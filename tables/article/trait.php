<?php 

require '../../lib/includes/defines.inc.php';


if(isset($_POST["create"])){
    if(empty($_POST["article_name"]) || empty($_POST["categorie"]) || empty($_POST["casier"])){
        ?>
            <script>
                window.location.replace("index.php");
            </script>
        <?php
    }
    $req = $oArticle->db_create($_POST["article_name"], $_POST["article_commentaire"], $_POST["categorie"], $_POST["casier"]);
    if($req){
        ?>
            <script>
                window.location.replace("index.php");
            </script>
        <?php
    }else{
        var_dump($req);
    }
}elseif(isset($_POST["update"])){
    $req = $oArticle->db_update($_POST["article_id"], $_POST["article_name"], $_POST["article_commentaire"], $_POST["categorie"], $_POST["casier"]);
    if($req){
        ?>
            <script>
                window.location.replace("index.php");
            </script>
        <?php
    }
}elseif(isset($_POST["delete"])){
    $req = $oArticle->db_soft_delete_one($_POST["article_id"]);
    if($req){
        ?>
            <script>
                window.location.replace("index.php");
            </script>
        <?php
    }
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