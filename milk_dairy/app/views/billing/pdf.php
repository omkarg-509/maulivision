<?php
require_once __DIR__ . '/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf([
    'default_font' => 'NotoSerifDevanagari' // देवनागरी font वापर
]);

$html = '<h1 style="text-align:center;">श्री कृष्णा डेअरी & स्वीट</h1>';
$mpdf->WriteHTML($html);
$mpdf->Output("dairy.pdf", "I"); // "I" म्हणजे browser मध्ये open होईल
