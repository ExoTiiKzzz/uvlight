<?php
    require './lib/includes/defines.inc.php';
    if(!isset($_COOKIE["user_jwt"])){
        header("location: login.php");
    }else{
        $check = $oLogin->validate_token();
        if(!$check){
            header("location: login.php");
        }else{
            $user_id = $oLogin->get_user_id_jwt();
            if(!$user_id){
                header("location: login.php");
            }else{
                $user_details = $oUser->db_get_by_id($user_id);
            }
        }
    }
    require './lib/includes/doctype.php';
    require './lib/includes/navbar.php';


    echo doctype("Accueil");
    echo navbar();
?>

<style>
    <?php 
    require './assets/css/navbar.css';
    require './assets/css/style.css';
    ?>
</style>


<body>
    <div class="mt-5 ">
        <h1 class="mt-5 text-center">Bienvenue <?php echo $user_details["use_firstname"]." ".$user_details["use_lastname"] ?></h1>
        <div class="buttons-container">
            <a class="btn btn-primary mt-5" href="./atelier1/tiers/index.php">Voir les tiers</a>
            <a class="btn btn-primary mt-5" href="#">Voir les produits <br> (Pas encore dispo)</a>
        </div>

    </div>
</body>
<script src="./script/js/indexcheckjwt.js"></script>
<script src="./script/js/index.js"></script>

</html>