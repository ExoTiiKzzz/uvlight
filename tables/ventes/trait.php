<?php 

require '../../lib/includes/defines.inc.php';


if(isset($_POST["create"])){
    echo json_encode($oTypeDocument->db_create($_POST["lib"]));
    
}elseif(isset($_POST["update"])){
    echo json_encode($oCommande->db_update($_POST["id"], $_POST["etat"]));
}elseif(isset($_POST["delete"])){
    echo json_encode($oTypeDocument->db_soft_delete_one($_POST["id"]));
}elseif(isset($_POST["multi_delete"])){
    

    $res = $oProduit->db_soft_delete_multi($_POST["array"]);
    if($res){
        $response = "ok";
    }else{
        $response = "pb";
    }
    echo json_encode($response);
}elseif(isset($_POST["getdata"])){
    echo json_encode($oCommande->db_get_by_id($_POST["id"]));
}elseif(isset($_POST["getDocuments"])){
    echo json_encode($oCommande->db_get_documents($_POST["id"]));
}elseif(isset($_POST["updateCommand"])){
    echo json_encode($oCommande->db_update_lignes_commande(json_decode($_POST["array"]), $_POST["com_ID"],$_POST["comment"]));
}else{
    header("location: index.php");
}


?>