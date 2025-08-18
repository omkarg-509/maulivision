
<?php
$tcpdfPath = __DIR__ . '/../../../lib/tcpdf/tcpdf';
if (!file_exists($tcpdfPath)) {
    print_r($tcpdfPath);
    die('TCPDF library file does not exist.');
}
require_once $tcpdfPath;

// Create new PDF document
$pdf = new TCPDF();
$pdf->AddPage();

// Title
$pdf->SetFont('freeserif', 'B', 16);
$pdf->Cell(0, 10, 'राजनंदिनी डेअरी', 0, 1, 'C');

$pdf->SetFont('freeserif', '', 10);
$pdf->Cell(0, 8, 'म्हसोबा चौक, गायवाडी नळ, ... संपर्क: 9822882755', 0, 1, 'C');

// Customer Info
$pdf->Ln(2);
$pdf->SetFont('freeserif', '', 12);
$pdf->Cell(95, 7, 'श्री. विलास वांकुद्रे', 1, 0);
$pdf->Cell(95, 7, 'गाव: 110125', 1, 1);
$pdf->Cell(95, 7, 'बिल नं: 1200', 1, 0);
$pdf->Cell(95, 7, 'तारीख: 11/01/25', 1, 1);

// Table Header
$pdf->Ln(3);
$tbl = <<<EOD
<table border="1" cellpadding="3">
<thead>
<tr>
    <th>दि.</th>
    <th>लिटर</th>
    <th>फॅट</th>
    <th>SNF</th>
    <th>दर/लिटर</th>
    <th>रक्कम</th>
</tr>
</thead>
<tbody>
<tr><td>1</td><td>1</td><td>8.6</td><td>9</td><td>66.1</td><td>66</td></tr>
<tr><td>2</td><td>1</td><td>9.1</td><td>9</td><td>66.1</td><td>66</td></tr>
<tr><td>3</td><td>1</td><td>8.9</td><td>9</td><td>66.1</td><td>66</td></tr>
<!-- Add more rows -->
<tr><td colspan="5" align="right">एकूण</td><td>1913</td></tr>
<tr><td colspan="5" align="right">पैसे जमा</td><td>0</td></tr>
<tr><td colspan="5" align="right">येणे बाकी</td><td>1914</td></tr>
</tbody>
</table>
EOD;

$pdf->writeHTML($tbl, true, false, false, false, '');

// Output PDF
$pdf->Output('dairy_bill.pdf', 'I');
