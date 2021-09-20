<?php 

require '../../lib/includes/defines.inc.php';


if(isset($_POST["create"])){
    $req = $oCommunes->db_create($_POST["insee_code"], $_POST["zip_code"], $_POST["c_name"], $_POST["departement_id"], $_POST["lat"], $_POST["lng"]);
    if($req){
        ?>
            <script>
                alert("Cela a fonctionné")
                window.location.replace("http://localhost/uvlight/atelier1/commune/index.php?nav=read");
            </script>
        <?php
    }
}elseif(isset($_POST["update"])){
    $req = $oDepartement->db_update_one($_POST["departement_id"], $_POST["departement_name"]);
    if($req){
        ?>
            <script>
                alert("Cela a fonctionné")
                window.location.replace("http://localhost/uvlight/atelier1/departement/index.php?nav=read");
            </script>
        <?php
    }
}elseif(isset($_POST["delete"])){
    $req = $oDepartement->db_soft_delete_one($_POST["departement_id"]);
    if($req){
        ?>
            <script>
                alert("Cela a fonctionné")
                window.location.replace("http://localhost/uvlight/atelier1/departement/index.php?nav=read");
            </script>
        <?php
    }
}


?>