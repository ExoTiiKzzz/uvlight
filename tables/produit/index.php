<?php 
require '../../lib/includes/defines.inc.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.2/datatables.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"/>
    <link rel="stylesheet" href="../static/css/table.css">
    <title>Articles</title>
</head>
<body>
    <script> 
        const url = "trait.php";
    </script>
<?php

        $data = $oArticle->db_get_all();
        $categories = $oCategorie->db_get_all();
        $casiers = $oCasier->db_get_all();

        $articles = $oArticle->db_get_all();

        $liste_articles = "";
        foreach ($articles as $key) {
            $liste_articles .= "<option value=".$key["art_ID"].">".$key["art_nom"]."</option>";
        }
        $liste_casiers = "";
        foreach ($casiers as $key) {
            $liste_casiers .= "<option value=".$key["cas_ID"].">".$key["cas_lib"]."</option>";
        }

?>
    <button type='button' class='mt-4 ml-3 btn btn-success' data-toggle='modal' data-target='#createmodal'> Créer un produit </button>
    <!-- modal pour créer une catégorie -->
    <div class="modal fade" id="createmodal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Créer un article</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="trait.php" method="post" class="update_form">
                    <div class="mx-auto modal-body col-10">
                        <div class="main-form">
                            <div class="form-group">
                                <div class="alert alert-danger" style="display: none">Le nom du produit doit faire entre 1 et 50 charactères maximum</div>
                                <input placeholder="Nom du produit" class="form-control name_input"
                                style="margin: 0 auto" type="text" name="produit_name" required>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" placeholder="Commentaire pour le produit" rows="3" name="commentaire"></textarea>
                            </div>
                            <div class="form-group">
                                <select name="casier" class="form-control">
                                    <?php echo $liste_casiers ?>
                                </select>
                            </div>
                            <div class="row">
                                <div class="form-group col-7">
                                    <select type="text" class="form-control" placeholder="Article" name="article[]" required autocomplete="off" required>
                                    <?php echo $liste_articles ?>
                                    </select>
                                </div>
                                <div class="form-group col-3">
                                    <input placeholder="Quantité" class="form-control" name="quantite[]">
                                </div>

                                
                            </div>
                        </div>
                        
                        <div class="btn btn-success add_article">Ajouter un article</div>

                    </div>
                
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        <button type="submit" name="create" class="btn btn-primary">Créer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="table-container p-3">
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
                                <button type='button' class='btn btn-primary' data-toggle='modal' data-target='#modal<?php echo $id ?>'>
                                    Modifier
                                </button>
                                <form action="trait.php" method="post">
                                    <input type="hidden" name="article_id" value="<?php echo $id ?>">
                                    <button type="submit" name="delete" class="delete-btn btn btn-danger">Supprimer</button>
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
    

    <?php 
    
        foreach($data as $key){ ?>

            <!-- Modal -->
            <div class="modal fade" id="modal<?php echo $key["art_ID"] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Modifier le casier</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="trait.php" method="post" class="update_form" onsubmit="return checkForm(0)">
                            <input type="hidden" name="article_id" value="<?php echo $key["art_ID"] ?>">
                            <div class="mx-auto modal-body col-10">
                                <div class="form-group">
                                    <div data-index="0" class="alert alert-danger" style="display: none">Le nom de l'article doit faire entre 1 et 50 charactères maximum</div>
                                    <input placeholder="Nom de l'article" class="form-control name_input" data-index="0" 
                                    style="margin: 0 auto" type="text" name="article_name" required value="<?php echo $key["art_nom"] ?>">
                                </div>
                                <div class="form-group">
                                    <textarea placeholder="Commentaire sur le produit" class="form-control" rows="3" name="article_commentaire"><?php if($key["art_commentaire"] === "0"){ echo " "; }else { echo $key["art_commentaire"]; } ?></textarea>
                                </div>

                                <div class="form-group">
                                    <select type="text" class="form-control" placeholder="Code tarif" name="categorie" required autocomplete="off" required>
                                        <?php 
                                        foreach ($categories as $subkey) {?>
                                            <option value="<?php echo $subkey["cat_ID"] ?>" <?php if($key["fk_cat_ID"] === $subkey["cat_ID"]) echo "selected" ?>><?php echo $subkey["cat_nom"] ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select type="text" class="form-control" placeholder="Code tarif" name="casier" required autocomplete="off" required>
                                        <?php 
                                        foreach ($casiers as $subkey) {?>
                                            <option value="<?php echo $subkey["cas_ID"] ?>" <?php if($key["fk_cas_ID"] === $subkey["cas_ID"]) echo "selected" ?>><?php echo $subkey["cas_lib"] ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                
                                
                            </div>
                        
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                <button type="submit" name="update" class="btn btn-primary">Modifier</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        
        <?php    
        }

    ?>

    <!-- jQuery Library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Datatable JS -->
    <script src="../script/jquery.dataTables.min.js"></script>

    <script src="../script/checkboxes.js"></script>
    <script src="../script/index.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    <script> //initialisation datatable
        $(document).ready(function(){
            $('#table').DataTable();
        });

        function checkForm(formid){
            if(0 > document.querySelector(".name_input[data-index='"+formid+"']").value.length > 50){
                document.querySelector(".alert-danger[data-index='"+formid+"']").style.display = "block";
                return false;
            }else{
                return true;
            }
        }

        function deleterow(index){
            var rowToDelete = document.querySelector(".row[data-rowindex='"+index+"']");
            mainForm.removeChild(rowToDelete);
        }

        const addArticleBtn = document.querySelector('.add_article');
        const mainForm = document.querySelector('.main-form');
        var index = 2;
        

        addArticleBtn.addEventListener('click',() => {
            index++;
            var formGroup = document.createElement('div');
            formGroup.classList.add('row');
            formGroup.dataset.rowindex = index;
            formGroup.innerHTML = "<div class='form-group col-7'> <select type='text' class='form-control' placeholder='Article' name='article[]' required autocomplete='off' required>"
                + "<?php echo $liste_articles ?></select></div><div class='form-group row col-3'><input placeholder='Quantité' class='col col-8 mr-2 form-control' name='quantite[]'>"
                +"<button class='col btn btn-danger' onclick='deleterow("+index+")'>X</button></div>";
                
            mainForm.appendChild(formGroup);
        })
    </script>
</body>
</html>