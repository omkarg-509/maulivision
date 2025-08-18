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
   <?php
namespace App\Controllers;

use Com\Tecnick\Pdf\Tcpdf;

class BillController
{
    public function dairyBill()
    {
        $pdf = new Tcpdf();
        $pdf->SetCreator('tc-lib-pdf');
        $pdf->SetAuthor('Rajnandini Dairy');
        $pdf->SetTitle('Milk Dairy Bill');

        $pdf->SetMargins(10, 10, 10);
        $pdf->SetAutoPageBreak(true, 15);
        $pdf->AddPage();

        // Heading
        $pdf->SetFont('dejavusans', 'B', 18);
        $pdf->Cell(0, 10, 'राजनंदिनी डेअरी', 0, 1, 'C');
        $pdf->SetFont('dejavusans', '', 9);
        $pdf->Cell(0, 6, 'म्हसोबा चौक, गायवाडी नळ, फोन: 9822882755', 0, 1, 'C');

        // Customer Info
        $pdf->Ln(3);
        $pdf->SetFont('dejavusans', '', 11);
        $pdf->Cell(95, 7, 'श्री. विलास वांकुद्रे', 1, 0);
        $pdf->Cell(95, 7, 'गाव: 110125', 1, 1);
        $pdf->Cell(95, 7, 'बिल नं: 1200', 1, 0);
        $pdf->Cell(95, 7, 'तारीख: 11/01/25', 1, 1);

        // Table Header
        $html = '
        <table border="1" cellpadding="4">
            <thead>
                <tr style="font-weight:bold; text-align:center;">
                    <th width="30">दि.</th>
                    <th width="50">लिटर</th>
                    <th width="50">फॅट</th>
                    <th width="50">SNF</th>
                    <th width="70">दर/लिटर</th>
                    <th width="70">रक्कम</th>
                </tr>
            </thead>
            <tbody>';

        // Sample rows (replace with DB data later)
        $rows = [
            ['1', '1', '8.6', '9', '66.1', '66'],
            ['2', '1', '9.0', '9', '66.1', '66'],
            ['3', '1', '8.8', '9', '66.1', '66'],
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
                <td colspan="5" align="right">एकूण</td>
                <td align="right">1913</td>
            </tr>
            <tr>
                <td colspan="5" align="right">पैसे जमा</td>
                <td align="right">0</td>
            </tr>
            <tr>
                <td colspan="5" align="right">येणे बाकी</td>
                <td align="right">1914</td>
            </tr>';

        $html .= '</tbody></table>';

        $pdf->Ln(5);
        $pdf->writeHTML($html, true, false, false, false, '');

        // Footer Note
        $pdf->Ln(5);
        $pdf->SetFont('dejavusans', '', 9);
        $pdf->Cell(0, 6, 'कृपया बिलाचे पैसे त्वरित देण्याची व्यवस्था करावी व सही घ्यावी.', 0, 1, 'C');

        // Output
        $pdf->Output('dairy_bill.pdf', 'I');
        exit;
    }
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
