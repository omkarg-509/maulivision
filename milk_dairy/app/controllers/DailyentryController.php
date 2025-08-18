<?php

require_once '../app/helpers/Auth.php';
class DailyentryController extends Controller
{
    public function index(){
        Auth::check();
        $dailyEntryModel = $this->model('DailyEntry');
        $dailyEntries = $dailyEntryModel->getAll();
        $this->view('dailyentry/index', ['dailyEntries' => $dailyEntries] );
        
    }

    public function store()
    {
        Auth::check();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'vid' => $_POST['vid'] ?? '',
                'cid' => $_POST['cid'] ?? '',
                'milktype' => $_POST['milktype'] ?? '',
                'milkliter' => $_POST['milkliter'] ?? '',
            ];

            // Debug: Check if all required fields are present
            if (empty($data['vid']) || empty($data['cid']) || empty($data['milktype']) || empty($data['milkliter'])) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'All fields are required.', 'data' => $data]);
                exit;
            }

            $dailyEntryModel = $this->model('DailyEntry');
            $result = $dailyEntryModel->insert($data);

            header('Content-Type: application/json');
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Entry added successfully.']);
            } else {
                // Debug: Output the data and possible error info
                if (method_exists($dailyEntryModel, 'getLastError')) {
                    $error = $dailyEntryModel->getLastError();
                } else {
                    $error = 'Unknown error';
                }
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to add entry.',
                    'data' => $data,
                    'error' => $error
                ]);
            }
            exit;
        }
    }
    public function list()
    {
        Auth::check();
        $dailyEntryModel = $this->model('DailyEntry');
        $dailyEntries = $dailyEntryModel->getAll();
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'data' => $dailyEntries]);
        exit;
    }

    public function pdf($bill_id)
    {
        Auth::check();

        // Start output buffering to prevent headers already sent errors
        if (ob_get_level() == 0) ob_start();

        // Load TCPDF library
        require_once __DIR__ . '/../lib/tcpdf/tcpdf.php';

        // // Fetch bill and customer info
        // $billModel = $this->model('Bill');
        // $bill = $billModel->getById($bill_id);

        // if (!$bill) {
        //     echo "Bill not found.";
        //     exit;
        // }

        // $customerModel = $this->model('Customer');
        // $customer = $customerModel->getById($bill['customer_id']);

        // Fetch daily entries for this bill/customer/month
        $dailyEntryModel = $this->model('DailyEntry');
        $entries = $dailyEntryModel->getByBillId($bill_id);

        // Prepare data: group by date, type
        $days = [];
        foreach ($entries as $entry) {
            $day = date('j', strtotime($entry['date']));
            if (!isset($days[$day])) {
                $days[$day] = ['cow' => '', 'buffalo' => ''];
            }
            if (strtolower($entry['milktype']) == 'cow') {
                $days[$day]['cow'] = $entry['milkliter'];
            } elseif (strtolower($entry['milktype']) == 'buffalo') {
                $days[$day]['buffalo'] = $entry['milkliter'];
            }
        }

        // Prepare rows for 1-31
        $rows = [];
        for ($i = 1; $i <= 31; $i++) {
            $left = isset($days[$i]) ? [$i, $days[$i]['cow'], $days[$i]['buffalo']] : [$i, '', ''];
            $j = $i + 16;
            $right = ($j <= 31 && isset($days[$j])) ? [$j, $days[$j]['cow'], $days[$j]['buffalo']] : [($j <= 31 ? $j : ''), '', ''];
            $rows[] = array_merge($left, $right);
            if ($j >= 31) break;
        }

        $pdf = new Tcpdf();
        $pdf->SetCreator('tc-lib-pdf');
        $pdf->SetAuthor('Rajnandini Dairy');
        $pdf->SetTitle('Milk Dairy Bill');

        $pdf->SetMargins(10, 10, 10);
        $pdf->SetAutoPageBreak(true, 15);
        $pdf->AddPage();

        // Heading
        $pdf->SetFont('dejavusans', 'B', 18);
        $pdf->Cell(0, 10, 'Rajnandini Dairy', 0, 1, 'C');
        $pdf->SetFont('dejavusans', '', 9);
        $pdf->Cell(0, 6, 'Mhasoba Chowk, Gaywadi Nal, Phone: 9822882755', 0, 1, 'C');

        // Customer Info
        $pdf->Ln(3);
        $pdf->SetFont('dejavusans', '', 11);
        $pdf->Cell(95, 7, $customer ? $customer['name'] : 'Customer', 1, 0);
        $pdf->Cell(95, 7, 'Village: ' . 'village', 1, 1);
        $pdf->Cell(95, 7, 'Bill No: ' . 'id', 1, 0);
        $pdf->Cell(95, 7, 'Date: ' . date('d/m/y', 'date')), 1, 1);

        // Table Header
        $html = '
        <style>
        table.responsive {
            width: 100%;
            border-collapse: collapse;
        }
        table.responsive th, table.responsive td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }
        </style>
        <table class="responsive" border="1" cellpadding="4">
            <thead>
            <tr style="font-weight:bold; text-align:center;">
            <th>Date</th>
            <th>Cow</th>
            <th>Buffalo</th>
            <th>Date</th>
            <th>Cow</th>
            <th>Buffalo</th>
            </tr>
            </thead>
            <tbody>';

        foreach ($rows as $r) {
            $html .= '<tr>
            <td align="center">'.$r[0].'</td>
            <td align="center">'.$r[1].'</td>
            <td align="center">'.$r[2].'</td>
            <td align="center">'.$r[3].'</td>
            <td align="center">'.$r[4].'</td>
            <td align="center">'.$r[5].'</td>
            </tr>';
        }

        // Calculate totals
        $total = 0;
        foreach ($days as $d) {
            $total += floatval($d['cow']) + floatval($d['buffalo']);
        }

        $amount_paid = $bill['amount_paid'] ?? 0;
        $balance_due = $total - $amount_paid;

        $html .= '
            <tr>
            <td colspan="5" align="right">Total</td>
            <td align="right">'.number_format($total, 2).'</td>
            </tr>
            <tr>
            <td colspan="5" align="right">Amount Paid</td>
            <td align="right">'.number_format($amount_paid, 2).'</td>
            </tr>
            <tr>
            <td colspan="5" align="right">Balance Due</td>
            <td align="right">'.number_format($balance_due, 2).'</td>
            </tr>';

        $html .= '</tbody></table>';

        $pdf->Ln(5);
        $pdf->writeHTML($html, true, false, false, false, '');

        // Footer Note
        $pdf->Ln(5);
        $pdf->SetFont('dejavusans', '', 9);
        $pdf->Cell(0, 6, 'Please arrange to pay the bill amount immediately and get the signature.', 0, 1, 'C');

        // Output
        $pdf->Output('dairy_bill.pdf', 'I');
        exit;
    }

    
public function delete($id)
{
    Auth::check();
    $dailyEntryModel = $this->model('DailyEntry');
    $result = $dailyEntryModel->delete($id);

    header('Content-Type: application/json');
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Entry deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete entry.']);
    }
    exit;
}


}