<?php 

require '../../lib/includes/defines.inc.php';


if(isset($_POST["create"])){
    $res = $oCasier->db_create($_POST["lib"]);
    if($res != false){
        $response["error"] = false;
        $response["existingid"] = $oCasier->db_get_one()["cas_ID"];
        $response["createdid"] = $res["lastid"];
    }else{
        $response["error"] = true;
        $response["errortext"] = $res;
    }
    echo json_encode($response);
}elseif(isset($_POST["update"])){
    $res = $oCasier->db_update($_POST["id"], $_POST["lib"]);
    if($res === true){
        $response["error"] = false;
        $response["existingid"] = $oCasier->db_get_one()["cas_ID"];
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

    $res = $oCasier->db_soft_delete_one($id);
    if($res){
        $response["error"] = false;
    }else{
        $response["error"] = true;
        $response["errortext"] = "Une erreur s'est produite, veuillez reessayer";
    }
    echo json_encode($response);
}elseif(isset($_POST["getData"])){
    $res = $oCasier->db_get_by_id($_POST["id"]);
    if($res != false){
        $response["error"] = false;
        $response["content"]["lib"] = $res["cas_lib"];
    }else{
        $response["error"] = true;
        $response["errortext"] = $res;
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

    $res = $oCasier->db_soft_delete_multi($ids);
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