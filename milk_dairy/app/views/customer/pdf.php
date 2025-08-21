<?php
// Milk Dairy PDF (Customer Bill) – bilingual (Marathi + English)
// FIX: Repaired corrupted file, now uses real DB data instead of hard‑coded samples.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Basic guard
if (empty($_SESSION['vendor']['id'])) {
    die('Unauthorized: Please login as vendor.');
}

// Input params (sanitized)
$customerId = isset($_GET['customer_id']) ? (int)$_GET['customer_id'] : 0;
$startDate = isset($_GET['start_date']) ? preg_replace('/[^0-9\-]/', '', $_GET['start_date']) : date('Y-m-01');
$endDate   = isset($_GET['end_date']) ? preg_replace('/[^0-9\-]/', '', $_GET['end_date']) : date('Y-m-t');

// Rates (allow decimal); defaults if invalid
$cowRate = isset($_GET['cow_rate']) && is_numeric($_GET['cow_rate']) ? (float)$_GET['cow_rate'] : 50.0;
$buffaloRate = isset($_GET['buffalo_rate']) && is_numeric($_GET['buffalo_rate']) ? (float)$_GET['buffalo_rate'] : 60.0;

// Ensure chronological order
if (strtotime($startDate) === false || strtotime($endDate) === false) {
    $startDate = date('Y-m-01');
    $endDate = date('Y-m-t');
}
if ($startDate > $endDate) {
    [$startDate, $endDate] = [$endDate, $startDate];
}

$vendorId = (int)$_SESSION['vendor']['id'];

// Load dependencies
require_once __DIR__ . '/../../models/Customer.php';
require_once __DIR__ . '/../../models/DailyEntry.php';
require_once __DIR__ . '/../../models/Setting.php'; // optional for business info if exists
require_once __DIR__ . '/../../lib/tcpdf/tcpdf.php';

// Fetch customer
$customerModel = new Customer();
$customer = $customerModel->getById($customerId);
if (!$customer) {
    die('Customer not found');
}
if (isset($customer['vid']) && (int)$customer['vid'] !== $vendorId) {
    die('Access denied to this customer');
}

// Fetch daily entries aggregated per date & milktype
$dailyEntryModel = new DailyEntry();
$rawEntries = $dailyEntryModel->getEntriesByDateRange($customerId, $startDate, $endDate, $vendorId);

// Transform to per-date structure
$dailyMilk = [];
$totalCow = 0.0; $totalBuffalo = 0.0;
foreach ($rawEntries as $row) {
    $date = $row['date'];
    if (!isset($dailyMilk[$date])) {
        $dailyMilk[$date] = ['cow' => 0.0, 'buffalo' => 0.0];
    }
    if ($row['milktype'] === 'cow') {
        $dailyMilk[$date]['cow'] += (float)$row['liter'];
        $totalCow += (float)$row['liter'];
    } elseif ($row['milktype'] === 'buffalo') {
        $dailyMilk[$date]['buffalo'] += (float)$row['liter'];
        $totalBuffalo += (float)$row['liter'];
    }
}

// Fill missing dates with zeros
$dateRange = [];
$current = new DateTime($startDate);
$end = new DateTime($endDate);
while ($current <= $end) {
    $ds = $current->format('Y-m-d');
    $dateRange[$ds] = $dailyMilk[$ds] ?? ['cow' => 0.0, 'buffalo' => 0.0];
    $current->modify('+1 day');
}

// Business / vendor details (fallbacks)
$businessName = $_SESSION['vendor']['business_name'] ?? 'Rajnandini Dairy';
$businessAddress = $_SESSION['vendor']['business_address'] ?? 'Mhasoba Chowk, Gaywadi Nal';
$businessNumber = $_SESSION['vendor']['business_number'] ?? '0000000000';

// Totals
$totalLiters = $totalCow + $totalBuffalo;
$cowAmount = $totalCow * $cowRate;
$buffaloAmount = $totalBuffalo * $buffaloRate;
$totalAmount = $cowAmount + $buffaloAmount;

// Prepare PDF
$pdf = new TCPDF();
$pdf->SetCreator($businessName);
$pdf->SetAuthor($businessName);
$pdf->SetTitle('Milk Bill - ' . $customer['name']);
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(true, 15);
$pdf->AddPage();

// Header
$pdf->SetFont('dejavusans', 'B', 20);
$pdf->Cell(0, 12, mb_strtoupper($businessName), 0, 1, 'C');
$pdf->SetFont('dejavusans', '', 10);
$pdf->Cell(0, 6, $businessAddress, 0, 1, 'C');
$pdf->Cell(0, 6, 'Phone: ' . $businessNumber, 0, 1, 'C');
$pdf->Ln(3);
$pdf->SetLineWidth(0.5);
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
$pdf->Ln(5);

