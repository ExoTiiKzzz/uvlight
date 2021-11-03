<?php 

require '../../lib/includes/defines.inc.php';


if(isset($_POST["create"])){
    $sql = $conn->prepare("SELECT d_id FROM departments WHERE d_name = :dname");
    $sql->bindValue(':dname', $_POST["departement"]);
    $sql->execute();
    $departement_id = $sql->fetch(PDO::FETCH_ASSOC);
    $departement_id = $departement_id["d_id"];
    $req = $oCommunes->db_create($_POST["insee_code"], $_POST["zip_code"], $_POST["city_name"], $departement_id, $_POST["lat"], $_POST["lng"]);
    if($req){
        ?>
            <script>
                alert("Cela a fonctionné")
                window.location.replace("./");
            </script>
        <?php
    }
}elseif(isset($_POST["update"])){
    $req = $oCommunes->db_update_one($_POST["city_id"], $_POST["city_name"], $_POST["insee_code"], $_POST["zip_code"], $_POST["lat"], $_POST["lng"]);
    if($req){
        ?>
            <script>
                alert("Cela a fonctionné")
                window.location.replace("./");
            </script>
        <?php
    }
}elseif(isset($_POST["delete"])){
    $req = $oCommunes->db_soft_delete_one($_POST["city_id"]);
    if($req){
        ?>
            <script>
                alert("Cela a fonctionné")
                window.location.replace("./");
            </script>
        <?php
    }else{
        var_dump($req);
        ?>
            <!-- <script>
                alert("Une erreur s'est produite")
                window.location.replace("./");
            </script> -->
        <?php
    }
}


?>