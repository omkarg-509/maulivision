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

// Daily milk table with improved date-wise display
$html = '
<style>
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
.date-cell {
    background-color: #f8f9fa;
    font-weight: bold;
}
.cow-cell {
    background-color: #fff3cd;
}
.buffalo-cell {
    background-color: #d4edda;
}
.total-cell {
    background-color: #e2e3e5;
    font-weight: bold;
}
.amount-cell {
    background-color: #cce5ff;
    font-weight: bold;
}
.grand-total {
    background-color: #dc3545;
    color: white;
    font-weight: bold;
}
.summary-header {
    background-color: #17a2b8;
    color: white;
    font-weight: bold;
}
</style>

<h3 style="text-align:center; margin: 15px 0; color: #2c3e50;">
� दैनिक दूध विवरण / Daily Milk Details<br>
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
$dailyTotalAmount = 0;

// Generate date range if no milk entries for some days
$dateRange = [];
$currentDate = new DateTime($startDate);
$endDateTime = new DateTime($endDate);

while ($currentDate <= $endDateTime) {
    $dateStr = $currentDate->format('Y-m-d');
    $dateRange[$dateStr] = isset($dailyMilk[$dateStr]) ? $dailyMilk[$dateStr] : ['cow' => 0, 'buffalo' => 0];
    $currentDate->modify('+1 day');
}

foreach ($dateRange as $date => $milk) {
    $dayCount++;
    $dayTotal = $milk['cow'] + $milk['buffalo'];
    $dayAmount = ($milk['cow'] * $cowRate) + ($milk['buffalo'] * $buffaloRate);
    $dailyTotalAmount += $dayAmount;
    
    // Format date in both English and Marathi style
    $dateObj = new DateTime($date);
    $dayName = $dateObj->format('D'); // Mon, Tue, etc.
    $formattedDate = $dateObj->format('d/m/Y');
    
    // Highlight days with no milk
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

// Weekly subtotals (every 7 days)
if ($dayCount > 7) {
    $html .= '<tr style="border-top: 3px solid #333;">
        <td colspan="6" style="text-align:center; font-style:italic; background-color: #e9ecef; padding: 5px;">
            <strong>साप्ताहिक सारांश उपलब्ध / Weekly summaries available on request</strong>
        </td>
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

// Performance analytics
$avgDaily = $dayCount > 0 ? $totalLiters / $dayCount : 0;
$cowPercentage = $totalLiters > 0 ? ($totalCow / $totalLiters) * 100 : 0;
$buffaloPercentage = $totalLiters > 0 ? ($totalBuffalo / $totalLiters) * 100 : 0;
$daysWithMilk = 0;
foreach ($dateRange as $milk) {
    if (($milk['cow'] + $milk['buffalo']) > 0) $daysWithMilk++;
}

$html .= '
<div style="margin: 15px 0;">
    <h4 style="color: #2c3e50; text-align: center;">📈 कामगिरी विश्लेषण / Performance Analytics</h4>
    <table class="milk-table" style="margin-top: 10px;">
        <tr class="summary-header">
            <td colspan="4"><strong>📊 सांख्यिकीय माहिती / Statistical Information</strong></td>
        </tr>
        <tr>
            <td width="25%"><strong>एकूण दिवस / Total Days:</strong></td>
            <td width="25%">' . $dayCount . ' दिवस</td>
            <td width="25%"><strong>दूध दिवस / Milk Supply Days:</strong></td>
            <td width="25%">' . $daysWithMilk . ' दिवस</td>
        </tr>
        <tr>
            <td><strong>दैनिक सरासरी / Daily Average:</strong></td>
            <td>' . number_format($avgDaily, 2) . ' L</td>
            <td><strong>पुरवठा दर / Supply Rate:</strong></td>
            <td>' . number_format(($daysWithMilk / max($dayCount, 1)) * 100, 1) . '%</td>
        </tr>
        <tr class="cow-cell">
            <td><strong>🐄 गाय दूध / Cow Milk:</strong></td>
            <td>' . number_format($totalCow, 1) . ' L</td>
            <td><strong>टक्केवारी / Percentage:</strong></td>
            <td>' . number_format($cowPercentage, 1) . '%</td>
        </tr>
        <tr class="buffalo-cell">
            <td><strong>🐃 म्हैस दूध / Buffalo Milk:</strong></td>
            <td>' . number_format($totalBuffalo, 1) . ' L</td>
            <td><strong>टक्केवारी / Percentage:</strong></td>
            <td>' . number_format($buffaloPercentage, 1) . '%</td>
        </tr>
    </table>
</div>';

// Monthly comparison (if applicable)
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
        <tr style="border-top: 2px solid #333;">
            <td colspan="3" class="grand-total"><strong>एकूण देय / TOTAL AMOUNT DUE</strong></td>
            <td class="grand-total"><strong>₹' . number_format($totalAmount, 2) . '</strong></td>
        </tr>
        <tr>
            <td colspan="3">(-) आधी भरलेली रक्कम / Previous Payments</td>
            <td>₹0.00</td>
        </tr>
        <tr>
            <td colspan="3">(-) सवलत / Discount</td>
            <td>₹0.00</td>
        </tr>
        <tr class="grand-total">
            <td colspan="3"><strong>📋 निव्वळ देय रक्कम / NET AMOUNT PAYABLE</strong></td>
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
