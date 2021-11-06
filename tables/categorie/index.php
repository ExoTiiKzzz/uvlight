<?php 
    require '../../lib/includes/defines.inc.php';
    require '../../lib/includes/navbar.php';
    require '../../lib/includes/sidenav.php';
    require '../../lib/includes/doctype.php';

    echo doctype("Catégorie", $path);
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

        $data = $oCategorie->db_get_all();

  ?>

  <div class="main-container sidenav-open">
    <button type='button' class='my-3 btn btn-success' data-toggle='modal' data-target='#createmodal'> Créer une catégorie </button>

    <!-- modal pour créer une catégorie -->
    <div class="modal fade" id="createmodal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Créer la catégorie</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="mx-auto modal-body col-10">
                    <div class="form-group">
                        <div data-index="0" class="alert alert-danger" style="display: none">Le nom de la catégorie doit faire entre 1 et 50 charactères maximum</div>
                        <input placeholder="Nom de la catégorie" class="form-control name_input createLib" data-index="0" 
                        style="margin: 0 auto" type="text" name="categorie_name">
                    </div>
                    <div class="form-group">
                        <textarea placeholder="Description de la catégorie" class="form-control createComment" rows="3" name="categorie_description"></textarea>
                    </div>                    
                </div>
            
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary createCloseBtn" data-dismiss="modal">Fermer</button>
                    <button type="button" name="create" class="btn btn-primary createBtn">Créer</button>
                </div>
            </div>
        </div>
    </div>

        <div class="table-container">
        <?php 
            
            $resulttable = $oDatatableGenerator->generate_datatable($data, ["cat_ID", "cat_nom", "cat_description"], "cat_ID", true, "checkbox", true, "btn btn-primary updateBtn", true, "delete-btn btn btn-danger");

            if(!$resulttable["error"]){
                echo $resulttable["content"];
            }else{
                $resulttable["errortext"];
            }
        
        ?>
            
            <input type="checkbox" class="select-all" id="select-all">
            <label for="select-all" class="form-check-label">Tout sélectionner</label>

        
        <div class="operations-div" style="display: flex; justify-content: space-evenly">
            <button class="btn btn-danger delete-all" style="display: none">
                Supprimer les éléments selectionnés.
            </button>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier la catégorie</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="trait.php" method="post" class="update_form">
                    <div class="mx-auto modal-body col-10">
                        <div class="form-group">
                            <div class="alert alert-danger" style="display: none">Le nom de la catégorie doit faire entre 1 et 50 charactères maximum</div>
                            <input class="form-control updateLib" style="margin: 0 auto" type="text" name="categorie_name">
                            <input type="hidden" class="updateId">
                        </div>
                        
                        <div class="form-group">
                            <textarea placeholder="Description de la catégorie" class="form-control updateComment" rows="3" name="categorie_description"></textarea>
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
    <script src="../../script/js/sidenav.js"></script>

    <script src="../script/checkboxes.js"></script>
    <script src="./js/index.js"></script>
    <script src="./js/updateRow.js"></script>
    <script src="../script/deleteRow.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

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