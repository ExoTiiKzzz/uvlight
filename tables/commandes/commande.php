<?php
if(!isset($_GET["id"]) || empty($_GET["id"])) header('Location: ./');
require '../../lib/includes/defines.inc.php';
require '../../lib/includes/navbar.php';
require '../../lib/includes/sidenav.php';
require '../../lib/includes/doctype.php';

echo doctype("Commande ".$_GET['id'], $path);
echo navbar($path);
echo sidenav($path);
?>
<body>
<style>
    <?php
        require '../static/css/table.css';
    ?>
    th{
        width: 25%;
    }
</style>
<?php

$articles = $oArticle->db_get_all();
$data = $oCommande->db_get_detailed_documents($_GET['id']);
$fournisseur = $oCommande->db_get_fournisseur($_GET["id"]);
$lignes = $oCommande->db_get_lignes_commande($_GET["id"]);
?>


<div class="main-container sidenav-open mb-5">
    <?php
        if($data["error"] === true){
            echo $data["errortext"];
        }else{
            ?>
            <div class="row">
                <div class="col-6 form-group">
                    <label>Fournisseur : </label>
                    <span class="font-weight-bold text-uppercase"><?= $fournisseur['data']["tie_raison_sociale"] ?></span>
                </div>
            </div>
                <div class="label mb-4 row d-flex p-3">
                    <div class="align">
                        <span class="font-weight-bold border-bottom border-white">Éléments : </span>
                    </div>
                    <div class="ml-auto">
                        <button data-toggle="modal" data-target="#command" class="btn btn-primary">Créer un bon de récéption</button>
                    </div>
                </div>

                <div class="table-container">
                    <table id="table" class="mt-4">
                        <thead>
                            <th>Article</th>
                            <th>Quantité commandée</th>
                            <th>Quantité reçue</th>
                            <th>Quantité manquante</th>
                        </thead>
                    </table>
                </div>


                <!-- Modal -->

                <div class="modal fade" id="command" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Bon de réception</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="mx-auto modal-body col-11">
                                <div class="form-group">
                                    <textarea cols="30" rows="2" class="form-control comment" placeholder="Commentaire"></textarea>
                                </div>
                                <?php

                                foreach($lignes["data"] as $ligne){
                                    if($ligne["Lign_is_received"] !== 1){
                                    ?>
                                    <div class="row">
                                        <div class="form-group col-6">
                                            <label>Nom de l'article : </label>
                                            <div class="form-control"><?= $articles[$ligne["fk_art_ID"]]["art_nom"] ?></div>
                                        </div>
                                        <div class="form-group col-3">
                                            <label>Quantité restante : </label>
                                            <div class="form-control restQuantity" data-artid="<?= $ligne['fk_art_ID'] ?>"><?= $ligne["difference"] ?></div>
                                        </div>
                                        <div class="form-group col-3">
                                            <label>Quantité reçue : </label>
                                            <input value="0" type="number" min="1" max="<?= $ligne['difference'] ?>" class="form-control updateCommandQuant" data-artid="<?= $ligne['fk_art_ID'] ?>">
                                        </div>
                                    </div>
                                    <?php
                                    }
                                }

                                ?>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary commandCloseBtn" data-dismiss="modal">Fermer</button>
                                <button type="button" class="btn btn-primary updateCommandBtn">Enregistrer les modifications</button>
                            </div>
                        </div>
                    </div>
                </div>

                <?php

        }
    ?>
</>


<!-- jQuery Library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- Datatable JS -->
<script src="../script/jquery.dataTables.min.js"></script>
<script src="../../script/js/sidenav.js"></script>
<script src="./js/commande.js"></script>

<script src="../script/table.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

<script> //initialisation datatable
    let url_string = window.location.href;
    let url = new URL(url_string);
    const id = url.searchParams.get("id");
        let table = $('#table');
        table.dataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                'url': './commandAjax/unreceived.php',
                'data' : {
                    com_ID: id
                }
            },
            'columns': [
                { data: 'fk_art_ID' },
                { data: 'Lign_quantite' },
                { data: 'Lign_received_quantity' },
                { data: 'difference' }
            ],
            deferRender:    true,
            scrollCollapse: true,
            scroller:       true
        });

</script>
</body>
</html>