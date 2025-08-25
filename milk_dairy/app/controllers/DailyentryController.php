<?php

require_once '../app/helpers/Auth.php';
class DailyentryController extends Controller
{
    public function index(){
        Auth::check();
        $dailyEntryModel = $this->model('DailyEntry');
        
        // Get current vendor ID from session
        $vendorId = $_SESSION['vendor']['id'] ?? null;
        $vendorLng = $dailyEntryModel->fetchLngByVid($vendorId);
        // Get all daily entries for the current vendor
        $dailyEntries = $dailyEntryModel->getAll($vendorId);
        $this->view('dailyentry/index', ['dailyEntries' => $dailyEntries, 'vendorLng' => $vendorLng]);

    }

    public function store()
    {
        Auth::check();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'vid' => $_SESSION['vendor']['id'] ?? ($_POST['vid'] ?? ''),
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
        // If not POST just show form (already handled by index view) - nothing else here
    }

    /**
     * Show history page with date filters
     */
    public function history()
    {
        Auth::check();
        $this->view('dailyentry/history');
    }

    /**
     * AJAX endpoint: aggregated entries by date range for current vendor
     * Params: start (Y-m-d), end (Y-m-d)
     */
    public function historyData()
    {
        Auth::check();
        header('Content-Type: application/json');

        $vendorId = $_SESSION['vendor']['id'] ?? null;
        if (!$vendorId) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $start = $_GET['start'] ?? date('Y-m-01');
        $end   = $_GET['end'] ?? date('Y-m-d');

        // Basic validation
        if (!preg_match('/^\\d{4}-\\d{2}-\\d{2}$/', $start) || !preg_match('/^\\d{4}-\\d{2}-\\d{2}$/', $end)) {
            echo json_encode(['success' => false, 'message' => 'Invalid date format']);
            return;
        }

        $dailyEntryModel = $this->model('DailyEntry');
        $entries = $dailyEntryModel->getEntriesByDateRangeAll($vendorId, $start, $end);
        echo json_encode(['success' => true, 'data' => $entries]);
    }
    public function list($vid = null)
    {
        Auth::check();
        $dailyEntryModel = $this->model('DailyEntry');
        
        // Get vendor ID from parameter or session
        if ($vid === null) {
            $vid = $_SESSION['vendor']['id'] ?? null;
        }
        
        if ($vid !== null) {
            // Get daily entries for the specific vendor
            $dailyEntries = $dailyEntryModel->getAll($vid);
        } else {
            // If no vendor ID, return empty array
            $dailyEntries = [];
        }
        
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'data' => $dailyEntries]);
        exit;
    }

    public function pdf()
    {
        Auth::check(); // ✅ session check

        // Start output buffering to prevent headers already sent errors
        if (ob_get_level() == 0) ob_start();

        // Load TCPDF library
        require_once __DIR__ . '/../lib/tcpdf/tcpdf.php';

        // Fetch bill_id from GET or POST
        $bill_id = $_GET['bill_id'] ?? $_POST['bill_id'] ?? null;

        // Fetch bill data by bill_id
        $dailyEntryModel = $this->model('DailyEntry');
        $billData = null;
        if ($bill_id) {
            $billData = $dailyEntryModel->getByBillId($bill_id);
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
        $customerName = $billData['customer_name'] ?? 'Customer Name';
        $pdf->Cell(95, 7, $customerName, 1, 0);
        $pdf->Cell(95, 7, 'Village: 110125', 1, 1);
        $pdf->Cell(95, 7, 'Bill No: 1200', 1, 0);
        $pdf->Cell(95, 7, 'Date: 11/01/25', 1, 1);

        // Table Header with responsive CSS for mobile
        $html = '
        <style>
        @media only screen and (max-width: 600px) {
            table.responsive {
            font-size: 10px !important;
            }
            table.responsive th, table.responsive td {
            padding: 2px !important;
            }
        }
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

        // Sample rows (replace with DB data later)
        $rows = [
            ['1', '', '', '17', '', ''],
            ['2', '', '', '18', '', ''],
            ['3', '', '', '19', '', ''],
            ['4', '', '', '20', '', ''],
            ['5', '', '', '21', '', ''],
            ['6', '', '', '22', '', ''],
            ['7', '', '', '23', '', ''],
            ['8', '', '', '24', '', ''],
            ['9', '', '', '25', '', ''],
            ['10','', '', '26', '', ''],
            ['11','', '', '27', '', ''],
            ['12','', '', '28', '', ''],
            ['13','', '', '29', '', ''],
            ['14','', '', '30', '', ''],
            ['15','', '', '31', '', ''],

        ];

        foreach ($rows as $r) {
            $html .= '<tr>
            <td align="center">'.$r[0].'</td>
            <td align="center">'.$r[1].'</td>
            <td align="center">'.$r[2].'</td>
            <td align="center">'.$r[3].'</td>
            <td align="center">'.$r[4].'</td>
            <td align="right">'.$r[5].'</td>
            </tr>';
        }

        // Totals
        $html .= '
            <tr>
            <td colspan="5" align="right">Total</td>
            <td align="right">1913</td>
            </tr>
            <tr>
            <td colspan="5" align="right">Amount Paid</td>
            <td align="right">0</td>
            </tr>
            <tr>
            <td colspan="5" align="right">Balance Due</td>
            <td align="right">1914</td>
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