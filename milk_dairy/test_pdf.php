<?php
// Test PDF generation with Marathi support

// Start session if needed
session_start();

// Load TCPDF library
require_once __DIR__ . '/app/lib/tcpdf/tcpdf.php';

// Test data
$customerData = [
    'name' => 'ओमकार गायकवाड',
    'bill_id' => 'TEST001',
    'address' => 'गायवाडी नाळ'
];

$milkEntries = [
    ['date' => '2025-08-01', 'milktype' => 'cow', 'liter' => 2.0],
    ['date' => '2025-08-01', 'milktype' => 'buffalo', 'liter' => 1.5],
    ['date' => '2025-08-02', 'milktype' => 'cow', 'liter' => 2.0],
    ['date' => '2025-08-02', 'milktype' => 'buffalo', 'liter' => 1.5],
];

$cowRate = 50;
$buffaloRate = 60;

// Initialize PDF
try {
    $pdf = new TCPDF();
    $pdf->SetCreator('Rajnandini Dairy System');
    $pdf->SetAuthor('Rajnandini Dairy');
    $pdf->SetTitle('दूध बिल - ' . $customerData['name']);
    
    $pdf->SetMargins(10, 10, 10);
    $pdf->SetAutoPageBreak(true, 15);
    $pdf->AddPage();

    // Header with Unicode support
    $pdf->SetFont('dejavusans', 'B', 20);
    $pdf->Cell(0, 12, '🥛 राजनंदिनी डेयरी', 0, 1, 'C');
    $pdf->SetFont('dejavusans', 'B', 16);
    $pdf->Cell(0, 8, 'RAJNANDINI DAIRY', 0, 1, 'C');
    $pdf->SetFont('dejavusans', '', 10);
    $pdf->Cell(0, 6, 'म्हसोबा चौक, गायवाडी नाळ | Mhasoba Chowk, Gaywadi Nal', 0, 1, 'C');
    $pdf->Cell(0, 6, '📞 Phone: 9822882755', 0, 1, 'C');

    // Customer Info
    $pdf->Ln(3);
    $pdf->SetFont('dejavusans', '', 12);
    $pdf->Cell(95, 8, '👤 ग्राहक/Customer: ' . $customerData['name'], 1, 0);
    $pdf->Cell(95, 8, '🏠 पत्ता/Address: ' . $customerData['address'], 1, 1);
    $pdf->Cell(95, 8, '📋 बिल क्रमांक/Bill No: ' . $customerData['bill_id'], 1, 0);
    $pdf->Cell(95, 8, '📅 दिनांक/Date: ' . date('d/m/Y'), 1, 1);

    // Simple test table
    $html = '
    <style>
    body { font-family: dejavusans; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #000; padding: 8px; text-align: center; }
    th { background-color: #4CAF50; color: white; font-weight: bold; }
    </style>
    
    <h3 style="text-align:center; color: #2c3e50;">
    📊 दैनिक दूध विवरण / Daily Milk Details
    </h3>
    
    <table>
        <thead>
            <tr>
                <th>दिनांक / Date</th>
                <th>🐄 गाय दूध / Cow (L)</th>
                <th>🐃 म्हैस दूध / Buffalo (L)</th>
                <th>रक्कम / Amount</th>
            </tr>
        </thead>
        <tbody>';

    // Add test data
    foreach ($milkEntries as $entry) {
        $amount = ($entry['milktype'] === 'cow') ? $entry['liter'] * $cowRate : $entry['liter'] * $buffaloRate;
        $html .= '<tr>
            <td>' . date('d/m/Y', strtotime($entry['date'])) . '</td>
            <td>' . ($entry['milktype'] === 'cow' ? $entry['liter'] : '-') . '</td>
            <td>' . ($entry['milktype'] === 'buffalo' ? $entry['liter'] : '-') . '</td>
            <td>₹' . number_format($amount, 2) . '</td>
        </tr>';
    }

    $html .= '
        </tbody>
    </table>';

    $pdf->writeHTML($html, true, false, false, false, '');

    // Footer
    $pdf->Ln(10);
    $pdf->SetFont('dejavusans', 'I', 9);
    $pdf->Cell(0, 5, 'धन्यवाद! / Thank you!', 0, 1, 'C');

    // Output PDF
    $pdf->Output('test_marathi_pdf.pdf', 'I');

} catch (Exception $e) {
    echo "PDF Generation Error: " . $e->getMessage();
    echo "<br>Error Details: ";
    var_dump($e);
}
?>
