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

if($columnName === "checkbox" || $columnName === "actions") $columnName = "tie_ID";

$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
   $searchQuery = " AND (tie_raison_sociale LIKE :tie_raison_sociale OR
                         tie_ville LIKE :tie_ville OR
                         tie_tel LIKE :tie_tel OR
                         fk_tar_ID LIKE :fk_tar_ID OR
                         tie_ID LIKE :tie_ID OR
                         fk_typso_ID IN (SELECT typso_ID FROM ".DB_TABLE_TYPE_SOCIETE." WHERE typso_acronym LIKE :typso_lib)) ";
   $searchArray = array( 
        'tie_raison_sociale' => "%$searchValue%",
        'tie_ville' => "%$searchValue%",
       'tie_tel' => "%$searchValue%",
       'fk_tar_ID' => "%$searchValue%",
       'tie_ID' => "%$searchValue%",
       'typso_lib' => "%$searchValue%",
   );
}

## Total number of records without filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM ".DB_TABLE_TIERS." WHERE tie_is_visible = 1");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM ".DB_TABLE_TIERS." WHERE tie_is_visible = 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT tie_ID, tie_raison_sociale, tie_tel, tie_ville, fk_tar_ID, typso_acronym
                              FROM ".DB_TABLE_TIERS." T
                              INNER JOIN ".DB_TABLE_TYPE_SOCIETE." TS ON T.fk_typso_ID = TS.typso_ID
                              WHERE tie_is_visible = 1 ".$searchQuery." 
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
    $id = $row["tie_ID"];
    $checkbox = "<input onchange='checkBoxListener()' type='checkbox' class='checkbox' data-index='$id'>";
    $actionrow = "<div style='display: flex; justify-content: space-evenly'><button type='button' class='btn btn-primary updateBtn' data-toggle='modal' onclick='updateModal(event)' data-target='#updateModal' data-index='$id'>Détails</button><button type='submit' name='delete' onclick='deleteEventListener(event)' class='delete-btn btn btn-danger' data-index='$id'>Supprimer</button></div>";
    $data[] = array(
      "checkbox" => $checkbox, 
      "tie_ID" => $id,
      "tie_raison_sociale"=>$row["tie_raison_sociale"],
        "fk_typso_ID"=>$row["typso_acronym"],
        "tie_ville"=>$row["tie_ville"],
        "tie_tel"=>$row["tie_tel"],
        "fk_tar_id"=>$row["fk_tar_ID"],
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