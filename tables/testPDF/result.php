<?php
require '../../server/vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;

$html2pdf = new Html2Pdf('L', 'A4', 'fr');
$html2pdf->writeHTML(file_get_contents($filename = 'http://localhost/uvlight/tables/testPDF/index.php'));
$html2pdf->output();

