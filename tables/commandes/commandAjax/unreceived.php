<?php
include '../../../lib/includes/defines.inc.php';
## Read value
$articles = $oArticle->db_get_all();
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$id = $_POST["com_ID"];

$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
   $searchQuery = " AND (fk_art_ID IN (SELECT art_ID FROM ".DB_TABLE_ARTICLE." WHERE art_nom LIKE :article)) ";
   $searchArray = array( 
        'article'=>"%$searchValue%"
   );
}
$searchArray["com_ID"] = $id;

## Total number of records without filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount
                              FROM ".DB_TABLE_LIGNES_COMMANDE."
                              WHERE fk_com_ID = :com_ID AND Lign_is_received = 0");
$stmt->bindValue(":com_ID", $id, PDO::PARAM_INT);
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount
                              FROM ".DB_TABLE_LIGNES_COMMANDE."
                              WHERE fk_com_ID = :com_ID AND Lign_is_received = 0 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT *, SUM(Lign_quantite - Lign_received_quantity) as difference 
                              FROM ".DB_TABLE_LIGNES_COMMANDE."
                              WHERE fk_com_ID = :com_ID ".$searchQuery." 
                              GROUP BY fk_art_ID  
                              ORDER BY ".$columnName." ".$columnSortOrder." 
                              LIMIT :limit,:offset");

// Bind values
foreach($searchArray as $key=>$search){
   $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
}
$stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
$stmt->execute();
$empRecords = $stmt->fetchAll();

$data = array();

foreach($empRecords as $row){
    $data[] = array(
        "fk_art_ID"=>$articles[$row["fk_art_ID"]]["art_nom"],
        "Lign_quantite"=>$row["Lign_quantite"],
        "Lign_received_quantity"=>$row["Lign_received_quantity"],
        "difference" => $row["difference"]
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