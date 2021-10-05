<?php 

require '../../lib/includes/defines.inc.php';


if(isset($_POST["create"])){
    if(empty($_POST["categorie_name"]) || empty($_POST["categorie_description"])){
        ?>
            <script>
                window.location.replace("index.php");
            </script>
        <?php
    }
    $req = $oCategorie->db_create($_POST["categorie_name"], $_POST["categorie_description"]);
    echo $_POST["categorie_name"], $_POST["categorie_description"];
    if($req){
        var_dump($req);
        ?>
            <script>
                window.location.replace("index.php");
            </script>
        <?php
    }
}elseif(isset($_POST["update"])){
    $req = $oCategorie->db_update($_POST["categorie_id"], $_POST["categorie_name"], $_POST["categorie_description"]);
    if($req){
        ?>
            <script>
                window.location.replace("index.php");
            </script>
        <?php
    }
}elseif(isset($_POST["delete"])){
    $req = $oCategorie->db_soft_delete_one($_POST["categorie_id"]);
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

    $res = $oCategorie->db_soft_delete_multi($ids);
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