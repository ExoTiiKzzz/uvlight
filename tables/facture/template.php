<?php
include "../../lib/includes/defines.inc.php";
$lignes_facture = $oFacture->db_get_lignes_facture($_GET["id"]);
if($lignes_facture["error"] === true){
    die($lignes_facture["errortext"]);
}
$doc = $oCommande->db_get_document_by_id($_GET["id"])["content"];
$com_ID = $oCommande->db_get_by_doc_ID($_GET["id"])["content"];
$client = $oCommande->db_get_tiers($com_ID);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Template Facture</title>
</head>
<style>

    @font-face {
        font-family: 'Poppins', sans-serif;
        src: url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');
    }

    *{
    box-sizing: border-box;
        padding: 0;
        margin: 0;
        color: rgb(53, 53, 53);
    }

    body{
    background: #ffffff;
}

    .nav{
    display: flex;
    align-items: center;
        justify-content: space-between;
        margin: 100px 0 0 10%;
    }

    .title{
    color: rgb(53, 53, 53);
    font-size: 5em;
    }

    .Nclient{
    font-size: 1.4em;
        font-weight: 500;
    }

    hr{
    margin-bottom: 100px;
    }

    .text{
    font-size: 1em;
    }

    .glbi h2{
    font-size: 1.2em;
        padding-bottom: 50px;
    }

    .date{
    margin: 40px 0;
    }

    .date label{
}

    .tp{
    grid-column-start: 2;
        font-size: 1.1em;
        margin-top: 10px;
    }

    .additionnal{
    margin-top: 50px;
    }

    .additionnal h2{
    font-size: 1.4em;
    }

    .additionnal label{
    font-size: 1em;
    }

    table{
    display: table;
    font-size: 1em;
        padding-bottom: 100px;
        width: 90%;
        margin-left: 5%;
    }

    table th{
    border-bottom: 1px solid rgb(53, 53, 53);
    }

    table thead, table tbody{
    text-align: center;
    }

    table th{
    width: 14.28%;
}

    tbody td{
    padding-top: 30px;
        border-bottom: black 1px;
    }

    .footer{
    position: fixed;
    bottom: 10;
    left: 50%;
    transform: translateX(-50%);
    width: 100%;
    text-align: center;
    }

    .footer2{
        position: fixed;
        top: 10;
        left: 50%;
        transform: translateX(-50%);
        width: 100%;
        text-align: center;
    }

    .footer label, .footer2 label{
    text-align: center;
        font-size: 1.5em;
        font-weight: thin;
        letter-spacing: .5em;
    }

    .container{
    padding-top: 50px;
        margin-left: 5%;
    }

    h1.recap{
        margin-left: 5%;
        margin-bottom: 50px;
        border-bottom: rgb(53, 53, 53) 1px solid;
        display: inline-block;
        font-weight: bold;
    }
</style>
<body>
    <header>
        <nav class="nav">
            <div class="header-container">
                <h1 class="title">Facture n°<?= $_GET["id"] ?></h1>
                <h3 class="Nclient">Client : <?= $client["data"]["tie_raison_sociale"] ?></h3>
                <h3 class="Nclient">Numéro de client : <?= $client["data"]["tie_ID"] ?></h3>

                <div class="date">
                    <h3 class="Nclient">Référence de la commande : <?= $com_ID ?></h3>
                    <h3 class="Nclient">Date de facturation : <?= $doc["doc_create_datetime"] ?></h3>
                </div>
            </div>
        </nav>
    </header>

    <footer class="footer">
        <label>GLBI &copy; - All Right Reserved</label>
    </footer>
    <footer class="footer2">
        <label>GLBI &copy; - All Right Reserved</label>
    </footer>
    <hr>
    <div class="table">
        <h1 class="recap">Récapitulatif de la commande :</h1>
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Quantité</th>
                    <th>Prix unitaire HT</th>
                    <th>Prix unitaire TTC</th>
                    <th>TVA %</th>
                    <th>Total HT</th>
                    <th>Total TTC</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $TotalHT = 0;
                    $TotalTTC = 0;
                    foreach($lignes_facture["content"] as $ligne){
                        $TotalHT += $ligne["TotalHT"];
                        $TotalTTC += $ligne["TotalTTC"];
                        ?>
                            <tr>
                                <td><?= $ligne["art_nom"] ?></td>
                                <td><?= $ligne["quantite"] ?></td>
                                <td><?= $ligne["HT"] . " €" ?></td>
                                <td><?= $ligne["TTC"] . " €" ?></td>
                                <td><?= $ligne["Lignf_taxe"] . " %" ?></td>
                                <td><?= $ligne["TotalHT"] . " €" ?></td>
                                <td><?= $ligne["TotalTTC"] . " €" ?></td>
                            </tr>
                        <?php
                    }
                ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><h4><?= $TotalHT . " €" ?></h4></td>
                    <td><h4><?= $TotalTTC . " €" ?></h4></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="separate"><hr class="separator"></div>
    <div style="page-break-after: always;"></div>
    <div class="container">
        <div class="glbi">
            <h2>GLBI</h2>
            <label class="text">25 Rue des champions</label><br>
            <label class="text">19100 Brive la gaillarde</label><br>
            <label class="text">Tel: +335.45.58.20.XX</label><br>
        </div>
        <div class="additionnal">
            <h2>Merci de nous avoir fait confiance</h2>
            <label class="text">Merci d'avoir choisi la société GLBI pour votre commande !</label>
        </div>
    </div>
</body>
</html>
