<?php 

require '../../lib/includes/defines.inc.php';


if(isset($_POST["create"])){
    $req = $oRegion->db_create( $_POST["region_code"] ,$_POST["region_name"]);
    if($req){
        ?>
            <script>
                alert("Cela a fonctionné")
                window.location.replace("http://localhost/uvlight/atelier1/region/index.php?nav=read");
            </script>
        <?php
    }
}elseif(isset($_POST["update"])){
    $req = $oRegion->db_update_one($_POST["region_id"], $_POST["region_name"]);
    if($req){
        ?>
            <script>
                alert("Cela a fonctionné")
                window.location.replace("http://localhost/uvlight/atelier1/region/index.php?nav=read");
            </script>
        <?php
    }
}elseif(isset($_POST["delete"])){
    $req = $oRegion->db_soft_delete_one($_POST["region_id"]);
    if($req){
        ?>
            <script>
                alert("Cela a fonctionné")
                window.location.replace("http://localhost/uvlight/atelier1/region/index.php?nav=read");
            </script>
        <?php
    }
}


?>