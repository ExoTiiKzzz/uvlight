<?php 

require '../../lib/includes/defines.inc.php';


if(isset($_POST["create"])){
    if(empty($_POST["casier_name"])){
        ?>
            <script>
                window.location.replace("http://localhost/uvlight/atelier1/casier/index.php");
            </script>
        <?php
    }
    $req = $oCasier->db_create($_POST["casier_name"]);
    if($req){
        ?>
            <script>
                window.location.replace("http://localhost/uvlight/atelier1/casier/index.php");
            </script>
        <?php
    }
}elseif(isset($_POST["update"])){
    $req = $oCasier->db_update_lib($_POST["casier_id"], $_POST["casier_name"]);
    if($req){
        ?>
            <script>
                window.location.replace("http://localhost/uvlight/atelier1/casier/index.php");
            </script>
        <?php
    }
}elseif(isset($_POST["delete"])){
    $req = $oCasier->db_soft_delete_one($_POST["casier_id"]);
    if($req){
        ?>
            <script>
                window.location.replace("http://localhost/uvlight/atelier1/casier/index.php?nav=read");
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

    $res = $oCasier->db_soft_delete_multi($ids);
    if($res){
        $response = "ok";
    }else{
        $response = "pb";
    }
    echo json_encode($response);
}


?>