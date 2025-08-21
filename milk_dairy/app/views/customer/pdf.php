<?php 
// This should be called from a controller with proper data
// For now, we'll simulate the data structure

// Get parameters from URL or session
$customerId = $_GET['customer_id'] ?? 1;
$startDate = $_GET['start_date'] ?? date('Y-m-01'); // First day of current month
$endDate = $_GET['end_date'] ?? date('Y-m-t'); // Last day of current month
$cowRate = $_GET['cow_rate'] ?? 50;
$buffaloRate = $_GET['buffalo_rate'] ?? 60;

// Load TCPDF library
require_once __DIR__ . '/../lib/tcpdf/tcpdf.php';

// You would normally get this data from your controller/model
// For demo purposes, we'll simulate the data
$customerData = [
    'name' => 'Omkar Gaikwad',
    'bill_id' => '1200',
    'mobile' => '9822882755',
    'address' => 'Village: Gaywadi Nal'
];

// Simulate milk entries data (replace with actual database query)
$milkEntries = [
    ['date' => '2025-08-01', 'milktype' => 'cow', 'liter' => 2.0],
    ['date' => '2025-08-01', 'milktype' => 'buffalo', 'liter' => 1.5],
    ['date' => '2025-08-02', 'milktype' => 'cow', 'liter' => 2.0],
    ['date' => '2025-08-02', 'milktype' => 'buffalo', 'liter' => 1.5],
    ['date' => '2025-08-03', 'milktype' => 'cow', 'liter' => 2.5],
    ['date' => '2025-08-03', 'milktype' => 'buffalo', 'liter' => 1.0],
    // Add more entries as needed
];

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
        $dailyMilk[$date]['cow'] += $entry['liter'];
        $totalCow += $entry['liter'];
    } elseif ($entry['milktype'] === 'buffalo') {
        $dailyMilk[$date]['buffalo'] += $entry['liter'];
        $totalBuffalo += $entry['liter'];
    }
}

// Sort dates
ksort($dailyMilk);

// Calculate totals
$totalLiters = $totalCow + $totalBuffalo;
$cowAmount = $totalCow * $cowRate;
$buffaloAmount = $totalBuffalo * $buffaloRate;
$totalAmount = $cowAmount + $buffaloAmount;

$pdf = new Tcpdf();
$pdf->SetCreator('tc-lib-pdf');
$pdf->SetAuthor('Rajnandini Dairy');
$pdf->SetTitle('Milk Dairy Bill - ' . $customerData['name']);

$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(true, 15);
$pdf->AddPage();

// Header
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
$pdf->Cell(95, 8, '👤 ग्राहक/Customer: ' . $customerData['name'], 1, 0);
$pdf->Cell(95, 8, '🏠 ' . $customerData['address'], 1, 1);
$pdf->Cell(95, 8, '📋 बिल क्रमांक/Bill No: ' . $customerData['bill_id'], 1, 0);
$pdf->Cell(95, 8, '📅 दिनांक/Date: ' . date('d/m/Y'), 1, 1);
$pdf->Cell(95, 8, '📅 कालावधी/Period: ' . date('d/m/Y', strtotime($startDate)), 1, 0);
$pdf->Cell(95, 8, 'ते/to: ' . date('d/m/Y', strtotime($endDate)), 1, 1);

$pdf->Ln(5);

// Daily milk table
$html = '
<style>
table.milk-table {
    width: 100%;
    border-collapse: collapse;
    font-family: dejavusans;
}
table.milk-table th, table.milk-table td {
    border: 1px solid #000;
    padding: 6px;
    text-align: center;
    font-size: 10px;
}
table.milk-table th {
    background-color: #f0f0f0;
    font-weight: bold;
}
.total-row {
    background-color: #e6f3ff;
    font-weight: bold;
}
.amount-row {
    background-color: #fff2cc;
    font-weight: bold;
}
</style>

<h3 style="text-align:center; margin: 10px 0;">🗓️ दैनिक दूध तपशील / Daily Milk Details</h3>

<table class="milk-table">
    <thead>
        <tr>
            <th width="15%">दिनांक<br>Date</th>
            <th width="20%">🐄 गाय दूध<br>Cow Milk (L)</th>
            <th width="20%">🐃 म्हैस दूध<br>Buffalo Milk (L)</th>
            <th width="20%">एकूण लिटर<br>Total Liters</th>
            <th width="25%">दैनिक रक्कम<br>Daily Amount (₹)</th>
        </tr>
    </thead>
    <tbody>';

