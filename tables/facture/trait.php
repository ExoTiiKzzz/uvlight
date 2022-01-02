<?php
require '../../lib/includes/defines.inc.php';
if(isset($_POST["prevalidation"])){
        echo json_encode($oFacture->db_insert_lignes_facture(
            $_POST["data"]["article"],
            $_POST["data"]["quantite"],
            $_POST["data"]["codetarif"],
            $_POST["data"]["comment"],
            $_POST["data"]["com_ID"],
            $_POST["data"]["remise"]
        ));
}else{
    $response["error"] = true;
    $response["errortext"] = "Verifier les informations";
    echo json_encode($response);
    header("Location: ./");
}