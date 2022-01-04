<?php
require '../../lib/includes/defines.inc.php';
$doc_ID = $_GET["id"];
$com_ID = $oCommande->db_get_by_doc_ID($doc_ID);

if($com_ID["error"] === true){
    echo $com_ID["errortext"];
}else{
    $com_ID = $com_ID["content"];
}

require "../../server/vendor/autoload.php";

$dompdf = new \Dompdf\Dompdf();
$html = file_get_contents("http://localhost/uvlight/tables/facture/template.php?id=$doc_ID");
$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4');

// Render the HTML as PDF
$dompdf->render();

$output = $dompdf->output();

// Output the generated PDF to Browser
$dompdf->stream();

if (!file_exists("factures/$com_ID")) {
    mkdir("factures/$com_ID", 0777, true);
}


file_put_contents("factures/$com_ID/$doc_ID.pdf", $output);
