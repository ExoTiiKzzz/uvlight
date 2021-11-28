<?php 


// define database tables names
define('DB_TABLE_ARTICLE', 'article');
define('DB_TABLE_CASIER', 'casier');
define('DB_TABLE_CATEGORIE', 'categorie');
define('DB_TABLE_COMMUNES', 'cities');
define('DB_TABLE_COMPOSE', 'compose');
define('DB_TABLE_DEPEND', 'depend');
define('DB_TABLE_DOCUMENT', 'document');
define('DB_TABLE_DOC_PRODUIT', 'doc_produit');
define('DB_TABLE_EMPLACEMENT', 'emplacement');
define('DB_TABLE_ETAT_DOCUMENT', 'etats_document');
define('DB_TABLE_PAYE', 'paye');
define('DB_TABLE_TARIF', 'tarif');
define('DB_TABLE_TIERS', 'tiers');
define('DB_TABLE_TYPE_REGLEMENT', 'type_reglement');
define('DB_TABLE_TYPE_SOCIETE', 'type_societe');
define('DB_TABLE_TYPE_DOCUMENT', 'type_document');
define('DB_TABLE_TYPE_TIERS', 'type_tiers');
define('DB_TABLE_REGION', 'regions');
define('DB_TABLE_DEPARTEMENT', 'departments');
define('DB_TABLE_USERS', 'users');
define('DB_TABLE_PRODUIT', 'produit');
define('DB_TABLE_COMMANDE', 'commande');
define('DB_TABLE_LIGNES_COMMANDE', 'lignes_commande');
define('DB_TABLE_SOUS_CATEGORIE', 'sous_categorie');

// err message
define('BASIC_ERROR', 'Une erreur s\'est produite, signalez la à l\'administrateur \n');

//define paths
define('DB_CLASS_DIR', 'db/classes/');


// get main classes
include_once DB_CLASS_DIR.'casier.class.inc.php';
include_once DB_CLASS_DIR.'categorie.class.inc.php';
include_once DB_CLASS_DIR.'etat_document.class.inc.php';
include_once DB_CLASS_DIR.'tarif.class.inc.php';
include_once DB_CLASS_DIR.'type_reglement.class.inc.php';
include_once DB_CLASS_DIR.'type_document.class.inc.php';
include_once DB_CLASS_DIR.'type_societe.class.inc.php';
include_once DB_CLASS_DIR.'article.class.inc.php';
include_once DB_CLASS_DIR.'articlequantite.class.inc.php';
include_once DB_CLASS_DIR.'communes.class.inc.php';
include_once DB_CLASS_DIR.'compose.class.inc.php';
include_once DB_CLASS_DIR.'region.class.inc.php';
include_once DB_CLASS_DIR.'departement.class.inc.php';
include_once DB_CLASS_DIR.'user.class.inc.php';
include_once DB_CLASS_DIR.'login.class.inc.php';
include_once DB_CLASS_DIR.'tiers.class.inc.php';
include_once DB_CLASS_DIR.'type_tiers.class.inc.php';
include_once DB_CLASS_DIR.'produit.class.inc.php';
include_once DB_CLASS_DIR.'generator.class.inc.php';
include_once DB_CLASS_DIR.'liste.class.inc.php';
include_once DB_CLASS_DIR.'sous_categorie.class.inc.php';
include_once DB_CLASS_DIR.'commande.class.inc.php';
//include_once DB_CLASS_DIR.'../../../../server/bootstrap.php';

// get main objects
$oCasier = new Casier();
$oCategorie = new Categorie();
$oEtatDocument = new Etat_Document();
$oTarif = new Tarif();
$oTypeReglement = new Type_Reglement();
$oTypeSociete = new Type_Societe();
$oTypeTiers = new Type_Tiers();
$oArticle = new Article();
$oCommunes = new Communes();
$oCompose = new Compose();
$oRegion = new Region();
$oDepartement = new Departement();
$oUser = new User();
$oLogin = new Login();
$oTiers = new Tiers();
$oProduit = new Produit();
$oTypeDocument = new Type_Document();
$oListe = new Liste();
$oSousCategorie = new Sous_categorie();
$oCommande = new Commande();

$isProd = false;

if(!$isProd){
    $server = "localhost";
    $username = "root";
    $password = "";
    $dbname = "glbi";
    $path = "/";
}else{
    $server = "db5005005670.hosting-data.io";
    $username = "dbu361051";
    $password = "Hippo14campes440!";
    $dbname = "dbs4185065";
    $path = "https://arthurlecompte.com/";
}

try {
    

    // connect to DB
    $conn = new PDO("mysql:host=$server;dbname=$dbname","$username","$password");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
}
catch (PDOException $e) {
    throw $e;
}
?>