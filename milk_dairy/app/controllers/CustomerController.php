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
                <th>Cow (Ltr)</th>
                <th>Buffalo (Ltr)</th>
                </tr>
                </thead>
                <tbody>';

            // Fetch daily milk entries for the customer and group by date
            $customerId = 38;
            $vid =1;
            $rows = [];

            if ($customerId && $vid) {
                // Get daily entries for this customer and vendor
                // You should have a method like getDailyEntriesByDate($vid, $cid)
                $milk_entries = $customerModel->DailyEntries($vid, $customerId);

                // Group by date and sum by milktype
                $grouped = [];
                foreach ($milk_entries as $entry) {
                $date = date('Y-m-d', strtotime($entry['created_at']));
                if (!isset($grouped[$date])) {
                    $grouped[$date] = ['cow' => 0, 'buffalo' => 0];
                }
                if ($entry['milktype'] === 'cow') {
                    $grouped[$date]['cow'] += (float)$entry['milkliter'];
                } elseif ($entry['milktype'] === 'buffalo') {
                    $grouped[$date]['buffalo'] += (float)$entry['milkliter'];
                }
                }

                // Prepare rows for the table
                foreach ($grouped as $date => $types) {
                $rows[] = [
                    $date,
                    $types['cow'] > 0 ? $types['cow'] : '',
                    $types['buffalo'] > 0 ? $types['buffalo'] : ''
                ];
                }
            }

            foreach ($rows as $r) {
                $html .= '<tr>
                <td align="center">'.$r[0].'</td>
                <td align="center">'.$r[1].'</td>
                <td align="center">'.$r[2].'</td>
                </tr>';
            }

            // Totals
            $totalCow = array_sum(array_column($rows, 1));
            $totalBuffalo = array_sum(array_column($rows, 2));

            $html .= '
                <tr>
                <td align="right"><b>Total</b></td>
                <td align="center"><b>'.$totalCow.'</b></td>
                <td align="center"><b>'.$totalBuffalo.'</b></td>
                </tr>
            ';

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
        // Get vendor id from session
        $vid = $_SESSION['vendor']['id'] ?? null;
        if (!$vid) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Vendor ID not found in session.']);
            exit;
        }
        $customerModel = $this->model('Customer');
        $customers = $customerModel->getAll($vid); // You need to implement getByVendorId($vid) in your Customer model
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
    Auth::check(); // Add authentication check
    
    if (isset($_GET['term'])) {
        $term = trim($_GET['term']);
        if (strlen($term) < 2) {
            header('Content-Type: application/json');
            echo json_encode([]);
            return;
        }

        // Get vendor ID from session
        $vid = $_SESSION['vendor']['id'] ?? null;
        if (!$vid) {
            header('Content-Type: application/json');
            echo json_encode([]);
            return;
        }

        $customerModel = $this->model('Customer');
        $results = $customerModel->searchByTermAndVendor($term, $vid);

        header('Content-Type: application/json');
        echo json_encode($results);
        exit;
    }
    
    // If no term provided, return empty array
    header('Content-Type: application/json');
    echo json_encode([]);
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
{
    Auth::check(); // ✅ session check
    $customerModel = $this->model('Customer');
    $customer = $customerModel->getById($id);

    if ($customer) {
        $sessionVid = $_SESSION['vendor']['id'] ?? null;
        $customerVid = $customer['vid'] ?? null;

        if ($sessionVid && $customerVid && $sessionVid == $customerVid) {
            $milk_entries = $customerModel->getDailyEntries($sessionVid, $id);
            $this->view('customer/view', [
                'customer' => $customer,
                'customerId' => $id,
                'milk_entries' => $milk_entries
            ]);
        } else {
           $this->view('customer/view', ['customer' => $customer]);
        }
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
