<?php
if(!isset($_GET["id"]) || empty($_GET["id"])) header('Location: ../../');
require '../../lib/includes/defines.inc.php';
require '../../lib/includes/navbar.php';
require '../../lib/includes/sidenav.php';
require '../../lib/includes/doctype.php';

echo doctype("Générer la facture ".$_GET['id'], $path);
echo navbar($path);
echo sidenav($path);

$lignes_recues = $oCommande->db_get_all_received_articles($_GET["id"]);


?>
<body>
<style>

</style>
<?php


?>


<div class="main-container sidenav-open mb-5">
    <h1 class="text-center mb-4 pb-4">Choisissez les articles à facturer</h1>

    <div class="row form-group pb-4">
        <div class="col-4">
            <h3>Référence article</h3>
        </div>
        <div class="col-4">
            <h3>Nom article</h3>
        </div>
        <div class="col-4">
            <h3>Quantité</h3>
        </div>
    </div>
    <form action="./trait.php" method="post">
        <?php
        foreach ($lignes_recues["content"] as $ligne){ ?>
            <div class="row form-group">
                <div class="col-4">
                    <div class="form-control"><?= $ligne["art_ID"] ?></div>
                    <input type="hidden" value="<?= $ligne["art_ID"] ?>" name="article[]">
                </div>
                <div class="col-4">
                    <div class="form-control"><?= $ligne["art_nom"] ?></div>
                </div>
                <div class="col-4">
                    <input required type="number" min="0" max="<?= $ligne['total'] ?>" class="form-control updateCommandQuant" value="<?= $ligne['total'] ?>" name="quantite[]">
                </div>
            </div>
            <?php
        }
        ?>
        <div class="float-right pr-4">
            <button class="btn btn-primary" type="submit" name="prevalidation" value="1">Valider</button>
        </div>
    </form>

</div>


<!-- jQuery Library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="./index.js"></script>

<script src="../../script/js/sidenav.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

<script> //initialisation datatable

</script>
</body>
</html>