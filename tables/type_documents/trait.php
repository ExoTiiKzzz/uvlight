<?php 

require '../../lib/includes/defines.inc.php';


if(isset($_POST["create"])){
    echo json_encode($oTypeDocument->db_create($_POST["lib"]));
    
}elseif(isset($_POST["update"])){
    echo json_encode($oTypeDocument->db_update_lib($_POST["id"], $_POST["lib"]));
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
    echo json_encode($oTypeDocument->db_get_by_id($_POST["id"]));
}elseif(isset($_POST["delete_article"])){

    $id = (int) $_POST["index"];
    $pro_id = (int) $_POST["produit_id"];

    if(!$id || !$pro_id){
        $response["error"] = true;
        $response["errortext"] = "Veuillez saisir une valeure correcte";
    }

    $res = $oCompose->db_delete_article_from_produit($pro_id, $id);
    if($res){
        $response["error"] = false;
    }else{
        $response["error"] = true;
        $response["errortext"] = $res;
    }
    echo json_encode($response);
}elseif(isset($_POST["addNewArt"])){
    $pro_id = (int) $_POST["produit_id"];

    if(!$pro_id){
        $response["error"] = true;
        $response["errortext"] = "Veuillez saisir une valeure correcte";
    }

    $res = $oCompose->db_soft_create_one($pro_id);
    if($res){
        $response["error"] = false;
    }else{
        $response["error"] = true;
        $response["errortext"] = $res;
    }
    echo json_encode($response);
}elseif(isset($_POST["get_produit"])){
    $pro_id = (int) $_POST["produit_id"];

    if(!$pro_id){
        $response["error"] = true;
        $response["errortext"] = "Veuillez saisir une valeure correcte";
    }

    $res = $oProduit->db_get_by_id($pro_id);
    if($res){
        $response["error"] = false;
        $response["content"] = $res;
    }else{
        $response["error"] = true;
        $response["errortext"] = $res;
    }
    echo json_encode($response);
}elseif(isset($_POST["updatearticle"])){

    $res = $oCompose->db_update_compo($_POST["produit_id"], $_POST["old_article_id"], $_POST["new_article_id"], $_POST["quantite"]);
    if($res){
        $response["error"] = false;
        $response["content"] = $res;
    }else{
        $response["error"] = true;
        $response["errortext"] = $res;
    }
    echo json_encode($response);
}else{
    header("location: index.php");
}


?>