$dayCount = 0;
foreach ($dailyMilk as $date => $milk) {
    $dayCount++;
    $dayTotal = $milk['cow'] + $milk['buffalo'];
    $dayAmount = ($milk['cow'] * $cowRate) + ($milk['buffalo'] * $buffaloRate);
    
    $html .= '<tr>
        <td>' . date('d/m/Y', strtotime($date)) . '</td>
        <td>' . number_format($milk['cow'], 1) . '</td>
        <td>' . number_format($milk['buffalo'], 1) . '</td>
        <td>' . number_format($dayTotal, 1) . '</td>
        <td>₹' . number_format($dayAmount, 2) . '</td>
    </tr>';
}

// Total rows
$html .= '
    <tr class="total-row">
        <td><strong>एकूण/Total</strong></td>
        <td><strong>' . number_format($totalCow, 1) . ' L</strong></td>
        <td><strong>' . number_format($totalBuffalo, 1) . ' L</strong></td>
        <td><strong>' . number_format($totalLiters, 1) . ' L</strong></td>
        <td><strong>₹' . number_format($totalAmount, 2) . '</strong></td>
    </tr>
</tbody>
</table>

<h3 style="text-align:center; margin: 15px 0;">💰 बिल सारांश / Bill Summary</h3>

<table class="milk-table">
    <thead>
        <tr>
            <th width="40%">दूध प्रकार / Milk Type</th>
            <th width="20%">प्रमाण / Quantity</th>
            <th width="20%">दर / Rate (₹/L)</th>
            <th width="20%">रक्कम / Amount</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>🐄 गाय दूध / Cow Milk</td>
            <td>' . number_format($totalCow, 1) . ' L</td>
            <td>₹' . $cowRate . '</td>
            <td>₹' . number_format($cowAmount, 2) . '</td>
        </tr>
        <tr>
            <td>🐃 म्हैस दूध / Buffalo Milk</td>
            <td>' . number_format($totalBuffalo, 1) . ' L</td>
            <td>₹' . $buffaloRate . '</td>
            <td>₹' . number_format($buffaloAmount, 2) . '</td>
        </tr>
        <tr class="amount-row">
            <td colspan="3"><strong>एकूण देय रक्कम / Total Amount Due</strong></td>
            <td><strong>₹' . number_format($totalAmount, 2) . '</strong></td>
        </tr>
        <tr>
            <td colspan="3">भरलेली रक्कम / Amount Paid</td>
            <td>₹0.00</td>
        </tr>
        <tr class="amount-row">
            <td colspan="3"><strong>शिल्लक रक्कम / Balance Due</strong></td>
            <td><strong>₹' . number_format($totalAmount, 2) . '</strong></td>
        </tr>
    </tbody>
</table>';

$pdf->writeHTML($html, true, false, false, false, '');

// Additional Info
$pdf->Ln(8);
$pdf->SetFont('dejavusans', 'B', 11);
$pdf->Cell(0, 6, '📊 तपशील / Details:', 0, 1);
$pdf->SetFont('dejavusans', '', 10);
$pdf->Cell(0, 5, '• एकूण दिवस / Total Days: ' . $dayCount, 0, 1);
$pdf->Cell(0, 5, '• सरासरी प्रतिदिन / Average per Day: ' . number_format($totalLiters / max($dayCount, 1), 1) . ' L', 0, 1);
$pdf->Cell(0, 5, '• गाय दूध टक्केवारी / Cow Milk %: ' . number_format(($totalCow / max($totalLiters, 1)) * 100, 1) . '%', 0, 1);
$pdf->Cell(0, 5, '• म्हैस दूध टक्केवारी / Buffalo Milk %: ' . number_format(($totalBuffalo / max($totalLiters, 1)) * 100, 1) . '%', 0, 1);

// Footer
$pdf->Ln(8);
$pdf->SetLineWidth(0.3);
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
$pdf->Ln(3);
$pdf->SetFont('dejavusans', 'I', 9);
$pdf->Cell(0, 5, 'कृपया बिलाची रक्कम लगेच भरून सही करा.', 0, 1, 'C');
$pdf->Cell(0, 5, 'Please arrange to pay the bill amount immediately and get the signature.', 0, 1, 'C');

$pdf->Ln(5);
$pdf->SetFont('dejavusans', '', 8);
$pdf->Cell(95, 5, 'ग्राहकाची सही / Customer Signature: ________________', 0, 0);
$pdf->Cell(95, 5, 'दुकानदाराची सही / Shop Signature: ________________', 0, 1);

// Output
$pdf->Output('rajnandini_dairy_bill_' . date('Y-m-d') . '.pdf', 'I');
?>
