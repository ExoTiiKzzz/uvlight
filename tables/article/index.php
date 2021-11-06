<?php 
    require '../../lib/includes/defines.inc.php';
    require '../../lib/includes/navbar.php';
    require '../../lib/includes/sidenav.php';
    require '../../lib/includes/doctype.php';
    
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
    </script>
<?php

        $data = $oArticle->db_get_all();
        $categories = $oCategorie->db_get_all();
        $casiers = $oCasier->db_get_all();

        $listeCasiers = "<datalist id='liste_casiers'>";
        foreach ($casiers as $key) {
            $listeCasiers .= "<option value='".$key["cas_lib"]."'>";
        }
        $listeCasiers .="</datalist>";

        $listeCategories = "<datalist id='liste_categories'>";
        foreach ($categories as $key) {
            $listeCategories .= "<option value='".$key["cat_nom"]."'>";
        }
        $listeCategories .="</datalist>";

        echo $listeCasiers;
        echo $listeCategories;

?>
<div class="main-container sidenav-open">
    

    <button type='button' class='my-3 btn btn-success' data-toggle='modal' data-target='#createmodal'> Créer un article </button>

    <div class="table-container" style="margin-right: 0px;">
        <table id="table">
            <thead>
                <th>Selectionner</th>
                <th style='text-align :center'>ID</th>
                <th style='text-align :center'>Nom</th>
                <th style='text-align :center'>Commentaire</th>
                <th style='text-align :center'>Catégorie</th>
                <th style='text-align :center'>Casier</th>
                <th style='text-align :center'>Actions</th>
            </thead>
            <tbody>
                <?php 
                    foreach ($data as $key) {
                        $id = $key["art_ID"]; ?>
                        <tr data-value="<?php echo $id ?>">
                            <td style='width: 5%'>
                            <input type='checkbox' class='checkbox' data-index="<?php echo $id ?>" checked='false'></td>
                            <td>
                                <center><?php echo $id ?></center>
                            </td>
                            <td>
                                <center><?php echo $key["art_nom"] ?></center>
                            </td>
                            <td>
                                <center><?php echo $key["art_commentaire"] ?></center>
                            </td>
                            <td>
                                <center><?php echo $key["cat_nom"] ?></center>
                            </td>
                            <td>
                                <center><?php echo $key["cas_lib"] ?></center>
                            </td>
                            <td style='display:flex; justify-content: space-evenly;'>
                                <button type='button' class='btn btn-primary updateBtn' data-index="<?php echo $id ?>" data-toggle='modal' data-target='#updateModal'>
                                    Modifier
                                </button>
                                <button type="button" name="delete" data-index="<?php echo $id ?>" class="delete-btn btn btn-danger">
                                    Supprimer
                                </button>
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
    <!-- modal pour créer une catégorie -->
    <div class="modal fade" id="createmodal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Créer un article</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="trait.php" method="post" class="update_form" onsubmit="return checkForm(0)">
                    <div class="mx-auto modal-body col-10">
                        <div class="form-group">
                            <div data-index="0" class="alert alert-danger" style="display: none">Le nom de l'article doit faire entre 1 et 50 charactères maximum</div>
                            <input placeholder="Nom de l'article" class="form-control name_input createLib" data-index="0" 
                            style="margin: 0 auto" type="text" name="article_name" required>
                        </div>
                        <div class="form-group">
                            <textarea placeholder="Commentaire sur le produit" class="form-control createComment" rows="3" name="article_commentaire"></textarea>
                        </div>

                        <div class="form-group">
                            <input list='liste_categories' class="form-control createCat">
                        </div>

                        <div class="form-group">
                            <input list='liste_casiers' class="form-control createCas">
                        </div>
                        
                        
                    </div>
                
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary createCloseBtn" data-dismiss="modal">Fermer</button>
                        <button type="button" name="create" class="btn btn-primary createBtn">Créer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

            <!-- Modal -->
            <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Modifier l'article</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="trait.php" method="post" class="update_form" onsubmit="return checkForm(0)">
                            <div class="mx-auto modal-body col-10">
                                <div class="form-group">
                                    <div data-index="0" class="alert alert-danger" style="display: none">Le nom de l'article doit faire entre 1 et 50 charactères maximum</div>
                                    <input type="hidden" class="updateId">
                                    <input class="form-control name_input updateLib" data-index="0" 
                                    style="margin: 0 auto" type="text" name="article_name" required>
                                </div>
                                <div class="form-group">
                                    <textarea class="form-control updateComment" rows="3" name="article_commentaire"></textarea>
                                </div>

                                <div class="form-group">
                                    <input list='liste_categories' class="form-control updateCat">
                                </div>

                                <div class="form-group">
                                    <input list='liste_casiers' class="form-control updateCas">
                                </div>
                                
                                
                            </div>
                        
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary updateCloseBtn" data-dismiss="modal">Fermer</button>
                                <button type="button" name="update" class="btn btn-primary updateRowBtn">Modifier</button>
                            </div>
                        </form>
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
    <script src="./js/index.js"></script>
    <script src="./js/updateRow.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="../../script/js/sidenav.js"></script>

    <script> //initialisation datatable
        var table = $('#table');
        $(document).ready(function(){
            table.dataTable();
        });

        function checkForm(formid){
            if(0 > document.querySelector(".name_input[data-index='"+formid+"']").value.length > 50){
                document.querySelector(".alert-danger[data-index='"+formid+"']").style.display = "block";
                return false;
            }else{
                return true;
            }
        }
    </script>
</body>
</html>