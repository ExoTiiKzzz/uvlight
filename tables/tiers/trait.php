<?php 
    require '../../lib/includes/defines.inc.php';
    if(isset($_POST["create"])){
        echo json_encode($oTiers->db_create(
                $_POST["raison_sociale"],
            $_POST["type_societe"],
            $_POST["telephone"],
            $_POST["email"],
            $_POST["adresse"],
            $_POST["ville"],
            $_POST["type_tiers"],
            $_POST["type_reglement"],
            $_POST["numero_compte"],
            $_POST["iban"],
            $_POST["bic"],
            $_POST["code_banque"],
            $_POST["code_guichet"],
            $_POST["cle_rib"],
            $_POST["code_tarif"],
            $_POST["domiciliation"]
        ));

    }elseif(isset($_POST["update"])){
        echo json_encode($oTiers->db_update(
                $_POST["id"],
            $_POST["raison_sociale"],
            $_POST["type_societe"],
            $_POST["telephone"],
            $_POST["email"],
            $_POST["adresse"],
            $_POST["ville"],
            $_POST["type_tiers"],
            $_POST["type_reglement"],
            $_POST["numero_compte"],
            $_POST["iban"],
            $_POST["bic"],
            $_POST["code_banque"],
            $_POST["code_guichet"],
            $_POST["cle_rib"],
            $_POST["code_tarif"],
            $_POST["domiciliation"]
        ));
    }elseif(isset($_POST["multi_delete"])){
        $array = $_POST["array"];
        $finalarray = json_decode($array, true);
        $ids = [];
        foreach ($finalarray as $key => $value) {
            $id = (int) $value;
            array_push($ids, $id);
        }
    
        $res = $oTiers->db_soft_delete_multi($ids);
        if($res){
            $response = "ok";
        }else{
            $response = "pb";
        }
        echo json_encode($response);
    }elseif(isset($_POST["getData"])){
        echo json_encode($oTiers->db_get_by_id($_POST["id"]));
    }

?>