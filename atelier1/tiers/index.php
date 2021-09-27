<?php 
require '../../lib/includes/defines.inc.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../static/css/table.css">
    <link rel="stylesheet" href="../../assets/css/loader.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"/>
    <link href='//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
    <title>Tiers</title>
</head>
<body>
    <div class="loader">
        <h2>Chargement...</h2>
        <img src="../../assets/img/svg/loader.svg">
    </div>
    <style>
        <?php 
        require '../../assets/css/navbar.css';
        ?> 
    </style>
    <script> 
        const url = "trait.php";
    </script>
<?php
        $data = $oTiers->db_get_all();
        $types_reglement = $oTypeReglement->db_get_all();
        $types_societes = $oTypeSociete->db_get_all();
        $types_tiers = $oTypeTiers->db_get_all();
        $tarifs = $oTarif->db_get_all();
        $cities = $oCommunes->db_get_all_names();

        $citiesdatalist = "";

        foreach ($cities as $subkey) { 
            $citiesdatalist .= '<option data-value="'.$subkey["c_ID"].'>'.$subkey["c_ID"]." ".$subkey["zip_code"]." ".$subkey["c_name"] .'</option>';
        }


  ?>
    <header>
        <ul class="menu">
            <a data-toggle="tooltip" data-placement="bottom" title="Accueil" href="../../"><i class="fas fa-home"></i></a>
            <a data-toggle="tooltip" data-placement="bottom" title="Tiers" href="./"><i class="fas fa-user"></i></a>
            <a data-toggle="tooltip" data-placement="bottom" title="Produits" href="#">Produits(Pas encore dispo)</a>
            <a class="logout" data-toggle="tooltip" data-placement="bottom" title="Se déconnecter"><i class="fas fa-power-off"></i></a>
        </ul>
    </header>

    
    <main style="display: none">
        <datalist id="suggestionList">
            <?php 
            echo $citiesdatalist;                                        
            ?>
        </datalist>
        <form action="trait.php" method="post">
            <div class="form-group row col-4 p-4">
                <button type='button' class='btn btn-success' data-toggle='modal' data-target='#modalcreate'>
                    Ajouter un tiers
                </button>
            </div>
        </form>

        <div class="table-container p-3">
            <table id="table">
                <thead>
                    <th>Selectionner</th>
                    <th>ID</th>
                    <th>Raison Sociale</th>
                    <th>Type Société</th>
                    <th>Ville</th>
                    <th>Téléphone</th>
                    <th>Code Tarif</th>
                    <th>Actions</th>
                </thead>
                <tbody>
                    <?php 
                        foreach ($data as $key) {
                            $id = $key["tie_ID"]; ?>
                            <tr data-value="<?php echo $id ?>">
                                <td style='width: 5%'>
                                <input type='checkbox' class='checkbox' data-index="<?php echo $id ?>" checked='false'></td>
                                <td><center><?php echo $id ?></center></td>
                                <td><center><?php echo $key["tie_raison_sociale"] ?></center></td>
                                <td><center><?php echo $key["typso_acronym"] ?></center></td>
                                <td><center><?php echo $key["c_name"] ?></center></td>
                                <td><center><?php echo $key["tie_tel"] ?></center></td>
                                <td><center><?php echo $key["tar_lib"] ?></center></td>
                                <td style='display:flex; justify-content: space-evenly;'>
                                    <button type='button' class='btn btn-primary' data-toggle='modal' data-target='#modal<?php echo $id ?>'>
                                        Voir plus
                                    </button>
                                    <form action="trait.php" method="post">
                                        <input type="hidden" name="tiers_id" value="<?php echo $id ?>">
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

        <div class="modal fade" id="modalcreate" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Créer un tiers</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="trait.php" method="post">
                            <div class="row form-group">
                                <div class="col">
                                    <input type="text" class="form-control" placeholder="Raison sociale" name="raison_sociale" required>
                                </div>
                                <div class="col">
                                    <select type="text" class="form-control" placeholder="Type société" name="type_societe" required>
                                        <?php 
                                        foreach ($types_societes as $key) {?>
                                            <option value="<?php echo $key["typso_ID"] ?>"><?php echo $key["typso_acronym"]." (".$key["typso_lib"].")" ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col">
                                    <input type="text" class="form-control" placeholder="Telephone" name="telephone" required>
                                </div>
                                <div class="col">
                                    <input type="email" class="form-control" placeholder="Adresse mail" name="email" required>
                                </div>
                            </div>
                            
                            
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Adresse" name="adresse" required>
                            </div>
                            
                            <div class="row form-group">
                                <div class="col">
                                    <input class="villeinput form-control" list="suggestionList" id="answerInput" placeholder="Ville">
                                        <datalist id="suggestionList">
                                            <?php 
                                            echo $citiesdatalist;                                        
                                            ?>
                                        </datalist>
                                    <input type="hidden" name="ville" id="answerInput-hidden" required>
                                </div>
                                <div class="col">
                                    <select type="text" class="form-control" placeholder="Type tiers" name="type_tiers" required>
                                        <?php 
                                        foreach ($types_tiers as $key) {?>
                                            <option value="<?php echo $key["typti_ID"] ?>"><?php echo $key["typti_lib"] ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                
                            </div>

                            <div class="row form-group">
                                <div class="col">
                                    <select type="text" class="form-control" placeholder="Type réglement" name="type_reglement" required>
                                        <?php 
                                        foreach ($types_reglement as $key) {?>
                                            <option value="<?php echo $key["typre_ID"] ?>"><?php echo $key["typre_lib"] ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>   
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control" placeholder="IBAN" name="iban" required>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col">
                                    <input type="text" class="form-control" placeholder="BIC" name="bic" required>
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control" placeholder="Code Banque" name="code_banque" required>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col">
                                    <input type="text" class="form-control" placeholder="Code guichet" name="code_guichet" required>
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control" placeholder="Numéro compte" name="numero_compte" required>
                                </div>
                            </div>
                            
                            <div class="row form-group">
                                <div class="col">
                                    <input type="text" class="form-control" placeholder="Cle RIB" name="cle_rib" required>
                                </div>
                                <div class="col">
                                    <select type="text" class="form-control" placeholder="Code tarif" name="code_tarif" required>
                                        <?php 
                                        foreach ($tarifs as $key) {?>
                                            <option value="<?php echo $key["tar_ID"] ?>"><?php echo "Tarif ".$key["tar_lib"] ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Domiciliation" name="domiciliation" required>
                            </div>
                            <center><button type="submit" name="create" class="btn btn-success btn-lg">Ajouter un tiers</button></center>
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>
        

        <?php 
        
            foreach($data as $key){ 
                $id = $key["tie_ID"];
                ?>
                <!-- Modal -->
                <div class="modal fade" id="modal<?php echo $id ?>" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Informations du tiers : <b> <?php echo $key["tie_raison_sociale"] ?></b></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="trait.php" method="post">
                                    <input type="hidden" name="tiers_id" value="<?php echo $id ?>">
                                    <div class="row form-group">
                                        <div class="col">
                                            <input type="text" class="form-control" placeholder="Raison sociale" name="raison_sociale" required value="<?php echo $key["tie_raison_sociale"] ?>">
                                        </div>
                                        <div class="col">
                                            <select type="text" class="form-control" placeholder="Type société" name="type_societe" required autocomplete="off">
                                                <?php 
                                                foreach ($types_societes as $subkey) {?>
                                                    <option value="<?php echo $subkey["typso_ID"] ?>" <?php if($subkey["typso_ID"] === $key["fk_typso_ID"]) echo "selected" ?>><?php echo $subkey["typso_acronym"]." (".$subkey["typso_lib"].")" ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col">
                                            <input type="text" class="form-control" placeholder="Telephone" name="telephone" required value="<?php echo $key["tie_tel"] ?>">
                                        </div>
                                        <div class="col">
                                            <input type="email" class="form-control" placeholder="Adresse mail" name="email" required value="<?php echo $key["tie_email"] ?>">
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Adresse" name="adresse" required value="<?php echo $key["tie_adresse"] ?>">
                                    </div>
                                    
                                    <div class="row form-group">
                                        <div class="col">
                                            <input class="villeinputupdate form-control" list="suggestionList" id="villeinputupdate<?php echo $id ?>" placeholder="Ville" autocomplete="off" value="<?php echo $key["fk_com_ID"]." ".$key["zip_code"]." ".$key["c_name"] ?>">
                                                
                                            <input type="hidden" name="ville" id="villeinputupdate<?php echo $id ?>-hidden" required value="<?php echo $key["fk_com_ID"] ?>">
                                        </div>
                                        <div class="col">
                                            <select type="text" class="form-control" placeholder="Type tiers" name="type_tiers" required autocomplete="off">
                                                <?php 
                                                foreach ($types_tiers as $subkey) {?>
                                                    <option value="<?php echo $subkey["typti_ID"] ?>" <?php if($subkey["typti_ID"] === $key["fk_typti_ID"]) echo "selected" ?>><?php echo $subkey["typti_lib"] ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        
                                    </div>

                                    <div class="row form-group">
                                        <div class="col">
                                            <select type="text" class="form-control" placeholder="Type réglement" name="type_reglement" required autocomplete="off">
                                                <?php 
                                                foreach ($types_reglement as $subkey) {?>
                                                    <option value="<?php echo $subkey["typre_ID"] ?>" <?php if($subkey["typre_ID"] === $key["fk_typre_ID"]) echo "selected" ?>>
                                                        <?php echo $subkey["typre_lib"] ?>
                                                    </option>
                                                <?php
                                                }
                                                ?>
                                            </select>   
                                        </div>
                                        <div class="col">
                                            <input type="text" class="form-control" placeholder="IBAN" name="iban" required value="<?php echo $key["tie_IBAN"] ?>">
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col">
                                            <input type="text" class="form-control" placeholder="BIC" name="bic" required value="<?php echo $key["tie_BIC"] ?>">
                                        </div>
                                        <div class="col">
                                            <input type="text" class="form-control" placeholder="Code Banque" name="code_banque" required value="<?php echo $key["tie_code_banque"] ?>">
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col">
                                            <input type="text" class="form-control" placeholder="Code guichet" name="code_guichet" required value="<?php echo $key["tie_code_guichet"] ?>">
                                        </div>
                                        <div class="col">
                                            <input type="text" class="form-control" placeholder="Numéro compte" name="numero_compte" required value="<?php echo $key["tie_num_compte"] ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="row form-group">
                                        <div class="col">
                                            <input type="text" class="form-control" placeholder="Cle RIB" name="cle_rib" required value="<?php echo $key["tie_cle_rib"] ?>">
                                        </div>
                                        <div class="col">
                                            <select type="text" class="form-control" placeholder="Code tarif" name="code_tarif" required autocomplete="off">
                                                <?php 
                                                foreach ($tarifs as $subkey) {?>
                                                    <option value="<?php echo $subkey["tar_ID"] ?>" <?php if($subkey["tar_ID"] === $key["fk_tar_id"]) echo "selected" ?>><?php echo "Tarif ".$subkey["tar_lib"] ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Domiciliation" name="domiciliation" required value="<?php echo $key["tie_domiciliation"] ?>">
                                    </div>
                                    <center><button type="submit" name="update" class="btn btn-success btn-lg">Sauvegarder</button></center>
                                </form>
                            </div>
                            
                        </div>
                    </div>
                </div>
            
            <?php    
            }

        ?>
    </main>
    

    <!-- Datatable JS -->
    <!-- jQuery Library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../script/jquery.dataTables.min.js"></script>

    <script src="../script/checkboxes.js"></script>
    <script src="../script/index.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    <script> //initialisation datatable
        $(document).ready(function(){
            $('#table').DataTable();
        });

        const logoutBtn = document.querySelector(".logout");

        var cookieName = "user_jwt";

        logoutBtn.addEventListener("click", () => {
            delete_cookie(cookieName);
            window.location.replace("../../login.php");
        })

        function getCookie(name) {
            var match = document.cookie.match(RegExp('(?:^|;\\s*)' + name + '=([^;]*)')); 
            return match ? match[1] : null;
        }

        function delete_cookie(name) {
            document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
        }

        if(getCookie(cookieName)){
            var formData = new FormData();
            formData.append("checkjwt", "jhvh");
            fetch("../../lib/includes/db/classes/trait.php", {
                method: 'POST',
                body: formData
            })
            .then((response) => response.json())
            .then((text) => {
                if(text != true){
                    window.location.replace("../../login.php");
                    delete_cookie(cookieName);
                }
            })
        }else{
            window.location.replace("../../login.php");
        }


        //datalist cities
        $(document).ready(function(){
            var villeinputs = document.querySelectorAll('.villeinput[list], .villeinputupdate[list]');
            villeinputs.forEach(element => {
                element.addEventListener('input', function(e) {
                    var input = e.target,
                        list = input.getAttribute('list'),
                        options = document.querySelectorAll('#' + list + ' option'),
                        hiddenInput = document.getElementById(input.getAttribute('id') + '-hidden'),
                        inputValue = input.value.split(" ")[0];

                    hiddenInput.value = inputValue;

                    for(var i = 0; i < options.length; i++) {
                        var option = options[i];

                        if(option.innerText === inputValue) {
                            hiddenInput.value = option.dataset.value;
                            break;
                        }
                    }
                });
            });
            document.querySelector(".loader").style.display = "none";
            document.querySelector("main").style.display = "block";
        });
        


        //Create modals
    </script>
</body>
</html>