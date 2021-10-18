<?php 
require '../../lib/includes/defines.inc.php';
require '../../lib/includes/navbar.php';
require '../../lib/includes/sidenav.php';
require '../../lib/includes/doctype.php';

echo doctype("Produits", $path);
echo sidenav($path);
echo navbar($path);
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

        $data = $oProduit->db_get_all();
        $categories = $oCategorie->db_get_all();
        $casiers = $oCasier->db_get_all();

        $articles = $oArticle->db_get_all();

        $liste_articles = "<datalist id='liste_articles'>";
        foreach ($articles as $key) {
            $liste_articles .= "<option>".$key["art_nom"]."</option>";
        }
        $liste_articles .= "</datalist>";
        $liste_casiers = "<datalist id='liste_casiers'>";
        foreach ($casiers as $key) {
            $liste_casiers .= "<option>".$key["cas_lib"]."</option>";
        }
        $liste_casiers.= "</datalist>";
        echo $liste_casiers;
        echo $liste_articles;

?>

    <div class="main-container sidenav-open">
        <button type='button' class='my-3 btn btn-success' data-toggle='modal' data-target='#createmodal'> Créer un produit </button>
        <!-- modal pour créer une catégorie -->
        <div class="modal fade" id="createmodal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Créer un Produit</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="trait.php" method="post" class="update_form">
                        <div class="mx-auto modal-body col-10">
                            <div class="main-form">
                                <div class="form-group">
                                    <div class="alert alert-danger" style="display: none">Le nom du produit doit faire entre 1 et 50 charactères maximum</div>
                                    <input placeholder="Nom du produit" class="form-control name_input createLib"
                                    style="margin: 0 auto" type="text" name="produit_name" required>
                                </div>
                                <div class="form-group">
                                    <textarea class="form-control createComment" placeholder="Commentaire pour le produit" rows="3" name="commentaire"></textarea>
                                </div>
                                <div class="form-group">
                                    <select name="casier" class="form-control createCas" required>
                                        <?php echo $liste_casiers ?>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="form-group col-7">
                                        <input class="form-control createArtLib" list="liste_articles" placeholder="Article" name="article[]" required autocomplete="off" required>
                                    </div>
                                    <div class="form-group col-3">
                                        <input placeholder="Quantité" class="form-control createArtQte" name="quantite[]" required>
                                    </div>

                                    
                                </div>
                            </div>
                            
                            <div class="btn btn-success add_article">Ajouter un Produit</div>

                        </div>
                    
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary createCloseBtn" data-dismiss="modal">Fermer</button>
                            <button type="button" name="create" class="btn btn-primary createBtn">Créer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="table-container">
            <table id="table">
                <thead>
                    <th>Selectionner</th>
                    <th style='text-align :center'>ID</th>
                    <th style='text-align :center'>Nom</th>
                    <th style='text-align :center'>Commentaire</th>
                    <th style='text-align :center'>Nombre d'articles</th>
                    <th style='text-align :center'>Actions</th>
                </thead>
                <tbody class="tablebody">
                    <?php 
                        foreach ($data as $key) {
                            $id = $key["pro_ID"];
                            $articles_by_id = $oArticle->db_get_article_by_produit_id($id);
                            ?>
                            <tr data-value="<?php echo $id ?>" data-rowindex="<?php echo $id ?>" class="tablerow">
                                <td style='width: 5%'>
                                <input type='checkbox' class='checkbox' data-index="<?php echo $id ?>" checked='false'></td>
                                <td>
                                    <center><?php echo $id ?></center>
                                </td>
                                <td>
                                    <center><?php echo $key["pro_lib"] ?></center>
                                </td>
                                <td>
                                    <center><?php echo $key["pro_commentaire"] ?></center>
                                </td>
                                <!-- casier à rajouter -->
                                <td>
                                    <center>
                                    <button type='button' class='btn btn-secondary seeArticleBtn' data-toggle='modal' data-index="<?php echo $id ?>" data-target='#seearticles'>
                                        Voir les articles (<span class="countArticles" data-index="<?php echo $id; ?>"><?php echo count($articles_by_id) ?></span>)
                                    </button></center>
                                </td>
                                <td style='display:flex; justify-content: space-evenly;'>
                                    <button type='button' class='btn btn-primary updateBtn' data-toggle='modal' data-index="<?php echo $id ?>" data-target='#update'>
                                        Modifier
                                    </button>
                                    <form>
                                        <button type="button" name="delete" data-index="<?php echo $id ?>" class="delete-btn btn btn-danger">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                            <?php
                        }
                    ?>
                </tbody>
            </table>
            <input type="checkbox" class="select-all" id="select-all">
            <label for="select-all" class="form-check-label">Tout sélectionner</label>

        
        <div class="operations-div" style="display: flex; justify-content: space-evenly">
            <button class="btn btn-danger delete-all" style="display: none">
                Supprimer les éléments selectionnés.
            </button>
        </div>
    </div>
    </div>

            

            

    <!-- Modal -->
    <div class="modal fade" id="update" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title updateTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="trait.php" method="post" class="update_form">
                    <input type="hidden" class="updateId">
                    <div class="mx-auto modal-body col-10">
                        <div class="main-form">
                            <div class="form-group">
                                <div class="alert alert-danger" style="display: none">Le nom du produit doit faire entre 1 et 50 charactères maximum</div>
                                <input placeholder="Nom du produit" class="form-control name_input updateLib"
                                style="margin: 0 auto" type="text" required value="">
                            </div>
                            <div class="form-group">
                                <textarea class="form-control updateComment" placeholder="Commentaire" rows="3"></textarea>
                            </div>
                            <div class="updateContainer">

                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-success updateAddBtn">Ajouter un article</button>
                            </div>
                        </div>

                    </div>
                
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary updateCloseBtn" data-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-primary updateRowBtn">Modifier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="seearticles" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title seearticletitle"><?php echo $key["pro_lib"] ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="mx-auto modal-body col-10">
                    <div class="row">
                        <div class="col-7">
                            Article : 
                        </div>
                        <div class="col-3">
                            Casier : 
                        </div>
                        <div class="col-2">
                            Quantité :
                        </div>
                    </div>
                    <div class="articles-container">

                    </div>
                    
                            
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery Library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Datatable JS -->
    <script src="../script/jquery.dataTables.min.js"></script>

    <script src="../script/checkboxes.js"></script>
    <script src="../script/deleteRow.js"></script>
    <script src="./js/updateRow.js"></script>
    <script src="./js/index.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    <script> //initialisation datatable
        var table = $('#table');
        $(document).ready(function(){
            table.dataTable();
        });

    </script>
</body>
</html>