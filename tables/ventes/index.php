<?php 
    require '../../lib/includes/defines.inc.php';
    require '../../lib/includes/navbar.php';
    require '../../lib/includes/sidenav.php';
    require '../../lib/includes/doctype.php';

    echo doctype("Commandes", $path);
    echo navbar($path);
    echo sidenav($path);
?>
<body>
    <script> 
        const url = "trait.php";
    </script>
    <style>
        <?php 
            require '../static/css/table.css';
        ?>
    </style>
<?php

        $data = $oTypeDocument->db_get_all();
        $articles = $oArticle->db_get_all();
        $liste_articles = "<datalist id='liste_articles'>";
        foreach ($articles as $key) {
            $liste_articles .= "<option value='".$key["art_nom"]."'>";
        }
        $liste_articles .="</datalist>";
        echo $liste_articles;

        echo $oListe->build_liste("liste_etat_document", $oEtatDocument->db_get_all(), 'et_lib');
  ?>

    <div class="main-container sidenav-open">

        <div class="table-container">
            <table id="table">
                <thead>
                    <th>Selectionner</th>
                    <th style='text-align :center'>ID</th>
                    <th style='text-align :center'>Etat</th>
                    <th style='text-align :center'>Date de création</th>
                    <th style='text-align :center'>Actions</th>
                </thead>
            </table>
            <div class="operations-div" style="display: flex; justify-content: space-evenly">
                <button class="btn btn-danger delete-all" style="display: none">
                    Supprimer les éléments selectionnés.
                </button>
            </div>
            <input type="checkbox" class="select-all" id="select-all">
            <label for="select-all" class="form-check-label">Tout sélectionner</label>

        
        </div>

        <!-- Create Modal -->
        <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Passer une commande</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="mx-auto modal-body form-group">
                        <div class="form-group">
                            <label for="comment">Commentaire :</label>
                            <textarea type="text" class="form-control createComment" placeholder="Commentaire" id="comment"></textarea>
                        </div>
                        <div class="articles-container">
                            <div class="row articleRow" data-index="1">
                                <div class="form-group col-6">
                                    <label>Article</label>
                                    <input class="form-control article" data-index="1" style="margin: 0 auto" list="liste_articles" >
                                </div>
                                <div class="form-group col-6">
                                    <label>Quantité</label>
                                    <input class="form-control quantite" data-index="1" style="margin: 0 auto">
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-success addArticle" data-index="1">Ajouter un article à la commande</button>
                    </div>
                
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        <button type="submit" name="update" class="btn btn-primary createBtn">Passer la commande</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Modal -->
        <div class="modal fade" id="updatemodal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Détails de la commande</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="mx-auto modal-body col-10">
                        <div class="form-group">
                            <label for="etat_document">Etat de la commande</label>
                            <select id="etat_document" class="select_etat custom-select"></select>
                        </div>
                        <div class="form-group">
                            <span class="seeDocumentsSpan" style="display: none">Documents associés : </span>
                            <a href="" class="seeDocuments btn btn-primary">Voir les documents associés</a>
                        </div>
                        <div class="form-group">
                            <div class="documents">

                            </div>
                        </div>
                    </div>
                
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary updateCloseBtn" data-dismiss="modal">Close</button>
                        <button type="submit" name="update" class="btn btn-primary updateSaveBtn">Modifier</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
  

    <!-- jQuery Library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Datatable JS -->
    <script src="../script/jquery.dataTables.min.js"></script>
    <script src="../../script/js/sidenav.js"></script>

    <script src="../script/checkboxes.js"></script>
    <script src="../script/deleteRow.js"></script>
    <script src="../script/table.js"></script>
    <script src="./js/index.js"></script>
    <script src="./js/updateRow.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

    <script> //initialisation datatable
        var table = $('#table');
        $(document).ready(function(){
            table.dataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'ajaxfile.php'
                },
                'columns': [
                    { data: 'checkbox' },
                    { data: 'Com_ID' },
                    { data: 'etat' },
                    { data: 'createdate' },
                    { data: 'actions' }
                ],
                deferRender:    true,
                scrollCollapse: true,
                scroller:       true
            });
        });

        function checkForm(formid){
            if(document.querySelector(".name_input[data-index='"+formid+"']").value.length > 10){
                document.querySelector(".alert-danger[data-index='"+formid+"']").style.display = "block";
                return false;
            }else{
                return true;
            }
        }
    </script>
</body>
</html>