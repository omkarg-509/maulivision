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
    public function pdf($billId = null, $Date = null)
    {
        // Get parameters from URL or function parameters
        $customerId = $_GET['customer_id'] ?? $billId ?? null;
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-t');
        $cowRate = $_GET['cow_rate'] ?? 50;
        $buffaloRate = $_GET['buffalo_rate'] ?? 60;
        
        if (!$customerId) {
            die('Customer ID is required for PDF generation. Please provide customer_id parameter.');
        }

        try {
            $customerModel = $this->model('Customer');
            $dailyEntryModel = $this->model('DailyEntry');
            
            // Get customer details by ID (not bill_id)
            $customer = $customerModel->getById($customerId);
            
            // If not found by ID, try by bill_id
            if (!$customer) {
                $customers = $customerModel->getByBillId($customerId);
                $customer = !empty($customers) ? $customers[0] : null;
            }
            
            if (!$customer) {
                die('No customer found for ID: ' . $customerId);
            }
            
            // Get vendor ID from session
            $vendorId = $_SESSION['vendor_id'] ?? 1;
            
            // Get milk entries for the date range
            $milkEntries = $dailyEntryModel->getEntriesByDateRange($customerId, $startDate, $endDate, $vendorId);

            // Start output buffering to prevent headers already sent errors
            if (ob_get_level() == 0) ob_start();

            // Load TCPDF library
            require_once __DIR__ . '/../lib/tcpdf/tcpdf.php';

            // Create PDF with proper error handling
            $pdf = new TCPDF();
            $pdf->SetCreator('Rajnandini Dairy System');
            $pdf->SetAuthor('Rajnandini Dairy');
            $pdf->SetTitle('दूध बिल - ' . $customer['name']);
            $pdf->SetSubject('Milk Bill - ' . $customer['name']);

            // Set margins and auto page break
            $pdf->SetMargins(10, 10, 10);
            $pdf->SetAutoPageBreak(true, 15);
            $pdf->AddPage();

        // Process milk entries by date
        $dailyMilk = [];
        $totalCow = 0;
        $totalBuffalo = 0;

        foreach ($milkEntries as $entry) {
            $date = $entry['date'];
            if (!isset($dailyMilk[$date])) {
                $dailyMilk[$date] = ['cow' => 0, 'buffalo' => 0];
            }
            
            if ($entry['milktype'] === 'cow') {
                $dailyMilk[$date]['cow'] += floatval($entry['liter']);
                $totalCow += floatval($entry['liter']);
            } elseif ($entry['milktype'] === 'buffalo') {
                $dailyMilk[$date]['buffalo'] += floatval($entry['liter']);
                $totalBuffalo += floatval($entry['liter']);
            }
        }

        // Sort dates
        ksort($dailyMilk);

        // Calculate totals
        $totalLiters = $totalCow + $totalBuffalo;
        $cowAmount = $totalCow * $cowRate;
        $buffaloAmount = $totalBuffalo * $buffaloRate;
        $totalAmount = $cowAmount + $buffaloAmount;

        // Header with Unicode support
        $pdf->SetFont('dejavusans', 'B', 20);
        $pdf->Cell(0, 12, '🥛 राजनंदिनी डेयरी', 0, 1, 'C');
        $pdf->SetFont('dejavusans', 'B', 16);
        $pdf->Cell(0, 8, 'RAJNANDINI DAIRY', 0, 1, 'C');
        $pdf->SetFont('dejavusans', '', 10);
        $pdf->Cell(0, 6, 'म्हसोबा चौक, गायवाडी नाळ | Mhasoba Chowk, Gaywadi Nal', 0, 1, 'C');
        $pdf->Cell(0, 6, '📞 Phone: 9822882755', 0, 1, 'C');

        // Line separator
        $pdf->Ln(3);
        $pdf->SetLineWidth(0.5);
        $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
        $pdf->Ln(5);

        // Customer Info
        $pdf->SetFont('dejavusans', '', 12);
        $pdf->Cell(95, 8, '👤 ग्राहक/Customer: ' . $customer['name'], 1, 0);
        $pdf->Cell(95, 8, '🏠 पत्ता/Address: ' . ($customer['address'] ?? 'गायवाडी नाळ'), 1, 1);
        $pdf->Cell(95, 8, '📋 बिल क्रमांक/Bill No: ' . $customer['bill_id'], 1, 0);
        $pdf->Cell(95, 8, '📅 दिनांक/Date: ' . date('d/m/Y'), 1, 1);
        $pdf->Cell(95, 8, '📅 कालावधी/Period: ' . date('d/m/Y', strtotime($startDate)), 1, 0);
        $pdf->Cell(95, 8, 'ते/to: ' . date('d/m/Y', strtotime($endDate)), 1, 1);

        $pdf->Ln(5);

        // Generate date range
        $dateRange = [];
        $currentDate = new DateTime($startDate);
        $endDateTime = new DateTime($endDate);

        while ($currentDate <= $endDateTime) {
            $dateStr = $currentDate->format('Y-m-d');
            $dateRange[$dateStr] = isset($dailyMilk[$dateStr]) ? $dailyMilk[$dateStr] : ['cow' => 0, 'buffalo' => 0];
            $currentDate->modify('+1 day');
        }

        // Daily milk table with improved formatting
        $html = '
        <style>
        body { font-family: dejavusans; }
        table.milk-table {
            width: 100%;
            border-collapse: collapse;
            font-family: dejavusans;
        }
        table.milk-table th, table.milk-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
            font-size: 11px;
        }
        table.milk-table th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
        }
        .date-cell { background-color: #f8f9fa; font-weight: bold; }
        .cow-cell { background-color: #fff3cd; }
        .buffalo-cell { background-color: #d4edda; }
        .total-cell { background-color: #e2e3e5; font-weight: bold; }
        .amount-cell { background-color: #cce5ff; font-weight: bold; }
        .grand-total { background-color: #dc3545; color: white; font-weight: bold; }
        .summary-header { background-color: #17a2b8; color: white; font-weight: bold; }
        </style>

        <h3 style="text-align:center; margin: 15px 0; color: #2c3e50;">
        📊 दैनिक दूध विवरण / Daily Milk Details<br>
        <small style="font-size: 12px; color: #666;">(' . date('d/m/Y', strtotime($startDate)) . ' ते ' . date('d/m/Y', strtotime($endDate)) . ')</small>
        </h3>

        <table class="milk-table">
            <thead>
                <tr>
                    <th width="12%">क्रमांक<br>Sr. No.</th>
                    <th width="18%">📅 दिनांक<br>Date</th>
                    <th width="18%">🐄 गाय दूध<br>Cow Milk (L)</th>
                    <th width="18%">🐃 म्हैस दूध<br>Buffalo Milk (L)</th>
                    <th width="16%">📊 एकूण<br>Total (L)</th>
                    <th width="18%">💰 दैनिक रक्कम<br>Daily Amount</th>
                </tr>
            </thead>
            <tbody>';

        $dayCount = 0;

        foreach ($dateRange as $date => $milk) {
            $dayCount++;
            $dayTotal = $milk['cow'] + $milk['buffalo'];
            $dayAmount = ($milk['cow'] * $cowRate) + ($milk['buffalo'] * $buffaloRate);
            
            $dateObj = new DateTime($date);
            $dayName = $dateObj->format('D');
            $formattedDate = $dateObj->format('d/m/Y');
            
            $rowClass = ($dayTotal == 0) ? 'style="background-color: #f8d7da;"' : '';
            
            $html .= '<tr ' . $rowClass . '>
                <td class="date-cell">' . $dayCount . '</td>
                <td class="date-cell">' . $formattedDate . '<br><small>' . $dayName . '</small></td>
                <td class="cow-cell">' . ($milk['cow'] > 0 ? number_format($milk['cow'], 1) : '-') . '</td>
                <td class="buffalo-cell">' . ($milk['buffalo'] > 0 ? number_format($milk['buffalo'], 1) : '-') . '</td>
                <td class="total-cell">' . ($dayTotal > 0 ? number_format($dayTotal, 1) : '-') . '</td>
                <td class="amount-cell">₹' . ($dayAmount > 0 ? number_format($dayAmount, 2) : '0.00') . '</td>
            </tr>';
        }

        // Grand total row
        $html .= '
            <tr class="grand-total">
                <td colspan="2"><strong>🎯 महिना एकूण / MONTHLY TOTAL</strong></td>
                <td><strong>' . number_format($totalCow, 1) . ' L</strong></td>
                <td><strong>' . number_format($totalBuffalo, 1) . ' L</strong></td>
                <td><strong>' . number_format($totalLiters, 1) . ' L</strong></td>
                <td><strong>₹' . number_format($totalAmount, 2) . '</strong></td>
            </tr>
        </tbody>
        </table>';

        // Bill Summary
        $html .= '
        <h4 style="color: #2c3e50; text-align: center; margin-top: 20px;">💰 बिल सारांश / Bill Summary</h4>
        <table class="milk-table">
            <thead>
                <tr class="summary-header">
                    <th width="30%">तपशील / Description</th>
                    <th width="20%">प्रमाण / Quantity</th>
                    <th width="20%">दर / Rate (₹/L)</th>
                    <th width="30%">रक्कम / Amount (₹)</th>
                </tr>
            </thead>
            <tbody>
                <tr class="cow-cell">
                    <td>🐄 गाय दूध / Cow Milk</td>
                    <td>' . number_format($totalCow, 1) . ' L</td>
                    <td>₹' . $cowRate . '.00</td>
                    <td>₹' . number_format($cowAmount, 2) . '</td>
                </tr>
                <tr class="buffalo-cell">
                    <td>🐃 म्हैस दूध / Buffalo Milk</td>
                    <td>' . number_format($totalBuffalo, 1) . ' L</td>
                    <td>₹' . $buffaloRate . '.00</td>
                    <td>₹' . number_format($buffaloAmount, 2) . '</td>
                </tr>
                <tr class="grand-total">
                    <td colspan="3"><strong>📋 एकूण देय रक्कम / TOTAL AMOUNT DUE</strong></td>
                    <td><strong>₹' . number_format($totalAmount, 2) . '</strong></td>
                </tr>
            </tbody>
        </table>';

        $pdf->writeHTML($html, true, false, false, false, '');

        // Footer
        $pdf->Ln(8);
        $pdf->SetLineWidth(0.3);
        $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
        $pdf->Ln(3);
        $pdf->SetFont('dejavusans', 'I', 9);
        $pdf->Cell(0, 5, 'कृपया बिलाची रक्कम लगेच भरून सही करा. | Please arrange to pay the bill amount immediately.', 0, 1, 'C');

        $pdf->Ln(5);
        $pdf->SetFont('dejavusans', '', 8);
        $pdf->Cell(95, 5, 'ग्राहकाची सही / Customer Signature: ________________', 0, 0);
        // Clean output buffer and send PDF
        if (ob_get_contents()) ob_end_clean();
        
        // Output PDF
        $filename = 'rajnandini_dairy_bill_' . $customer['name'] . '_' . date('Y-m-d') . '.pdf';
        $pdf->Output($filename, 'I');
        
        } catch (Exception $e) {
            // Clean buffer on error
            if (ob_get_contents()) ob_end_clean();
            
            // Show error page
            header('Content-Type: text/html; charset=utf-8');
            echo "<h2>PDF Generation Error</h2>";
            echo "<p>Error: " . $e->getMessage() . "</p>";
            echo "<p>Please try again or contact support.</p>";
            echo "<a href='" . BASE_URL . "customer'>← Back to Customers</a>";
        }
    }

    public function viewCustomer($id)
    {
        Auth::check(); // ✅ session check
        $customerModel = $this->model('Customer');
        $customer = $customerModel->getById($id);

        if (!$customer) {
            header('Location: ' . BASE_URL . 'customer');
            exit;
        }

        $this->view('customer/view', ['customer' => $customer]);
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
        header('Location: /public/customer');
        exit;
        }
    } else {
        header('Location: /public/customer');
        exit;
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
