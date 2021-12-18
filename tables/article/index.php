<?php 
    require '../../lib/includes/defines.inc.php';
    require '../../lib/includes/navbar.php';
    require '../../lib/includes/sidenav.php';
    require '../../lib/includes/doctype.php';
    require '../../lib/includes/tablefooter.php';
    
    echo doctype("Article", $path);
    echo navbar($path);
    echo sidenav($path);
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    <?php 
        require '../static/css/table.css';
        require '../../assets/css/main.css';
        require '../../assets/css/navbar.css';
        require '../../assets/css/sidenav.css';
    ?>
    .produitArticleContainer{
        display: none;
    }
    .produitArticleContainer.active{
        display: block;
    }
</style>

<body>
    
<script> 
    const url = "trait.php";
    function updateListeArticle(newlib){
        let option = document.createElement("option");
        option.value = newlib;
        document.getElementById("liste_articles").appendChild(option);
    }
</script>
<?php
        echo $oListe->build_liste("liste_categories", $oCategorie->db_get_all(), "cat_nom");
        echo $oListe->build_liste("liste_casiers", $oCasier->db_get_all(), "cas_lib");
        echo $oListe->build_liste("liste_fournisseurs", $oTiers->db_get_all_fournisseurs(), "tie_raison_sociale");
        echo $oListe->build_liste("all_articles", $oArticle->db_get_all(), "art_nom");
        require './components/main.php';
?>

    <?= $footer ?>
</body>
</html>