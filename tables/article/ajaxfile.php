<?php
include '../../lib/includes/defines.inc.php';

$categories = $oCategorie->db_get_all();
$casiers = $oCasier->db_get_all();
$fournisseurs = $oTiers->db_get_all_fournisseurs();

## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value

if($columnName === "checkbox" || $columnName === "actions") $columnName = "art_id";
if($columnName === "categorie") $columnName = "fk_cat_ID";
if($columnName === "casier") $columnName = "fk_cas_ID";
if($columnName === "fournisseur") $columnName = "fk_tiers_ID";

$searchArray = array();


## Search 
$searchQuery = " ";
if($searchValue != ''){
       $searchQuery = " AND (art_nom LIKE :art_nom OR 
                             art_commentaire LIKE :art_commentaire OR 
                             fk_cas_ID IN (SELECT cas_ID FROM ".DB_TABLE_CASIER." WHERE cas_lib LIKE :cas_lib) OR 
                             fk_cat_ID IN (SELECT cat_ID FROM ".DB_TABLE_CATEGORIE." WHERE cat_nom LIKE :cat_lib) OR 
                             fk_tiers_ID IN (SELECT tie_ID FROM ".DB_TABLE_TIERS." WHERE tie_raison_sociale LIKE :tie_lib))";
   $searchArray = array(
       'art_nom' => "%$searchValue%",
       'art_commentaire' => "%$searchValue%",
       'cas_lib' => "%$searchValue%",
       'cat_lib' => "%$searchValue%",
       'tie_lib' => "%$searchValue%",
   );
}

## Total number of records without filtering
$stmt = $conn->prepare("SELECT COUNT(*) as allcount
FROM article
WHERE art_is_visible = 1 AND art_ID != 0");
$stmt->execute();
$records = $stmt->fetch(PDO::FETCH_ASSOC);
$totalRecords = $records["allcount"];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount 
   FROM article
WHERE art_is_visible = 1 AND art_ID != 0 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch(PDO::FETCH_ASSOC);
$totalRecordwithFilter = $records["allcount"];

## Fetch records
$stmt = $conn->prepare("SELECT stockfinal.art_ID, stockfinal.art_commentaire, stockfinal.art_nom, stockfinal.fk_tiers_ID, stockfinal.fk_cat_ID, stockfinal.fk_cas_ID, stockfinal.stock 
FROM 
(SELECT (achetee.quantite_achetee - vendue.quantite_vendue) as stock, achetee.quantite_achetee, vendue.quantite_vendue, art_ID, art_nom, art_commentaire, fk_tiers_ID, fk_cas_ID, fk_cat_ID
   FROM article, 
   (SELECT SUM(Lign_quantite) AS quantite_vendue, fk_art_ID FROM lignes_commande WHERE Lign_is_vente = 1 GROUP BY fk_art_ID) AS vendue, 
   (SELECT SUM(Lign_quantite) AS quantite_achetee, fk_art_ID FROM lignes_commande WHERE Lign_is_vente = 0 GROUP BY fk_art_ID) AS achetee
         
   WHERE art_is_visible = 1 AND  vendue.fk_art_ID = article.art_ID AND achetee.fk_art_ID = article.art_ID 
   AND article.art_ID != 0 ".$searchQuery." GROUP BY article.art_ID
   ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit, :offset) as stockfinal");

// Bind values
foreach($searchArray as $key=>$search){
   $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
}
//
$stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
$stmt->execute();
$empRecords = $stmt->fetchAll();

//echo json_encode($empRecords);

$data = array();

foreach($empRecords as $row){
    $id = $row["art_ID"];
    $checkbox = "<input onchange='checkBoxListener()' type='checkbox' class='checkbox' data-index='$id'>";
    $actionrow = "<div style='display: flex; justify-content: space-evenly'><button type='button' class='btn btn-primary updateBtn' data-toggle='modal' onclick='openUpdateModalListener(event)' data-target='#createmodal' data-index='$id'>Détails</button><button type='submit' name='delete' onclick='deleteEventListener(event)' class='delete-btn btn btn-danger' data-index='$id'>Supprimer</button></div>";
    $data[] = array(
      "checkbox" => $checkbox, 
      "art_id" => $id,
      "art_nom"=>$row['art_nom'],
      "art_commentaire"=>$row["art_commentaire"],
      "stock"=>$row["stock"],
      "fournisseur" => $fournisseurs[$row["fk_tiers_ID"]]["tie_raison_sociale"],
      "categorie"=>$categories[$row["fk_cat_ID"]]["cat_nom"],
      "casier"=>$casiers[$row["fk_cas_ID"]]["cas_lib"],
      "actions"=>$actionrow
   );
}


## Response
$response = array(
   "draw" => intval($draw),
   "iTotalRecords" => $totalRecords,
   "iTotalDisplayRecords" => $totalRecordwithFilter,
   "aaData" => $data
);

echo json_encode($response);