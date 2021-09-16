<?php 


// define database tables names
define('DB_TABLE_ARTICLE', 'article');
define('DB_TABLE_CASIER', 'casier');
define('DB_TABLE_CATEGORIE', 'categorie');
define('DB_TABLE_COMMUNES', 'communes');
define('DB_TABLE_COMPOSE', 'compose');
define('DB_TABLE_DEPEND', 'depend');
define('DB_TABLE_DOCUMENT', 'document');
define('DB_TABLE_DOC_PRODUIT', 'doc_produit');
define('DB_TABLE_EMPLACEMENT', 'emplacement');
define('DB_TABLE_ETAT_DOCUMENT', 'etat_doc');
define('DB_TABLE_PAYE', 'paye');
define('DB_TABLE_TARIF', 'tarif');
define('DB_TABLE_TIERS', 'tiers');
define('DB_TABLE_TYPE_REGLEMENT', 'type_reglement');
define('DB_TABLE_TYPE_SOCIETE', 'type_societe');

//define paths
define('DB_CLASS_DIR', 'db/classes/');


// get main classes
include_once DB_CLASS_DIR.'casier.class.inc.php';
include_once DB_CLASS_DIR.'categorie.class.inc.php';
include_once DB_CLASS_DIR.'etat_document.class.inc.php';
include_once DB_CLASS_DIR.'tarif.class.inc.php';
include_once DB_CLASS_DIR.'type_reglement.class.inc.php';
include_once DB_CLASS_DIR.'type_societe.class.inc.php';
include_once DB_CLASS_DIR.'article.class.inc.php';

// get main objects
$oCasier = new Casier();
$oCategorie = new Categorie();
$oEtatDocument = new Etat_Document();
$oTarif = new Tarif();
$oTypeReglement = new Type_Reglement();
$oTypeSociete = new Type_Societe();
$oArticle = new Article();
?>