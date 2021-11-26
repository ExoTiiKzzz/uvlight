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
<style>
    <?php 
        require '../static/css/table.css';
        require '../../assets/css/main.css';
        require '../../assets/css/navbar.css';
        require '../../assets/css/sidenav.css';
    ?>
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
        echo $oListe->build_liste("liste_articles", $oArticle->db_get_all(), "art_nom");
        echo $oListe->build_liste("liste_categories", $oCategorie->db_get_all(), "cat_nom");
        echo $oListe->build_liste("liste_casiers", $oCasier->db_get_all(), "cas_lib");
        require './components/main.php';
?>

    <?= $footer ?>
</body>
</html>