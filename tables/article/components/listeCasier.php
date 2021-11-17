<?php
$listeCasiers = "<datalist id='liste_casiers'>";
foreach ($casiers as $key) {
    $listeCasiers .= "<option value='".$key["cas_lib"]."'>";
}  
$listeCasiers .="</datalist>";
echo $listeCasiers;