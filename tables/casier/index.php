<?php 
    require '../../lib/includes/defines.inc.php';
$oLogin->validate_SESSION();
    require '../../lib/includes/navbar.php';
    require '../../lib/includes/sidenav.php';
    require '../../lib/includes/doctype.php';

    echo doctype("Casier", $path);
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

        $data = $oCasier->db_get_all();

  ?>

  <div class="main-container sidenav-open">
        <form action="trait.php" method="post">
            <div class="form-group row col-4 p-4">
                <input class="form-control col-6 mr-3 createLib" type="text" placeholder="Nom du casier">
                <button class="btn btn-success col-5 createBtn" name="create" type="button">Créer un casier</button>
            </div>
        </form>

        <div class="table-container">
            <table id="table">
                <thead>
                <th>Selectionner</th>
                <th style='text-align :center'>ID</th>
                <th style='text-align :center'>Lib</th>
                <th style='text-align :center'>Actions</th>
                </thead>
                <tbody>
                <?php
                foreach ($data as $key) {
                    $id = $key["cas_ID"]; ?>
                    <tr data-value="<?php echo $id ?>" data-rowindex="<?php echo $id ?>">
                        <td style='width: 5%'>
                            <input type='checkbox' class='checkbox' data-index="<?php echo $id ?>" checked='false'>
                        </td>
                        <td>
                            <center><?php echo $id ?></center>
                        </td>
                        <td>
                            <center><?php echo $key["cas_lib"] ?></center>
                        </td>
                        <td style='display:flex; justify-content: space-evenly;'>
                            <button type='button' data-index="<?php echo $id ?>" class='btn btn-primary updateBtn' data-toggle='modal' data-target='#updateModal'>
                                Détails
                            </button>
                            <button type="button" data-index="<?php echo $id ?>" name="delete" class="delete-btn btn btn-danger">
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

    <!-- Modal -->
    <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier le casier</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="mx-auto modal-body form-group col-8">
                        <input type="hidden" class="updateId">
                        <input class="form-control updateLib" type="text">
                    </div>
                
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary updateCloseBtn" data-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-primary updateRowBtn">Modifier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
  </div>
  

    <!-- jQuery Library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';

    <!-- Datatable JS -->
    <script src="../script/jquery.dataTables.min.js"></script>
    <script src="../../script/js/sidenav.js"></script>
    <script src="../../script/js/index.js"></script>

    <script src="../script/checkboxes.js"></script>
    <script src="./js/index.js"></script>
    <script src="./js/updateRow.js"></script>
    <script src="../script/deleteRow.js"></script>
    <script src="../script/table.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    <script> //initialisation datatable
        var table = $('#table');
        $(document).ready(function(){
            table.dataTable();
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