<?php 
    require '../../lib/includes/defines.inc.php';
$oLogin->validate_SESSION();
    require '../../lib/includes/navbar.php';
    require '../../lib/includes/sidenav.php';
    require '../../lib/includes/doctype.php';

    echo doctype("Tiers", $path);
    echo navbar($path);
    echo sidenav($path);

?>


    <style>
        <?php 
        require '../static/css/table.css';
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
    <main class="main-container sidenav-open">
        <datalist id="suggestionList">
            <?php 
            echo $citiesdatalist;                                        
            ?>
        </datalist>
        <form action="trait.php" method="post">
            <div class="form-group row col-4 p-4">
                <button type='button' class='btn btn-success' data-toggle='modal' data-target='#createModal'>
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

            </table>
            <input type="checkbox" class="select-all" id="select-all">
            <label for="select-all" class="form-check-label">Tout sélectionner</label>





            <div class="operations-div" style="display: flex; justify-content: space-evenly">
                <button class="btn btn-danger delete-all" style="display: none">
                    Supprimer les éléments selectionnés.
                </button>
            </div>
    </div>

<!--        Create Form-->
        <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Informations du tiers : <span class="updateRaison"></span></h5>
                        <button type="button" class="close createCloseBtn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="createForm">
                            <input type="hidden" name="tiers_id" value="">
                            <div class="row form-group">
                                <div class="col">
                                    <label class="form-label">Raison sociale</label>
                                    <input type="text" class="form-control createRaisonSociale" placeholder="Raison sociale" name="raison_sociale" required value="">
                                </div>
                                <div class="col">
                                    <label class="form-label">Type société</label>
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
                                    <label class="form-label">Téléphone</label>
                                    <input type="text" class="form-control createTel" placeholder="Telephone" name="telephone" required value="">
                                </div>
                                <div class="col">
                                    <label class="form-label">Adresse mail</label>
                                    <input type="email" class="form-control createMail " placeholder="Adresse mail" name="email" required value="">
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="form-label">Adresse</label>
                                <input type="text" class="form-control createAdresse" placeholder="Adresse" name="adresse" required value="">
                            </div>

                            <div class="row form-group">
                                <div class="col">
                                    <label class="form-label">Ville</label>
                                    <input class="form-control createVille" placeholder="Ville" type="text" name="ville">
                                </div>
                                <div class="col">
                                    <label class="form-label">Type tiers</label>
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
                                    <label class="form-label">Type de réglement</label>
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
                                    <label class="form-label">Numéro compte</label>
                                    <input type="text" class="form-control createNumCompte" placeholder="Numéro compte" name="numero_compte">
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col">
                                    <label class="form-label">IBAN</label>
                                    <input type="text" class="form-control createIBAN" placeholder="IBAN" name="iban">
                                </div>
                                <div class="col">
                                    <label class="form-label">BIC</label>
                                    <input type="text" class="form-control createBIC" placeholder="BIC" name="bic">
                                </div>

                            </div>

                            <div class="row form-group">
                                <div class="col">
                                    <label class="form-label">Code Banque</label>
                                    <input type="text" class="form-control createBanque" placeholder="Code Banque" name="code_banque">
                                </div>
                                <div class="col">
                                    <label class="form-label">Code guichet</label>
                                    <input type="text" class="form-control createGuichet" placeholder="Code guichet" name="code_guichet">
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col">
                                    <label class="form-label">Cle RIB</label>
                                    <input type="text" class="form-control createCle" placeholder="Cle RIB" name="cle_rib">
                                </div>
                                <div class="col">
                                    <label class="form-label">Code Tarif</label>
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
                                <label class="form-label">Domicialitation</label>
                                <input type="text" class="form-control createDomi" placeholder="Domiciliation" name="domiciliation">
                            </div>
                            <center><input type="button" name="create" class="btn btn-success btn-lg createBtn" value="Ajouter"></center>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Informations du tiers : <span class="updateRaison"></span></h5>
                        <button type="button" class="close updateCloseBtn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="updateForm">
                            <input class="updateId" type="hidden" name="id" value="">
                            <div class="row form-group">
                                <div class="col">
                                    <label class="form-label">Raison sociale</label>
                                    <input type="text" class="form-control updateRaisonSociale" placeholder="Raison sociale" name="raison_sociale" required value="">
                                </div>
                                <div class="col">
                                    <label class="form-label">Type société</label>
                                    <select type="text" class="form-control updateTypso" placeholder="Type société" name="type_societe" required>
                                        <?php
                                        foreach ($types_societes as $key) {?>
                                            <option class="typsoOption" data-index="<?php echo $key["typso_ID"] ?>" value="<?php echo $key["typso_ID"] ?>"><?php echo $key["typso_acronym"]." (".$key["typso_lib"].")" ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col">
                                    <label class="form-label">Téléphone</label>
                                    <input type="text" class="form-control updateTel" placeholder="Telephone" name="telephone" required value="">
                                </div>
                                <div class="col">
                                    <label class="form-label">Adresse mail</label>
                                    <input type="email" class="form-control updateMail " placeholder="Adresse mail" name="email" required value="">
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="form-label">Adresse</label>
                                <input type="text" class="form-control updateAdresse" placeholder="Adresse" name="adresse" required value="">
                            </div>

                            <div class="row form-group">
                                <div class="col">
                                    <label class="form-label">Région</label>
                                    <input class="form-control updateRegion" placeholder="Région" type="text" list="liste_regions" name="region">
                                </div>
                                <div class="col">
                                    <label class="form-label">Départements</label>
                                    <input type="text" list="liste_departements" placeholder="Départements" class="form-control updateDepartement" name="departement" value="a" readonly="readonly">
                                </div>

                            </div>

                            <div class="row form-group">
                                <div class="col">
                                    <label class="form-label">Ville</label>
                                    <input class="form-control updateVille" placeholder="Ville" type="text" list="liste_villes" name="ville" value="a" readonly="readonly">
                                </div>
                                <div class="col">
                                    <label class="form-label">Type tiers</label>
                                    <select type="text" class="form-control" placeholder="Type tiers" name="type_tiers" required>
                                        <?php
                                        foreach ($types_tiers as $key) {?>
                                            <option class="typtiOption" data-index="<?php echo $key["typti_ID"] ?>" value="<?php echo $key["typti_ID"] ?>"><?php echo $key["typti_lib"] ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>

                            </div>

                            <div class="row form-group">
                                <div class="col">
                                    <label class="form-label">Type de réglement</label>

                                    <select type="text" class="form-control" placeholder="Type réglement" name="type_reglement" required>
                                        <?php
                                        foreach ($types_reglement as $key) {?>
                                            <option class="typreOption" data-index="<?php echo $key["typre_ID"] ?>" value="<?php echo $key["typre_ID"] ?>"><?php echo $key["typre_lib"] ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="col">
                                    <label class="form-label">Numéro compte</label>
                                    <input type="text" class="form-control updateNumCompte" placeholder="Numéro compte" name="numero_compte">
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col">
                                    <label class="form-label">IBAN</label>
                                    <input type="text" class="form-control updateIBAN" placeholder="IBAN" name="iban">
                                </div>
                                <div class="col">
                                    <label class="form-label">BIC</label>
                                    <input type="text" class="form-control updateBIC" placeholder="BIC" name="bic">
                                </div>

                            </div>

                            <div class="row form-group">
                                <div class="col">
                                    <label class="form-label">Code Banque</label>
                                    <input type="text" class="form-control updateBanque" placeholder="Code Banque" name="code_banque">
                                </div>
                                <div class="col">
                                    <label class="form-label">Code guichet</label>
                                    <input type="text" class="form-control updateGuichet" placeholder="Code guichet" name="code_guichet">
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col">
                                    <label class="form-label">Cle RIB</label>
                                    <input type="text" class="form-control updateCle" placeholder="Cle RIB" name="cle_rib">
                                </div>
                                <div class="col">
                                    <label class="form-label">Code Tarif</label>
                                    <select type="text" class="form-control" placeholder="Code tarif" name="code_tarif" required>
                                        <?php
                                        foreach ($tarifs as $key) {?>
                                            <option class="codetarOption" data-index="<?php echo $key["tar_ID"] ?>" value="<?php echo $key["tar_ID"] ?>"><?php echo "Tarif ".$key["tar_lib"] ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Domicialitation</label>
                                <input type="text" class="form-control updateDomi" placeholder="Domiciliation" name="domiciliation">
                            </div>
                            <center><input type="button" name="update" value="Enregistrer" class="btn btn-success btn-lg updateBtn"></center>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </main>
    

    <!-- Datatable JS -->
    <script src="../script/jquery.dataTables.min.js"></script>
    <script src="../../script/js/sidenav.js"></script>
<script src="./js/index.js"></script>
<script src="./js/updateRow.js"></script>

<script src="../script/checkboxes.js"></script>
<script src="../script/table.js"></script>
<script src="../script/deleteRow.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

<script>
    const liste_tarifs = <?php echo json_encode($tarifs)  ?>;
    const liste_types_reglement = <?php echo json_encode($types_reglement) ?>;
    const liste_types_societe = <?php echo json_encode($types_societes) ?>;
    const liste_types_tiers = <?php echo json_encode($types_tiers) ?>;
    let table = $('#table');
    console.log(liste_types_reglement);
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
                { data: 'ID' },
                { data: 'lib' },
                { data: 'typso' },
                { data: 'ville'},
                { data: 'tel'},
                { data: 'tarif'},
                { data: 'actions' }
            ],
            deferRender:    true,
            scrollCollapse: true,
            scroller:       true
        });
    });
</script>
</body>
</html>