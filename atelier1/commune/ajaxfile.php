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

$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
   $searchQuery = " AND (insee_code LIKE :insee_code or zip_code LIKE :zip_code OR c_name LIKE :c_name OR gps_lat LIKE :gps_lat OR gps_lng LIKE :gps_lng OR d_name LIKE :d_name ) ";
   $searchArray = array( 
        'insee_code'=>"%$searchValue%", 
        'zip_code'=>"%$searchValue%",
        'c_name'=>"%$searchValue%",
        'gps_lat'=>"%$searchValue%",
        'gps_lng'=>"%$searchValue%",
        'd_name'=>"%$searchValue%"
   );
}

## Total number of records without filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM ".DB_TABLE_COMMUNES);
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM ".DB_TABLE_COMMUNES." INNER JOIN ".DB_TABLE_DEPARTEMENT." ON ".DB_TABLE_COMMUNES.".department_code =  ".DB_TABLE_DEPARTEMENT.".d_code WHERE 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT * FROM ".DB_TABLE_COMMUNES." 
INNER JOIN ".DB_TABLE_DEPARTEMENT." 
ON ".DB_TABLE_COMMUNES.".department_code = ".DB_TABLE_DEPARTEMENT.".d_code WHERE c_is_visible = 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
    $id = $row["c_id"];
    $actionrow = "<div style='display: flex; justify-content: space-evenly'><a href='index.php?nav=update&id=$id'>Modifier</a><a href='index.php?nav=delete&id=$id'>Supprimer</a></div>";
   $data[] = array(
      "insee_code"=>$row['insee_code'],
      "zip_code"=>$row['zip_code'],
      "c_name"=>$row['c_name'],
      "gps_lat"=>$row['gps_lat'],
      "gps_lng"=>$row['gps_lng'],
      "d_name"=>$row['d_name'],
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