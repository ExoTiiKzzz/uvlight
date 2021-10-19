<?php 

require '../../lib/includes/defines.inc.php';


if(isset($_POST["create"])){
    $res = $oArticle->db_create($_POST["lib"], $_POST["comment"], $_POST["cat"], $_POST["cas"]);
    if($res != false){
        $response["error"] = false;
        $response["existingid"] = $oArticle->db_get_one();
        $response["createdid"] = $res["lastid"];
        $response["cat"] = $res["cas"];
        $response["cas"] = $res["cat"];
    }else{
        $response["error"] = true;
        $response["errortext"] = $res;
    }
    echo json_encode($response);
}elseif(isset($_POST["update"])){
    $res = $oArticle->db_update($_POST["id"], $_POST["lib"], $_POST["comment"], $_POST["cat"], $_POST["cas"]);
    if($res != false){
        $response["error"] = false;
        $response["existingid"] = $oArticle->db_get_one();
    }else{
        $response["error"] = true;
        $response["errortext"] = $res;
    }
    echo json_encode($response);
}elseif(isset($_POST["getData"])){
    $res = $oArticle->db_get_by_id($_POST["id"]);
    if($res != false){
        $response["error"] = false;
        $response["content"]["lib"] = $res["art_nom"];
        $response["content"]["comment"] = $res["art_commentaire"];
        $response["content"]["cas"] = $res["cas_lib"];
        $response["content"]["cat"] = $res["cat_nom"];
    }else{
        $response["error"] = true;
        $response["errortext"] = $res;
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

    $res = $oArticle->db_soft_delete_one($id);
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

    $res = $oArticle->db_soft_delete_multi($ids);
    if($res){
        $response = "ok";
    }else{
        $response = "pb";
    }
    echo json_encode($response);
}else{
    header("location: index.php");
}


?>