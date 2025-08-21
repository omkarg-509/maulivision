<?php
// Simple direct PDF generation test
// Access via: http://localhost/maulivision/milk_dairy/public/pdf_direct_test.php

// Start output buffering
if (ob_get_level() == 0) ob_start();

try {
    // Load TCPDF library
    require_once '../app/lib/tcpdf/tcpdf.php';

    // Create PDF instance
    $pdf = new TCPDF();
    $pdf->SetCreator('Rajnandini Dairy');
    $pdf->SetAuthor('Rajnandini Dairy');
    $pdf->SetTitle('दूध बिल टेस्ट');
    
    // Set margins and page
    $pdf->SetMargins(10, 10, 10);
    $pdf->SetAutoPageBreak(true, 15);
    $pdf->AddPage();

    // Test Marathi fonts
    $pdf->SetFont('dejavusans', 'B', 24);
    $pdf->Cell(0, 15, '🥛 राजनंदिनी डेयरी', 0, 1, 'C');
    
    $pdf->SetFont('dejavusans', 'B', 18);
    $pdf->Cell(0, 10, 'RAJNANDINI DAIRY', 0, 1, 'C');
    
    $pdf->SetFont('dejavusans', '', 12);
    $pdf->Ln(5);
    $pdf->Cell(0, 8, 'PDF जेनरेशन टेस्ट / PDF Generation Test', 0, 1, 'C');
    $pdf->Cell(0, 8, 'मराठी सपोर्ट कार्य करत आहे / Marathi Support Working', 0, 1, 'C');
    
    $pdf->Ln(10);
    
    // Test table with Marathi
    $html = '
    <style>
    body { font-family: dejavusans; }
    table { width: 100%; border-collapse: collapse; margin: 10px 0; }
    th, td { border: 1px solid #000; padding: 8px; text-align: center; }
    th { background-color: #4CAF50; color: white; font-weight: bold; }
    .test-pass { background-color: #d4edda; color: #155724; }
    </style>
    
    <h3 style="text-align:center; color: #2c3e50;">
    🧪 टेस्ट रिपोर्ट / Test Report
    </h3>
    
    <table>
        <thead>
            <tr>
                <th>टेस्ट / Test</th>
                <th>स्थिती / Status</th>
                <th>परिणाम / Result</th>
            </tr>
        </thead>
        <tbody>
            <tr class="test-pass">
                <td>TCPDF लायब्ररी / TCPDF Library</td>
                <td>✅ कार्यरत / Working</td>
                <td>यशस्वी / Success</td>
            </tr>
            <tr class="test-pass">
                <td>मराठी फॉन्ट / Marathi Fonts</td>
                <td>✅ सपोर्ट / Supported</td>
                <td>यशस्वी / Success</td>
            </tr>
            <tr class="test-pass">
                <td>PDF आउटपुट / PDF Output</td>
                <td>✅ तयार / Generated</td>
                <td>यशस्वी / Success</td>
            </tr>
        </tbody>
    </table>
    
    <p style="text-align:center; margin-top: 20px; color: #28a745;">
    <strong>🎉 सर्व टेस्ट पास झाले! / All Tests Passed!</strong>
    </p>';

    $pdf->writeHTML($html, true, false, false, false, '');

    // Footer
    $pdf->Ln(10);
    $pdf->SetFont('dejavusans', 'I', 10);
    $pdf->Cell(0, 6, 'Generated on: ' . date('d/m/Y H:i:s'), 0, 1, 'C');
    $pdf->Cell(0, 6, 'तयार केले: ' . date('d/m/Y H:i:s'), 0, 1, 'C');

    // Clean buffer and output
    if (ob_get_contents()) ob_end_clean();
    
    // Output the PDF
    $pdf->Output('marathi_test.pdf', 'I');
    
} catch (Exception $e) {
    // Clean buffer on error
    if (ob_get_contents()) ob_end_clean();
    
    header('Content-Type: text/html; charset=utf-8');
    echo "<h2>PDF Error / PDF त्रुटी</h2>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>File:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
    echo "<h3>Debug Info:</h3>";
    echo "<pre>";
    print_r($e->getTrace());
    echo "</pre>";
}
?>
