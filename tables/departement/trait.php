<?php 

require '../../lib/includes/defines.inc.php';


if(isset($_POST["create"])){
    $req = $oDepartement->db_create( $_POST["departement_code"] ,$_POST["departement_name"], $_POST["region_id"]);
    if($req){
        echo $_POST["departement_code"] ,$_POST["departement_name"], $_POST["region_id"];
        ?>
            <script>
                alert("Cela a fonctionné")
                window.location.replace("./");
            </script>
        <?php
    }
}elseif(isset($_POST["update"])){
    $req = $oDepartement->db_update_one($_POST["departement_id"], $_POST["departement_name"]);
    if($req){
        ?>
            <script>
                alert("Cela a fonctionné")
                window.location.replace("./");
            </script>
        <?php
    }
}elseif(isset($_POST["delete"])){
    $req = $oDepartement->db_soft_delete_one($_POST["departement_id"]);
    if($req){
        ?>
            <script>
                alert("Cela a fonctionné")
                window.location.replace("./");
            </script>
        <?php
    }
}


?>