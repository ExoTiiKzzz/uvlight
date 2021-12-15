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
define('DB_TABLE_LIGNES_RECEPTION', 'lignes_reception');
define('DB_TABLE_RECEPTION', 'reception');
define('DB_TABLE_LIGNES_FACTURE', 'lignes_facture');

// err message
define('BASIC_ERROR', 'Une erreur s\'est produite, signalez la à l\'administrateur \n');

//define paths
define('DB_CLASS_DIR', 'db/classes/');
define('INC', '.class.inc.php');


// get main classes
include_once DB_CLASS_DIR.'casier'.INC;
include_once DB_CLASS_DIR.'categorie'.INC;
include_once DB_CLASS_DIR.'etat_document'.INC;
include_once DB_CLASS_DIR.'tarif'.INC;
include_once DB_CLASS_DIR.'type_reglement'.INC;
include_once DB_CLASS_DIR.'type_document'.INC;
include_once DB_CLASS_DIR.'type_societe'.INC;
include_once DB_CLASS_DIR.'article'.INC;
include_once DB_CLASS_DIR.'articlequantite'.INC;
include_once DB_CLASS_DIR.'communes'.INC;
include_once DB_CLASS_DIR.'compose'.INC;
include_once DB_CLASS_DIR.'region'.INC;
include_once DB_CLASS_DIR.'departement'.INC;
include_once DB_CLASS_DIR.'user'.INC;
include_once DB_CLASS_DIR.'login'.INC;
include_once DB_CLASS_DIR.'tiers'.INC;
include_once DB_CLASS_DIR.'type_tiers'.INC;
include_once DB_CLASS_DIR.'produit'.INC;
include_once DB_CLASS_DIR.'generator'.INC;
include_once DB_CLASS_DIR.'liste'.INC;
include_once DB_CLASS_DIR.'sous_categorie'.INC;
include_once DB_CLASS_DIR.'commande'.INC;
include_once DB_CLASS_DIR.'facture'.INC;
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
$oFacture = new Facture();

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
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
}
catch (PDOException $e) {
    throw $e;
}
?>