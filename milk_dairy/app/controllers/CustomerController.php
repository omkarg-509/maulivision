<?php
require_once '../app/helpers/Auth.php';
class CustomerController extends Controller
{
    public function index()
    {
        
              Auth::check(); // ✅ session check
        $customerModel = $this->model('Customer');
        $customers = $customerModel->getAll();

        $this->view('customer/index', ['customers' => $customers]);

    }
    public function pdf($billId , $Date)
    {
            // Auth::check(); // ✅ session check
        $customerModel = $this->model('Customer');
        $billId = $billId ?? null;
        $customers = $customerModel->getByBillId($billId);

        // Check if customer exists
        if (empty($customers) || !isset($customers[0])) {
            die('No customer found for the provided bill ID.');
        }

        // Start output buffering to prevent headers already sent errors
        if (ob_get_level() == 0) ob_start();

        // Load TCPDF library
        require_once __DIR__ . '/../lib/tcpdf/tcpdf.php';

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
        $pdf->Cell(95, 7,'Name: ' . $customers[0]['name'], 1, 0);
        $pdf->Cell(95, 7, 'Village: 110125', 1, 1);
        $pdf->Cell(95, 7, 'Bill No: ' . $customers[0]['bill_id'], 1, 0);
        $pdf->Cell(95, 7, 'Date: '. $Date .'', 1, 1);

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

        // Fetch daily milk entries for the customer and group by day
        $customerId = $customers[0]['id'] ?? null;
        $vid = $_SESSION['vendor']['id'] ?? 's1';
        $rows = [];

        if ($customerId && $vid) {
            // Get daily entries for this customer and vendor
            $milk_entries = $customerModel->DailyEntries($vid, $customerId);

            // Group entries by day (date)
            $grouped = [];
            foreach ($milk_entries as $entry) {
                $day = date('j', strtotime($entry['created_at'])); // Day of month (1-31)
                if (!isset($grouped[$day])) {
                    $grouped[$day] = [];
                }
                $grouped[$day][] = $entry;
            }

            // Prepare rows for the table: two columns per row (left and right day)
            $days = range(1, 31);
            for ($i = 0; $i < count($days); $i += 2) {
                $leftDay = $days[$i];
                $rightDay = $days[$i + 1] ?? null;

                // Left day data
                $leftEntries = $grouped[$leftDay] ?? [];
                $leftCow = '';
                $leftBuffalo = '';
                if (count($leftEntries) > 0) {
                    // If multiple entries, join values with comma
                    $leftCow = implode(', ', array_column($leftEntries, 'cow'));
                    $leftBuffalo = implode(', ', array_column($leftEntries, 'buffalo'));
                }

                // Right day data
                $rightEntries = $rightDay ? ($grouped[$rightDay] ?? []) : [];
                $rightCow = '';
                $rightBuffalo = '';
                if (count($rightEntries) > 0) {
                    $rightCow = implode(', ', array_column($rightEntries, 'cow'));
                    $rightBuffalo = implode(', ', array_column($rightEntries, 'buffalo'));
                }

                $rows[] = [
                    $leftDay,
                    $leftCow,
                    $leftBuffalo,
                    $rightDay ?? '',
                    $rightCow,
                    $rightBuffalo
                ];
            }
        } else {
            // Fallback: empty rows if no customer or vendor
            $rows = [];
        }

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
        $html .= ''. $vid .'
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

    public function create()
    {
          Auth::check(); // ✅ session check
           $customerModel = $this->model('Customer');
        $customers = $customerModel->getAll();
        $this->view('customer/create',['customers' => $customers]);
    }


      public function store()
    {
        Auth::check();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'vid' => $_POST['vid'] ?? '',
                'bill_id' => $_POST['bill_id'] ?? '',
                'name' => $_POST['name'] ?? '',
                'mobile' => $_POST['mobile'] ?? '',
                'address' => $_POST['address'] ?? '',
            ];

            // Debug: Check if all required fields are present
            if (empty($data['vid']) || empty($data['bill_id']) || empty($data['name']) || empty($data['mobile']) || empty($data['address'])) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'All fields are required.', 'data' => $data]);
                exit;
            }

            $customerModel = $this->model('Customer');
            $result = $customerModel->insert($data);

            header('Content-Type: application/json');
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'New Customers added successfully.']);
            } else {
                // Debug: Output the data and possible error info
                if (method_exists($customerModel, 'getLastError')) {
                    $error = $customerModel->getLastError();
                } else {
                    $error = 'Unknown error';
                }
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to New Customers.',
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
        $customerModel = $this->model('Customer');
        $customers = $customerModel->getAll();
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'data' => $customers]);
        exit;
    }

 public function delete($id)
{
    Auth::check();
    $customerModel = $this->model('Customer');
    $result = $customerModel->delete($id);

    header('Content-Type: application/json');
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Customer deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete customer.']);
    }
    exit;
}
public function searchCustomer()
{
    if (isset($_GET['term'])) {
        $term = trim($_GET['term']);
        if (strlen($term) < 2) {
            echo json_encode([]);
            return;
        }

        $customerModel = $this->model('Customer');
        $results = $customerModel->searchByTerm($term);

        header('Content-Type: application/json');
        echo json_encode($results);
        exit;
    }
    
}

public function update($id)
{
    Auth::check();
    $customerModel = $this->model('Customer');
    $customer = $customerModel->getById($id);

    if ($customer) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($customerModel->updateData($id, $_POST)) {
                echo "<script>alert('Data successfully updated'); window.location.href='/public/customer/show/{$id}';</script>";
            } else {
                echo "<script>alert('No changes were made'); window.location.href='/public/customer/show/{$id}';</script>";
            }
            exit;
        } else {
            $this->view('customer/view', ['customer' => $customer]);
        }
    } else {
        echo "Customer data not found.";
    }
}


public function show($id)
{ Auth::check(); // ✅ session check
    $customerModel = $this->model('Customer');
    $customer = $customerModel->getById($id);

    if ($customer) {
        $vid = $_SESSION['vendor']['id']; // Get the vendor ID from the session
         $milk_entries = $customerModel->getDailyEntries($vid, $id);
        // $this->view('customer/view', ['customer' => $customer]);
        $this->view('customer/view', [
    'customer' => $customer,
    'customerId' => $id,
    'milk_entries' => $milk_entries]);
    } else {
        echo "Customer data not found.";
    }
}

// public function dailyEntries($vid, $cid)
// {
//     Auth::check();
//     $customerModel = $this->model('Customer');
//     $customer = $customerModel->getById($cid);

//     if ($customer) {
//         $milk_entries = $customerModel->getDailyEntries($vid, $cid);
//         $this->view('customer/view', [
//     'customer' => $customer,
//     'customerId' => $cid,
//     'milk_entries' => $milk_entries
// ]);
//     } else {
//         echo "Customer data not found.";
//     }
// }

}
