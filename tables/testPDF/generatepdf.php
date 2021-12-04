<?php
$opts = array('http' =>
                        array(
                        'method'  => 'POST',
                        'header'  => 'Content-type: application/x-www-form-urlencoded',
                        'content' => http_build_query(array('o' => 'P', 'f' => 'A4', 'c' => file_get_contents('./index.php')))
                        )
);

// $pdf contiendra le contendu du PDF à sauvegarder
$pdf = file_get_contents('https://html2pdf.roulade.fr', true, stream_context_create($opts));

//var_dump($pdf);

file_put_contents('./test.pdf', $pdf);