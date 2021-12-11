<?php 

require '../../lib/includes/defines.inc.php';


if(isset($_POST["create"])){
    $res = $oSousCategorie->db_create($_POST["lib"],$_POST["categorie"]);
    if($res != false){
        $response["error"] = false;
    }else{
        $response["error"] = true;
        $response["errortext"] = $res;
    }
    echo json_encode($response);
}elseif(isset($_POST["getData"])){
    $res = $oSousCategorie->db_get_by_id($_POST["id"]);
    if($res != false){
        $response["error"] = false;
        $response["content"]["lib"] = $res["scat_lib"];
        $response["content"]["categorie"] = $res["cat_nom"];
    }else{
        $response["error"] = true;
        $response["errortext"] = $res;
    }
    echo json_encode($response);
}elseif(isset($_POST["update"])){
    $res = $oSousCategorie->db_update($_POST["id"], $_POST["lib"], $_POST["categorie"]);
    if($res != false){
        $response["error"] = false;
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

    $res = $oSousCategorie->db_soft_delete_one($id);
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

    $res = $oSousCategorie->db_soft_delete_multi($ids);
    if($res){
        $response = "ok";
    }else{
        $response = "pb";
    }
    echo json_encode($response);
}else{
    // header("location: index.php");
    echo "coucou";
}


?>