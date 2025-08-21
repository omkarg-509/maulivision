<?php 
// PDF generation with English content only

// Get parameters from URL or session
$customerId = $_GET['customer_id'] ?? 1;
$startDate = $_GET['start_date'] ?? date('Y-m-01'); // First day of current month
$endDate = $_GET['end_date'] ?? date('Y-m-t'); // Last day of current month
$cowRate = $_GET['cow_rate'] ?? 50;
$buffaloRate = $_GET['buffalo_rate'] ?? 60;

// Load TCPDF library
require_once __DIR__ . '/../lib/tcpdf/tcpdf.php';

// Sample customer data (replace with actual database query)
$customerData = [
    'name' => 'Omkar Gaikwad',
    'bill_id' => '1200',
    'mobile' => '9822882755',
    'address' => 'Village: Gaywadi Nal'
];

// Sample milk entries data (replace with actual database query)
$milkEntries = [
    ['date' => '2025-08-01', 'milktype' => 'cow', 'liter' => 2.0],
    ['date' => '2025-08-01', 'milktype' => 'buffalo', 'liter' => 1.5],
    ['date' => '2025-08-02', 'milktype' => 'cow', 'liter' => 2.0],
    ['date' => '2025-08-02', 'milktype' => 'buffalo', 'liter' => 1.5],
    ['date' => '2025-08-03', 'milktype' => 'cow', 'liter' => 2.5],
    ['date' => '2025-08-03', 'milktype' => 'buffalo', 'liter' => 1.0],
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

$pdf = new TCPDF();
$pdf->SetCreator('Rajnandini Dairy');
$pdf->SetAuthor('Rajnandini Dairy');
$pdf->SetTitle('Milk Dairy Bill - ' . $customerData['name']);

$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(true, 15);
$pdf->AddPage();

// Header - English only
$pdf->SetFont('helvetica', 'B', 20);
$pdf->Cell(0, 12, 'RAJNANDINI DAIRY', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 6, 'Mhasoba Chowk, Gaywadi Nal', 0, 1, 'C');
$pdf->Cell(0, 6, 'Phone: 9822882755', 0, 1, 'C');

// Line separator
$pdf->Ln(3);
$pdf->SetLineWidth(0.5);
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
$pdf->Ln(5);

// Customer Info - English only
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(95, 8, 'Customer: ' . $customerData['name'], 1, 0);
$pdf->Cell(95, 8, $customerData['address'], 1, 1);
$pdf->Cell(95, 8, 'Bill No: ' . $customerData['bill_id'], 1, 0);
$pdf->Cell(95, 8, 'Date: ' . date('d/m/Y'), 1, 1);
$pdf->Cell(95, 8, 'Period: ' . date('d/m/Y', strtotime($startDate)), 1, 0);
$pdf->Cell(95, 8, 'to: ' . date('d/m/Y', strtotime($endDate)), 1, 1);

$pdf->Ln(5);

// Daily milk table - English only
$html = '
<style>
table.milk-table {
    width: 100%;
    border-collapse: collapse;
    font-family: helvetica;
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
Daily Milk Details<br>
<small style="font-size: 12px; color: #666;">(' . date('d/m/Y', strtotime($startDate)) . ' to ' . date('d/m/Y', strtotime($endDate)) . ')</small>
</h3>

<table class="milk-table">
    <thead>
        <tr>
            <th width="12%">Sr. No.</th>
            <th width="18%">Date</th>
            <th width="18%">Cow Milk (L)</th>
            <th width="18%">Buffalo Milk (L)</th>
            <th width="16%">Total (L)</th>
            <th width="18%">Daily Amount</th>
        </tr>
    </thead>
    <tbody>';

$dayCount = 0;

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
    
    // Format date in English style
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
        <td class="amount-cell">Rs.' . ($dayAmount > 0 ? number_format($dayAmount, 2) : '0.00') . '</td>
    </tr>';
}

// Grand total row
$html .= '
    <tr class="grand-total">
        <td colspan="2"><strong>MONTHLY TOTAL</strong></td>
        <td><strong>' . number_format($totalCow, 1) . ' L</strong></td>
        <td><strong>' . number_format($totalBuffalo, 1) . ' L</strong></td>
        <td><strong>' . number_format($totalLiters, 1) . ' L</strong></td>
        <td><strong>Rs.' . number_format($totalAmount, 2) . '</strong></td>
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
    <h4 style="color: #2c3e50; text-align: center;">Performance Analytics</h4>
    <table class="milk-table" style="margin-top: 10px;">
        <tr class="summary-header">
            <td colspan="4"><strong>Statistical Information</strong></td>
        </tr>
        <tr>
            <td width="25%"><strong>Total Days:</strong></td>
            <td width="25%">' . $dayCount . ' days</td>
            <td width="25%"><strong>Milk Supply Days:</strong></td>
            <td width="25%">' . $daysWithMilk . ' days</td>
        </tr>
        <tr>
            <td><strong>Daily Average:</strong></td>
            <td>' . number_format($avgDaily, 2) . ' L</td>
            <td><strong>Supply Rate:</strong></td>
            <td>' . number_format(($daysWithMilk / max($dayCount, 1)) * 100, 1) . '%</td>
        </tr>
        <tr class="cow-cell">
            <td><strong>Cow Milk:</strong></td>
            <td>' . number_format($totalCow, 1) . ' L</td>
            <td><strong>Percentage:</strong></td>
            <td>' . number_format($cowPercentage, 1) . '%</td>
        </tr>
        <tr class="buffalo-cell">
            <td><strong>Buffalo Milk:</strong></td>
            <td>' . number_format($totalBuffalo, 1) . ' L</td>
            <td><strong>Percentage:</strong></td>
            <td>' . number_format($buffaloPercentage, 1) . '%</td>
        </tr>
    </table>
</div>';

// Bill Summary
$html .= '
<h4 style="color: #2c3e50; text-align: center; margin-top: 20px;">Bill Summary</h4>
<table class="milk-table">
    <thead>
        <tr class="summary-header">
            <th width="30%">Description</th>
            <th width="20%">Quantity</th>
            <th width="20%">Rate (Rs/L)</th>
            <th width="30%">Amount (Rs)</th>
        </tr>
    </thead>
    <tbody>
        <tr class="cow-cell">
            <td>Cow Milk</td>
            <td>' . number_format($totalCow, 1) . ' L</td>
            <td>Rs.' . $cowRate . '.00</td>
            <td>Rs.' . number_format($cowAmount, 2) . '</td>
        </tr>
        <tr class="buffalo-cell">
            <td>Buffalo Milk</td>
            <td>' . number_format($totalBuffalo, 1) . ' L</td>
            <td>Rs.' . $buffaloRate . '.00</td>
            <td>Rs.' . number_format($buffaloAmount, 2) . '</td>
        </tr>
        <tr style="border-top: 2px solid #333;">
            <td colspan="3" class="grand-total"><strong>TOTAL AMOUNT DUE</strong></td>
            <td class="grand-total"><strong>Rs.' . number_format($totalAmount, 2) . '</strong></td>
        </tr>
        <tr>
            <td colspan="3">(-) Previous Payments</td>
            <td>Rs.0.00</td>
        </tr>
        <tr>
            <td colspan="3">(-) Discount</td>
            <td>Rs.0.00</td>
        </tr>
        <tr class="grand-total">
            <td colspan="3"><strong>NET AMOUNT PAYABLE</strong></td>
            <td><strong>Rs.' . number_format($totalAmount, 2) . '</strong></td>
        </tr>
    </tbody>
</table>';

$pdf->writeHTML($html, true, false, false, false, '');

// Additional Info
$pdf->Ln(8);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0, 6, 'Details:', 0, 1);
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 5, '• Total Days: ' . $dayCount, 0, 1);
$pdf->Cell(0, 5, '• Average per Day: ' . number_format($totalLiters / max($dayCount, 1), 1) . ' L', 0, 1);
$pdf->Cell(0, 5, '• Cow Milk %: ' . number_format(($totalCow / max($totalLiters, 1)) * 100, 1) . '%', 0, 1);
$pdf->Cell(0, 5, '• Buffalo Milk %: ' . number_format(($totalBuffalo / max($totalLiters, 1)) * 100, 1) . '%', 0, 1);

// Footer
$pdf->Ln(8);
$pdf->SetLineWidth(0.3);
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
$pdf->Ln(3);
$pdf->SetFont('helvetica', 'I', 9);
$pdf->Cell(0, 5, 'Please arrange to pay the bill amount immediately.', 0, 1, 'C');

$pdf->Ln(5);
$pdf->SetFont('helvetica', '', 8);
$pdf->Cell(95, 5, 'Customer Signature: ________________', 0, 0);
$pdf->Cell(95, 5, 'Shop Signature: ________________', 0, 1);

// Output
$pdf->Output('rajnandini_dairy_bill_' . date('Y-m-d') . '.pdf', 'I');
?>
