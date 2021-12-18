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
    body{
        overflow-y: scroll;
    }
    thead{
       background-color: #707070;
    }
    th{
        width: 25%;
    }
    .accordion{
       width: 100%;
        box-shadow: none;
    }

    .accordion-button{
        width: 100%;
    }
    .accordion h2{
        font-size: 1.5rem;
        margin-bottom: 0;
    }
    .accordion button{
        padding: 10px 0;
        background-color: rgb(170, 170, 170);
    }
    .accordion table{
        margin-bottom: 20px;
    }
    .accordion i{
        float: left;
        margin-left: 20px;
    }
</style>
<?php

$articles = $oArticle->db_get_all();
$data = $oCommande->db_get_detailed_all_documents($_GET['id']);
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
                        <span class="font-weight-bold border-bottom border-white">Récapitulatif de la commande : </span>
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
                <div class="label mb-4 row d-flex p-3">
                    <div class="align">
                        <span class="font-weight-bold border-bottom border-white">Bons de livraison : </span>
                    </div>
                </div>
                <div class="accordion" id="accordionrecep">
                <?php
                    $cpt = 1;
                    foreach($data["content"] as $cate){ ?>
                        <?php
                        if($cate["error"] === false && !empty($cate["data"]) && $cate["data"][0]["fk_typdo_ID"] === 4){
                            ?>
                    <?php
                            foreach($cate["data"] as $ligne){ ?>
                                <?php
                                $lignes_recep = $oCommande->get_lignes_livraison($ligne["doc_ID"]);
                                if($lignes_recep["error"] === false){
                                    ?>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading<?= $cpt ?>">
                                            <button class="accordion-button" type="button" data-index="<?= $cpt ?>" data-bs-toggle="collapse" data-bs-target="#collapse<?= $cpt ?>" aria-expanded="true" aria-controls="collapse<?= $cpt ?>">
                                               <i data-index="<?= $cpt ?>" class="seeMore fal fa-plus"></i> Bon de livraison numéro <?= $cpt ?> (<?= $ligne["doc_create_datetime"] ?>)
                                            </button>
                                        </h2>
                                        <div id="collapse<?= $cpt ?>" class="accordion-collapse collapse  accordion-flush" aria-labelledby="heading<?= $cpt ?>">
                                            <div class="accordion-body">
                                                <div class="form-control my-3">
                                                    <?= $ligne["doc_commentaire"] ?>
                                                </div>
                                                <table>
                                                    <thead>
                                                    <th>Article</th>
                                                    <th>Quantité</th>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    $cptlignes = 0;
                                                    foreach($lignes_recep["data"] as $ligne_recep){
                                                        if($ligne_recep){
                                                            ?>
                                                            <tr>
                                                                <td><?= $ligne_recep["art_nom"] ?></td>
                                                                <td><?= $ligne_recep["Lignr_quantite"] ?></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                        $cptlignes++;
                                                    }
                                                    ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    $cpt++;
                                }
                            }
                        }

                        ?>
                        <?php
                    }
                ?>
                </div>




                <!-- Modal bon de récéption-->

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
</div>


<!-- jQuery Library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- Datatable JS -->
<script src="../script/jquery.dataTables.min.js"></script>
<script src="../../script/js/sidenav.js"></script>
<script src="./js/commande.js"></script>

<script src="../script/table.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

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