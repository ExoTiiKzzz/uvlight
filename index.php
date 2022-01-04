<?php
    require './lib/includes/defines.inc.php';
    $oLogin->validate_SESSION();
    $user_details = $oUser->db_get_by_id($_SESSION['session_user_ID']);
    unset($_SESSION['session_user_ID']);
    require './lib/includes/doctype.php';
    require './lib/includes/navbar.php';
    require './lib/includes/sidenav.php';


    echo doctype("Accueil", $path);
    echo navbar($path);
    echo sidenav($path);
?>


<body>
    <div class="main-container sidenav-open">
        <div class="mt-5 landing-container">
            <h1 class="mt-5 text-center welcome">Bienvenue <?php echo $user_details["use_firstname"]." ".$user_details["use_lastname"] ?></h1>
            <div class="buttons-container">
                <a class="btn btn-primary mt-5" href="./atelier1/tiers/index.php">Voir les tiers</a>
                <a class="btn btn-primary mt-5" href="./atelier1/casier/index.php">Voir les casiers </a>
            </div>
        </div>
    </div>
    <style>
        /*.landing-container{
            display: flex;
            flex-direction: column;
            align-items: center;

            background: #3B3B3B;
            color: #FFFFFF;
        }

        .welcome{

        }*/
    </style>
</body>
<script src="./script/js/indexcheckjwt.js"></script>
<script src="./script/js/index.js"></script>
<script src="./script/js/sidenav.js"></script>

</html>