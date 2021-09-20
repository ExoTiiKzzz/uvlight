<?php 

require '../../lib/includes/defines.inc.php';


if(isset($_POST["create"])){
    $req = $oCasier->db_create($_POST["casier_name"]);
    if($req){
        ?>
            <script>
                alert("Cela a fonctionné")
                window.location.replace("http://localhost/uvlight/atelier1/casier/index.php?nav=read");
            </script>
        <?php
    }
}elseif(isset($_POST["update"])){
    $req = $oCasier->db_update_lib($_POST["casier_id"], $_POST["casier_name"]);
    if($req){
        ?>
            <script>
                alert("Cela a fonctionné")
                window.location.replace("http://localhost/uvlight/atelier1/casier/index.php?nav=read");
            </script>
        <?php
    }
}elseif(isset($_POST["delete"])){
    $req = $oCasier->db_soft_delete_one($_POST["casier_id"]);
    if($req){
        ?>
            <script>
                alert("Cela a fonctionné")
                window.location.replace("http://localhost/uvlight/atelier1/casier/index.php?nav=read");
            </script>
        <?php
    }
}


?>