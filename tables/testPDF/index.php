<?php
    require '../../lib/includes/defines.inc.php';
    $categories = $oCategorie->db_get_all();
    $casiers = $oCasier->db_get_all();
$stmt = $conn->prepare("SELECT stockfinal.art_ID, stockfinal.art_commentaire, stockfinal.art_nom, stockfinal.fk_cat_ID, stockfinal.fk_cas_ID, stockfinal.stock 
FROM 
(SELECT (achetee.quantite_achetee - vendue.quantite_vendue) as stock, achetee.quantite_achetee, vendue.quantite_vendue, art_ID, art_nom, art_commentaire, fk_cas_ID, fk_cat_ID
   FROM article, 
   (SELECT SUM(Lign_quantite) AS quantite_vendue, fk_art_ID FROM lignes_commande WHERE Lign_is_vente = 1 GROUP BY fk_art_ID) AS vendue, 
   (SELECT SUM(Lign_quantite) AS quantite_achetee, fk_art_ID FROM lignes_commande WHERE Lign_is_vente = 0 GROUP BY fk_art_ID) AS achetee
         
   WHERE art_is_visible = 1 AND  vendue.fk_art_ID = article.art_ID AND achetee.fk_art_ID = article.art_ID 
   AND article.art_ID != 0 
   GROUP BY article.art_ID
   ORDER BY article.art_ID) as stockfinal
   limit 100");

$stmt->execute();
$empRecords = $stmt->fetchAll();
?>
<page>
    <page_header>
        <style>
            thead{
                background: red;
                width: 100vw;
            }

            thead th{
                padding: 15px 5px 15px 5px;
                text-align: center;
            }

            tbody tr{
                background: rgb(170, 170, 170);
                color: #fff;
            }
            tr {
                width: 100vw;
            }

            tr td{
                padding: 8px 0px ;
                text-align: center;
            }

            table{
                margin: 0;
                border-radius: 10px;
            }

            tbody tr:nth-child(even) {
                background-color: rgb(138, 138, 138);
            }
        </style>
    </page_header>

    <table id="table" style="width: 100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Libelle</th>
                <th>Commentaire</th>
                <th>Stock</th>
                <th>Categorie</th>
                <th>Casier</th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach($empRecords as $row){
                    ?>
                    <tr>
                        <td><?= $row["art_ID"] ?></td>
                        <td><?= $row["art_nom"] ?></td>
                        <td><?= $row["art_commentaire"] ?></td>
                        <td><?= $row["stock"] ?></td>
                        <td><?= $categories[$row["fk_cat_ID"]]["cat_nom"] ?></td>
                        <td><?= $casiers[$row["fk_cas_ID"]]["cas_lib"] ?></td>
                    </tr>

                    <?php
                }
            ?>
        </tbody>
    </table>
</page>