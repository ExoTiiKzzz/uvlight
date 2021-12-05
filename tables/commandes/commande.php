<?php
if(!isset($_GET["id"]) || empty($_GET["id"])) header('Location: ./');
require '../../lib/includes/defines.inc.php';
require '../../lib/includes/navbar.php';
require '../../lib/includes/sidenav.php';
require '../../lib/includes/doctype.php';

echo doctype("Commande ".$_GET['id'], $path);
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
    th{
        width: 25%;
    }
</style>
<?php

$articles = $oArticle->db_get_all();
$data = $oCommande->db_get_detailed_documents($_GET['id']);
?>

<div class="main-container sidenav-open mb-5">
    <?php
        if($data["error"] === true){
            echo $data["errortext"];
        }else{

            if($oCommande->db_is_command_received($_GET["id"]) === false){
                ?>
                <div class="label mb-4">
                    <span class="font-weight-bold border-bottom border-white">Eléments manquants : </span>
                </div>

                <div class="table-container">
                    <table id="unreceived" class="mt-4">
                        <thead>
                        <th>Article</th>
                        <th>Quantité commandée</th>
                        <th>Quantité recue</th>
                        <th>Quantité manquante</th>
                        </thead>
                        <tbody>
                        <?php
                        $lignes = $oCommande->db_get_lignes_commande($_GET["id"]);
                        foreach($lignes['data'] as $ligne){
                            if($ligne["Lign_is_received"] != 1){
                                ?>
                                <tr>
                                    <td><?= $articles[$ligne["fk_art_ID"]]["art_nom"] ?></td>
                                    <td><?= $ligne["Lign_quantite"] ?></td>
                                    <td><?= $ligne["Lign_received_quantity"] ?></td>
                                    <td><?= $ligne["difference"] ?></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>

                <div class="label my-4">
                    <span class="font-weight-bold border-bottom border-white">Eléments recus : </span>
                </div>

                <div class="table-container">
                    <table id="received" class="mt-4">
                        <thead>
                        <th>Article</th>
                        <th>Quantité commandée</th>
                        <th>Quantité recue</th>
                        <th>Quantité manquante</th>
                        </thead>
                        <tbody>
                        <?php
                        $lignes = $oCommande->db_get_lignes_commande($_GET["id"]);
                        foreach($lignes['data'] as $ligne){
                            if($ligne["Lign_is_received"] == 1){
                                ?>
                                <tr>
                                    <td><?= $articles[$ligne["fk_art_ID"]]["art_nom"] ?></td>
                                    <td><?= $ligne["Lign_quantite"] ?></td>
                                    <td><?= $ligne["Lign_received_quantity"] ?></td>
                                    <td><?= $ligne["difference"] ?></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>

                <?php
            }

        }
    ?>
</div>


<!-- jQuery Library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- Datatable JS -->
<script src="../script/jquery.dataTables.min.js"></script>
<script src="../../script/js/sidenav.js"></script>

<script src="../script/table.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

<script> //initialisation datatable
    <?php
    if($oCommande->db_is_command_received($_GET["id"]) === false){
        ?>
        $(document).ready(function(){
            $('#unreceived').dataTable();
        });
        $(document).ready(function(){
            $('#received').dataTable();
        });
    <?php
    } ?>

</script>
</body>
</html>