<?php 
    require '../../lib/includes/defines.inc.php';
$oLogin->validate_SESSION();
    require '../../lib/includes/navbar.php';
    require '../../lib/includes/sidenav.php';
    require '../../lib/includes/doctype.php';

    echo doctype("Type Documents", $path);
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

  ?>

    <div class="main-container sidenav-open">
        <div class="form-group row col-6 p-4">
            <input class="form-control col-6 mr-3 createLib" type="text" name="lib" placeholder="Nom du casier">
            <button class="btn btn-success createBtn" name="create" type="button">Créer un type de document</button>
        </div>

    <div class="table-container">
        <table id="table">
            <thead>
                <th>Selectionner</th>
                <th style='text-align :center'>ID</th>
                <th style='text-align :center'>Lib</th>
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

    <!-- Modal -->
    <div class="modal fade" id="updatemodal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier le type de document</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="mx-auto modal-body form-group col-8">
                    <input class="form-control updateLib" style="margin: 0 auto" type="text" name="casier_name">
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
                    { data: 'typdo_id' },
                    { data: 'typdo_lib' },
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