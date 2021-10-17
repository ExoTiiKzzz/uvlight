<?php 

require '../../lib/includes/defines.inc.php';


if(isset($_POST["create"])){
    $articlesarray = explode(",", $_POST["articles"]);
    $quantitesarray = explode(",", $_POST["quantites"]);

    $res = $oProduit->db_create($_POST["lib"], $_POST["comment"], $_POST["cas"], $articlesarray, $quantitesarray);
    if($res != false){
        $response["error"] = false;
        $response["existingid"] = $oProduit->db_get_one();
        $response["createdid"] = $res;
    }else{
        $response["error"] = true;
        $response["errortext"] = $res;
    }
    echo json_encode($response);
    
}elseif(isset($_POST["update"])){
    $id = (int) $_POST["id"];

    if(!$id){
        $response["error"] = true;
        $response["errortext"] = "Veuillez saisir une valeure correcte";
        echo json_encode($response);
        die;
    }

    $res = $oProduit->db_update($_POST["id"], $_POST["lib"], $_POST["comment"]);
    if($res === true){
        $response["error"] = false;
    }else{
        $response["error"] = true;
        $response["errortext"] = "Une erreur s'est produite, veuillez reessayer";
    }
    echo json_encode($response);

}elseif(isset($_POST["delete"])){
    $id = (int) $_POST["id"];

    if(!$id){
        $response["error"] = true;
        $response["errortext"] = "Veuillez saisir une valeure correcte";
        echo json_encode($response);
        die;
    }

    $res = $oProduit->db_soft_delete_one($id);
    if($res){
        $response["error"] = false;
    }else{
        $response["error"] = true;
        $response["errortext"] = "Une erreur s'est produite, veuillez reessayer";
    }
    echo json_encode($response);
}elseif(isset($_POST["multi_delete"])){
    $array = $_POST["array"];
    $finalarray = json_decode($array, true);
    $ids = [];
    foreach ($finalarray as $key => $value) {
        $id = (int) $value;
        array_push($ids, $id);
    }

    $res = $oProduit->db_soft_delete_multi($ids);
    if($res){
        $response = "ok";
    }else{
        $response = "pb";
    }
    echo json_encode($response);
}elseif(isset($_POST["getarticles"])){

    $id = (int) $_POST["index"];

    if(!$id){
        $response["error"] = true;
        $response["errortext"] = "Veuillez saisir une valeure correcte";
        echo json_encode($response);
        die;
    }

    $res = $oArticle->db_get_article_by_produit_id($id);
    if($res){
        $response["error"] = false;
        $response["content"] = $res;
    }else{
        $response["error"] = true;
        $response["errortext"] = "article";
    }
    $response["produit"] = $oProduit->db_get_by_id($id);
    echo json_encode($response);
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