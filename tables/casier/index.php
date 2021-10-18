<?php 
    require '../../lib/includes/defines.inc.php';
    require '../../lib/includes/navbar.php';
    require '../../lib/includes/sidenav.php';
    require '../../lib/includes/doctype.php';

    echo doctype("Article", $path);
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
                <input class="form-control col-6 mr-3" type="text" name="casier_name" placeholder="Nom du casier">
                <button class="btn btn-success col-5" name="create" type="submit">Créer un casier</button>
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
                            <tr data-value="<?php echo $id ?>">
                            <td style='width: 5%'>
                            <input type='checkbox' class='checkbox' data-index="<?php echo $id ?>" checked='false'></td>
                            <td><center><?php echo $id ?></center></td>
                            <td><center><?php echo $key["cas_lib"] ?></center></td>
                            <td style='display:flex; justify-content: space-evenly;'>
                                <button type='button' class='btn btn-primary' data-toggle='modal' data-target='#modal<?php echo $id ?>'>
                                    Modifier
                                </button>
                                <form action="trait.php" method="post">
                                    <input type="hidden" name="casier_id" value="<?php echo $id ?>">
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
                <div class="modal fade" id="modal<?php echo $key["cas_ID"] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Modifier le casier</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="trait.php" method="post" class="update_form" data-index="<?php echo $key["cas_ID"] ?>" onsubmit="return checkForm(<?php echo $key['cas_ID'] ?>)">
                                <div class="mx-auto modal-body form-group col-8">
                                    <div data-index="<?php echo $key["cas_ID"] ?>" class="alert alert-danger" style="display: none">Le nom du casier doit faire entre 1 et 10 charactères maximum</div>
                                    <input class="form-control name_input" data-index="<?php echo $key["cas_ID"] ?>" style="margin: 0 auto" type="text" name="casier_name" value="<?php echo $key["cas_lib"]; ?>">
                                    <input type="hidden" name="casier_id" value="<?php echo $key["cas_ID"] ?>">
                                </div>
                            
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" name="update" class="btn btn-primary">Modifier</button>
                                </div>
                            </form>
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

    <script src="../script/checkboxes.js"></script>
    <script src="../script/index.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    <script> //initialisation datatable
        $(document).ready(function(){
            $('#table').DataTable();
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