<?php
$listeCategories = "<datalist id='liste_categories'>";
foreach ($categories as $key) {
    $listeCategories .= "<option value='".$key["cat_nom"]."'>";
}
$listeCategories .="</datalist>";
echo $listeCategories;