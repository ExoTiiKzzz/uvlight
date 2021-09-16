<?php 


//Defines des tables de la base de données
define('DB_TABLE_ARTICLE', 'article');
define('DB_TABLE_CASIER', 'casier');
define('DB_TABLE_CATEGORIE', 'categorie');
define('DB_TABLE_COMMUNES', 'communes');
define('DB_TABLE_COMPOSE', 'compose');
define('DB_TABLE_DEPEND', 'depend');
define('DB_TABLE_DOCUMENT', 'document');
define('DB_TABLE_DOC_PRODUIT', 'doc_produit');
define('DB_TABLE_EMPLACEMENT', 'emplacement');
define('DB_TABLE_ETAT_DOC', 'etat_doc');
define('DB_TABLE_PAYE', 'paye');
define('DB_TABLE_TARIF', 'tarif');
define('DB_TABLE_TIERS', 'tiers');
define('DB_TABLE_TYPES_REGLEMENT', 'type_reglement');
define('DB_TABLE_TYPES_SOCIETES', 'type_societe');


// get main classes
include_once 'db/classes/casier.class.php';

// get main objects
$oCasier = new Casier();
?>