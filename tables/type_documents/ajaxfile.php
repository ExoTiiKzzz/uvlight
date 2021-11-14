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

if($columnName === "checkbox" || $columnName === "actions") $columnName = "typdo_id";

$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
   $searchQuery = " AND (typdo_lib LIKE :typdo_lib) ";
   $searchArray = array( 
        'typdo_lib'=>"%$searchValue%"
   );
}

## Total number of records without filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM ".DB_TABLE_TYPE_DOCUMENT ." WHERE typdo_is_visible = 1");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM ".DB_TABLE_TYPE_DOCUMENT." WHERE typdo_is_visible = 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT * FROM ".DB_TABLE_TYPE_DOCUMENT." WHERE typdo_is_visible = 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
    $id = $row["typdo_ID"];
    $checkbox = "<input onchange='checkBoxListener()' type='checkbox' class='checkbox' data-index='$id'>";
    $actionrow = "<div style='display: flex; justify-content: space-evenly'><button type='button' class='btn btn-primary updateBtn' data-toggle='modal' onclick='updateModal(event)' data-target='#updatemodal' data-index='$id'>Modifier</button><button type='submit' name='delete' onclick='deleteEventListener(event)' class='delete-btn btn btn-danger' data-index='$id'>Supprimer</button></div>";
    $data[] = array(
      "checkbox" => $checkbox, 
      "typdo_id" => $id,
      "typdo_lib"=>$row['typdo_lib'],
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