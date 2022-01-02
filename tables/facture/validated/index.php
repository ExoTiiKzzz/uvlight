<?php
    if(isset($_GET["id"])){
        require "../../../lib/includes/defines.inc.php";
        $lignes_facture = $oFacture->db_get_lignes_facture($_GET["id"]);
        echo "<pre>";
        var_dump($lignes_facture);
        echo "</pre>";

    }else{
        header("Location: ../");
    }
