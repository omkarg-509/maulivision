<?php
// Test PDF generation - English only

// Start session if needed
session_start();

// Load TCPDF library
require_once __DIR__ . '/app/lib/tcpdf/tcpdf.php';

// Test data
$customerData = [
    'name' => 'Omkar Gaikwad',
    'bill_id' => 'TEST001',
    'address' => 'Gaywadi Nal'
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
    $pdf->SetTitle('Milk Bill - ' . $customerData['name']);
    
    $pdf->SetMargins(10, 10, 10);
    $pdf->SetAutoPageBreak(true, 15);
    $pdf->AddPage();

    // Header - English only
    $pdf->SetFont('helvetica', 'B', 20);
    $pdf->Cell(0, 12, 'RAJNANDINI DAIRY', 0, 1, 'C');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 6, 'Mhasoba Chowk, Gaywadi Nal', 0, 1, 'C');
    $pdf->Cell(0, 6, 'Phone: 9822882755', 0, 1, 'C');

    // Customer Info
    $pdf->Ln(3);
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(95, 8, 'Customer: ' . $customerData['name'], 1, 0);
    $pdf->Cell(95, 8, 'Address: ' . $customerData['address'], 1, 1);
    $pdf->Cell(95, 8, 'Bill No: ' . $customerData['bill_id'], 1, 0);
    $pdf->Cell(95, 8, 'Date: ' . date('d/m/Y'), 1, 1);

    // Simple test table
    $html = '
    <style>
    body { font-family: helvetica; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #000; padding: 8px; text-align: center; }
    th { background-color: #4CAF50; color: white; font-weight: bold; }
    </style>
    
    <h3 style="text-align:center; color: #2c3e50;">
    Daily Milk Details
    </h3>
    
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Cow Milk (L)</th>
                <th>Buffalo Milk (L)</th>
                <th>Amount</th>
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
            <td>Rs.' . number_format($amount, 2) . '</td>
        </tr>';
    }

    $html .= '
        </tbody>
    </table>';

    $pdf->writeHTML($html, true, false, false, false, '');

    // Footer
    $pdf->Ln(10);
    $pdf->SetFont('helvetica', 'I', 9);
    $pdf->Cell(0, 5, 'Thank you!', 0, 1, 'C');

    // Output PDF
    $pdf->Output('test_english_pdf.pdf', 'I');

} catch (Exception $e) {
    echo "PDF Generation Error: " . $e->getMessage();
    echo "<br>Error Details: ";
    var_dump($e);
}
?>
