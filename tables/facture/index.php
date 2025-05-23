<?php
if(!isset($_GET["id"]) || empty($_GET["id"])) header('Location: ../../');
require '../../lib/includes/defines.inc.php';
$oLogin->validate_SESSION();
require '../../lib/includes/navbar.php';
require '../../lib/includes/sidenav.php';
require '../../lib/includes/doctype.php';

echo doctype("Générer la facture ".$_GET['id'], $path);
echo navbar($path);
echo sidenav($path);

$lignes_recues = $oCommande->db_get_all_articles($_GET["id"]);
$prix = $oTarif->db_get_all_tarifs();
$tiers = $oCommande->db_get_tiers($_GET["id"])["data"];

$max = 0;
?>
<body>
<style>
    .totalTTC{
        font-weight: 800;
    }
</style>



<div class="main-container sidenav-open mb-5">
    <?php

    if($prix["error"] === true){
        echo $prix["errortext"];
    }
    ?>

    <form action="./trait.php" method="post" id="upload-form">
        <input type="hidden" name="data[com_ID]" value="<?= $_GET["id"]; ?>" />
    <h1 class="text-center mb-4 pb-4">Choisissez les articles à facturer</h1>

    <div class="form-group">
        <label for="comment">Commentaire : </label>
        <textarea id="comment" name="data[comment]" rows="2" class="form-control" placeholder="Commentaire"></textarea>
    </div>
    <div class="row form-group">
        <div class="col-2">
            <label for="tarif"><h5>Tarif :</h5></label>
            <select id="tarif" class="form-control selectTarif" name="data[codetarif]">
                <?php

                foreach($oTarif->db_get_all() as $tarif){ ?>
                    <option <?php if($tarif["tar_ID"] == $tiers["fk_tar_ID"]) echo "selected" ?> value="<?= $tarif['tar_ID'] ?>">T <?= $tarif['tar_ID'] ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
    </div>


    <div class="row form-group pb-4">
        <div class="col-2">
            <h3>Nom article</h3>
        </div>
        <div class="col-2">
            <h3>Prix HT</h3>
        </div>
        <div class="col-2">
            <h3>Taxe produit</h3>
        </div>
        <div class="col-2">
            <h3>Prix TTC</h3>
        </div>
        <div class="col-2">
            <h3>Quantité</h3>
        </div>
        <div class="col-2">
            <h3>Sous-Total</h3>
        </div>
    </div>
        <?php
        foreach ($lignes_recues["content"] as $ligne){
            $prixHT = $prix["content"][$ligne["art_ID"].'-'.$tiers["fk_tar_ID"]]['prix'];
            ?>
            <div class="row form-group">
                <div class="col-2">
                    <div class="form-control"><?= $ligne["art_nom"] ?></div>
                </div>
                <div class="col-2">
                    <div class="form-control">
                        <span class="prixHT" data-index="<?= $ligne["art_ID"] ?>"><?= $prixHT ?> </span> €
                    </div>
                </div>

                <div class="col-2">
                    <div class="form-control">
                        <span class="taxe" data-index="<?= $ligne["art_ID"] ?>" > <?= $ligne["art_taxe"]?></span> %
                    </div>
                    <input type="hidden" value="<?= $ligne["art_ID"] ?>" name="data[article][]">
                </div>
                <div class="col-2">
                    <div class="form-control">
                        <span class="prixTTC" data-index="<?= $ligne["art_ID"] ?>"><?= $prixHT + ($prixHT * (int) $ligne["art_taxe"] / 100) ?> </span> €
                    </div>
                </div>
                <div class="col-2">
                    <input required type="number" min="0" max="<?= $ligne['total'] ?>" class="form-control quantite" value="<?= $ligne['total'] ?>" name="data[quantite][]" data-index="<?= $ligne["art_ID"] ?>">
                </div>

                <div class="col-2 form-control">
                    <span class="sousTotal" data-index="<?= $ligne["art_ID"] ?>"></span> €
                </div>
            </div>
            <?php
            $max += ($prixHT + ($prixHT * (int) $ligne["art_taxe"] / 100)) * $ligne['total'];
        }
        ?>
        <div class="row form-group mt-5">
            <div class="col-6"></div>
            <div class="col-4 text-center">
                <h4><b>Total HT avant remise:</b></h4>
            </div>
            <div class="col-2 form-control">
                 <span class="totalAvantHT"></span> €
                <input type="hidden" name="totalHT" class="hiddenTotalHT" value="<?= $max / 1.20 ?>">
            </div>
        </div>

        <div class="row form-group">
            <div class="col-6"></div>
            <div class="col-4 text-center">
                <h4>Remise (%) :</h4>
            </div>
            <div class="col-2 p-0">
                <input type="number" step="0.01" class="form-control remiseP" value="0">
            </div>
        </div>

        <div class="row form-group">
            <div class="col-6"></div>
            <div class="col-4 text-center">
                <h4>Remise (€) :</h4>
            </div>
            <div class="col-2 p-0">
                <input name="data[remise]" step="0.01" type="number" class="form-control remiseE" value="0">
            </div>
        </div>

        <div class="row form-group">
            <div class="col-6"></div>
            <div class="col-4 text-center">
                <h4><b>Total HT après remise:</b></h4>
            </div>
            <div class="col-2 form-control">
                <span class="totalHT"></span> €
            </div>
        </div>

        <div class="row form-group">
            <div class="col-6"></div>
            <div class="col-4 text-center">
                <h4><b>Taxes :</b></h4>
            </div>
            <div class="col-2 form-control">
                <span class="totalTaxes"></span> €
            </div>
        </div>

        <div class="row form-group">
            <div class="col-6"></div>
            <div class="col-4 text-center">
                <h4><b>Total TTC :</b></h4>
            </div>
            <div class="col-2 form-control">
                <span class="totalTTC"></span> €
            </div>
        </div>

        <div class="float-right col-2 p-0">
            <input type="hidden" name="prevalidation" value="1">
            <button class="btn btn-primary form-control ml-3" type="submit" >Valider</button>
        </div>
    </form>

</div>

<script>
    const prix = <?php echo json_encode($prix["content"]) ?>;
    const max = <?php echo $max ?>;
</script>

<!-- jQuery Library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="./index.js"></script>

<script src="../../script/js/sidenav.js"></script>
<script src="../../script/js/index.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';

<script src="../script/table.js"></script>
</body>
</html>