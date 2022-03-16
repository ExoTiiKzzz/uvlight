<?php
include '../../lib/includes/defines.inc.php';
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value

if($columnName === "checkbox" || $columnName === "actions") $columnName = "cat_ID";

$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
   $searchQuery = " AND (cat_nom LIKE :cat_nom OR 
                         cat_description LIKE :cat_description OR
                         cat_ID LIKE :cat_id) ";
   $searchArray = array( 
        'cat_nom' => "%$searchValue%",
        'cat_description' => "%$searchValue%",
       'cat_id' => "%$searchValue%",
   );
}

## Total number of records without filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM ".DB_TABLE_CATEGORIE." WHERE cat_is_scat = 0 AND cat_is_visible = 1");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM ".DB_TABLE_CATEGORIE." WHERE cat_is_scat = 0 AND cat_is_visible = 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT cat_ID, cat_nom, cat_description 
                              FROM ".DB_TABLE_CATEGORIE." 
                              WHERE cat_is_scat = 0 AND cat_is_visible = 1 ".$searchQuery." 
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
    $id = $row["cat_ID"];
    $checkbox = "<input onchange='checkBoxListener()' type='checkbox' class='checkbox' data-index='$id'>";
    $actionrow = "<div style='display: flex; justify-content: space-evenly'><button type='button' class='btn btn-primary updateBtn' data-toggle='modal' onclick='updateModal(event)' data-target='#updateModal' data-index='$id'>Détails</button><button type='submit' name='delete' onclick='deleteEventListener(event)' class='delete-btn btn btn-danger' data-index='$id'>Supprimer</button></div>";
    $data[] = array(
      "checkbox" => $checkbox, 
      "cat_ID" => $id,
      "cat_nom"=>$row["cat_nom"],
      "cat_description"=>$row["cat_description"],
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