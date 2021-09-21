<?php
require '../../lib/includes/defines.inc.php';

$keyword = '%'.$_POST['keyword'].'%';  // récupère la lettre saisie dans le champ texte en provenance de JS 


$sql = "SELECT d_name, d_id FROM departments WHERE d_name LIKE (:var) GROUP BY d_name";  // création de la requete avec sélection des résultats sur la lettre 
$req = $conn->prepare($sql);
$req->bindParam(':var', $keyword, PDO::PARAM_STR);
$req->execute();
$list = $req->fetchAll();
// var_dump($list);
// die;
foreach ($list as $res) {
    //  affichage
	$nom_list_id = str_replace($_POST['keyword'], '<b>'.$_POST['keyword'].'</b>', $res['d_name']);
	// sélection 
      echo '<option value='.$res['d_id'].' onclick="set_item(\''.str_replace("'", "\'", $res['d_name']).'\')">'.$nom_list_id.'</option>';
}

?>