// Customer Info
$pdf->SetFont('dejavusans', '', 11);
$pdf->Cell(95, 8, 'ग्राहक / Customer: ' . $customer['name'], 1, 0);
$pdf->Cell(95, 8, ($customer['address'] ?? ''), 1, 1);
$pdf->Cell(95, 8, 'बिल क्र./ Bill No: ' . ($customer['bill_id'] ?? $customer['id']), 1, 0);
$pdf->Cell(95, 8, 'दिनांक / Date: ' . date('d/m/Y'), 1, 1);
$pdf->Cell(95, 8, 'कालावधी / Period: ' . date('d/m/Y', strtotime($startDate)), 1, 0);
$pdf->Cell(95, 8, 'ते / to: ' . date('d/m/Y', strtotime($endDate)), 1, 1);
$pdf->Ln(5);

// Build HTML table
$html = '<style>
table.milk-table { width:100%; border-collapse:collapse; font-family:dejavusans; }
table.milk-table th, table.milk-table td { border:1px solid #000; padding:6px; text-align:center; font-size:11px; }
table.milk-table th { background:#4CAF50; color:#fff; }
.date-cell{background:#f8f9fa;font-weight:bold;}
.cow-cell{background:#fff3cd;}
.buffalo-cell{background:#d4edda;}
.total-cell{background:#e2e3e5;font-weight:bold;}
.amount-cell{background:#cce5ff;font-weight:bold;}
.grand-total{background:#dc3545;color:#fff;font-weight:bold;}
.summary-header{background:#17a2b8;color:#fff;font-weight:bold;}
</style>';

$html .= '<h3 style="text-align:center; margin:12px 0; color:#2c3e50;">दैनिक दूध विवरण / Daily Milk Details<br><small>(' . date('d/m/Y', strtotime($startDate)) . ' ते ' . date('d/m/Y', strtotime($endDate)) . ')</small></h3>';

$html .= '<table class="milk-table"><thead><tr>
<th width="12%">क्रमांक<br>Sr.</th>
<th width="18%">📅 दिनांक<br>Date</th>
<th width="18%">🐄 गाय दूध<br>Cow (L)</th>
<th width="18%">🐃 म्हैस दूध<br>Buffalo (L)</th>
<th width="16%">📊 एकूण<br>Total (L)</th>
<th width="18%">💰 रक्कम<br>Amount</th>
</tr></thead><tbody>';

$dayCount = 0; $dailyTotalAmount = 0.0; $daysWithMilk = 0;
foreach ($dateRange as $date => $milk) {
    $dayCount++;
    $dayTotal = $milk['cow'] + $milk['buffalo'];
    $dayAmount = ($milk['cow'] * $cowRate) + ($milk['buffalo'] * $buffaloRate);
    $dailyTotalAmount += $dayAmount;
    if ($dayTotal > 0) { $daysWithMilk++; }
    $dateObj = new DateTime($date);
    $formattedDate = $dateObj->format('d/m/Y');
    $dayName = $dateObj->format('D');
    $rowClass = ($dayTotal == 0) ? ' style="background:#f8d7da;"' : '';
    $html .= '<tr' . $rowClass . '>
        <td class="date-cell">' . $dayCount . '</td>
        <td class="date-cell">' . $formattedDate . '<br><small>' . $dayName . '</small></td>
        <td class="cow-cell">' . ($milk['cow']>0?number_format($milk['cow'],1):'-') . '</td>
        <td class="buffalo-cell">' . ($milk['buffalo']>0?number_format($milk['buffalo'],1):'-') . '</td>
        <td class="total-cell">' . ($dayTotal>0?number_format($dayTotal,1):'-') . '</td>
        <td class="amount-cell">₹' . ($dayAmount>0?number_format($dayAmount,2):'0.00') . '</td>
    </tr>';
}

if ($dayCount > 7) {
    $html .= '<tr style="border-top:3px solid #333;"><td colspan="6" style="text-align:center;background:#e9ecef; font-style:italic;"><strong>साप्ताहिक सारांश विनंतीनुसार / Weekly summary on request</strong></td></tr>';
}

$html .= '<tr class="grand-total"><td colspan="2">🎯 महिना एकूण / TOTAL</td><td>' . number_format($totalCow,1) . ' L</td><td>' . number_format($totalBuffalo,1) . ' L</td><td>' . number_format($totalLiters,1) . ' L</td><td>₹' . number_format($totalAmount,2) . '</td></tr>';
$html .= '</tbody></table>';

// Analytics
$avgDaily = $dayCount ? $totalLiters / $dayCount : 0;
$cowPercentage = $totalLiters ? ($totalCow / $totalLiters) * 100 : 0;
$buffaloPercentage = $totalLiters ? ($totalBuffalo / $totalLiters) * 100 : 0;

$html .= '<div style="margin:14px 0;">
<h4 style="text-align:center;color:#2c3e50;">📈 कामगिरी विश्लेषण / Performance Analytics</h4>
<table class="milk-table" style="margin-top:6px;">
<tr class="summary-header"><td colspan="4">📊 सांख्यिकीय माहिती / Statistics</td></tr>
<tr><td width="25%">एकूण दिवस / Days</td><td width="25%">' . $dayCount . '</td><td width="25%">दूध पुरवठा दिवस / Supply Days</td><td width="25%">' . $daysWithMilk . '</td></tr>
<tr><td>दैनिक सरासरी / Daily Avg</td><td>' . number_format($avgDaily,2) . ' L</td><td>पुरवठा दर / Supply %</td><td>' . number_format(($daysWithMilk/max($dayCount,1))*100,1) . '%</td></tr>
<tr class="cow-cell"><td>🐄 गाय दूध / Cow</td><td>' . number_format($totalCow,1) . ' L</td><td>टक्केवारी / %</td><td>' . number_format($cowPercentage,1) . '%</td></tr>
<tr class="buffalo-cell"><td>🐃 म्हैस दूध / Buffalo</td><td>' . number_format($totalBuffalo,1) . ' L</td><td>टक्केवारी / %</td><td>' . number_format($buffaloPercentage,1) . '%</td></tr>
</table></div>';

// Billing summary
$html .= '<h4 style="text-align:center; margin-top:18px; color:#2c3e50;">💰 बिल सारांश / Bill Summary</h4><table class="milk-table"><thead><tr class="summary-header"><th width="30%">तपशील / Description</th><th width="20%">प्रमाण / Qty</th><th width="20%">दर / Rate</th><th width="30%">रक्कम / Amount</th></tr></thead><tbody>';
$html .= '<tr class="cow-cell"><td>🐄 गाय / Cow Milk</td><td>' . number_format($totalCow,1) . ' L</td><td>₹' . number_format($cowRate,2) . '</td><td>₹' . number_format($cowAmount,2) . '</td></tr>';
$html .= '<tr class="buffalo-cell"><td>🐃 म्हैस / Buffalo Milk</td><td>' . number_format($totalBuffalo,1) . ' L</td><td>₹' . number_format($buffaloRate,2) . '</td><td>₹' . number_format($buffaloAmount,2) . '</td></tr>';
$html .= '<tr style="border-top:2px solid #333;"><td colspan="3" class="grand-total">एकूण / TOTAL</td><td class="grand-total">₹' . number_format($totalAmount,2) . '</td></tr>';
$html .= '<tr><td colspan="3">(-) आधी भरलेले / Previous Payments</td><td>₹0.00</td></tr>';
$html .= '<tr><td colspan="3">(-) सवलत / Discount</td><td>₹0.00</td></tr>';
$html .= '<tr class="grand-total"><td colspan="3">निव्वळ देय / NET PAYABLE</td><td>₹' . number_format($totalAmount,2) . '</td></tr>';
$html .= '</tbody></table>';

$pdf->writeHTML($html, true, false, false, false, '');

// Additional textual summary
$pdf->Ln(6);
$pdf->SetFont('dejavusans', 'B', 11);
$pdf->Cell(0, 6, '📊 तपशील / Details:', 0, 1);
$pdf->SetFont('dejavusans', '', 10);
$pdf->Cell(0, 5, '• एकूण दिवस / Total Days: ' . $dayCount, 0, 1);
$pdf->Cell(0, 5, '• सरासरी प्रतिदिन / Avg per Day: ' . number_format(($dayCount?($totalLiters/$dayCount):0),1) . ' L', 0, 1);
$pdf->Cell(0, 5, '• गाय दूध %: ' . number_format($cowPercentage,1) . '%', 0, 1);
$pdf->Cell(0, 5, '• म्हैस दूध %: ' . number_format($buffaloPercentage,1) . '%', 0, 1);

// Footer
$pdf->Ln(6);
$pdf->SetLineWidth(0.3);
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
$pdf->Ln(3);
$pdf->SetFont('dejavusans', 'I', 9);
$pdf->Cell(0, 5, 'कृपया बिलाची रक्कम लगेच जमा करा.', 0, 1, 'C');
$pdf->Cell(0, 5, 'Please pay the bill amount immediately.', 0, 1, 'C');
$pdf->Ln(5);
$pdf->SetFont('dejavusans', '', 8);
$pdf->Cell(95, 5, 'ग्राहक सही / Customer: ________________', 0, 0);
$pdf->Cell(95, 5, 'स्वाक्षरी / Signature: ________________', 0, 1);

// Output file
$pdf->Output('milk_bill_' . $customer['id'] . '_' . date('Ymd') . '.pdf', 'I');
exit; // prevent any stray output
?>

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

