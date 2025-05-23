<?php 

require '../../lib/includes/defines.inc.php';

if(isset($_POST["create"])){
    if(isset($_POST["articles"]))  echo json_encode($oArticle->db_create($_POST["lib"], $_POST["comment"], $_POST["fournisseur"], $_POST["cat"], $_POST["cas"], $_POST["articles"], $_POST["quantitys"]));
    else echo json_encode($oArticle->db_create($_POST["lib"], $_POST["comment"], $_POST["fournisseur"], $_POST["cat"], $_POST["cas"]));
}elseif(isset($_POST["update"])){
    if(isset($_POST["articles"]))  echo json_encode($oArticle->db_update($_POST["id"],$_POST["lib"], $_POST["comment"], $_POST["fournisseur"], $_POST["cat"], $_POST["cas"], $_POST["articles"], $_POST["quantitys"]));
    else echo json_encode($oArticle->db_update($_POST["id"],$_POST["lib"], $_POST["comment"], $_POST["fournisseur"], $_POST["cat"], $_POST["cas"]));
}elseif(isset($_POST["getData"])){
    $res = $oArticle->db_get_by_id($_POST["id"]);
    if($res != false){
        $response["error"] = false;
        $response["content"]["lib"] = $res["content"]["art_nom"];
        $response["content"]["comment"] = $res["content"]["art_commentaire"];
        $response["content"]["cas"] = $res["content"]["cas_lib"];
        $response["content"]["cat"] = $res["content"]["cat_nom"];
        $response["content"]["tarifs"] = $res["content"]["tarifs"];
        $response["content"]["fourni"] = $res["content"]["tie_raison_sociale"];
        $response["content"]["isComposed"] = $res["content"]["art_is_composed"];
        if($res["content"]["art_is_composed"] === 1){
            $response["content"]["articles"] = $res["content"]["articles"];
        }
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
}elseif(isset($_POST["command"])){
    echo json_encode($oCommande->db_command($_POST["comment"], json_decode($_POST["quantity"]), json_decode($_POST["article"]), $_POST["tiers"], 0));
}elseif(isset($_POST["sale"])){
    echo json_encode($oCommande->db_command($_POST["comment"], json_decode($_POST["quantity"]), json_decode($_POST["article"]), $_POST["tiers"], 1));
}elseif(isset($_POST["getFourniArticles"])){
    echo json_encode($oArticle->db_get_all_by_fournisseur($_POST["fournisseur"]));
}elseif(isset($_POST["updateTarif"])){
    echo json_encode($oTarif->db_update_grid($_POST["tar_ID"], $_POST["art_ID"], $_POST["prix"]));
}
else{
    header("location: index.php");
}


?>