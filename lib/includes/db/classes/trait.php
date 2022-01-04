<?php 

require '../../defines.inc.php';

if(isset($_POST["create"])){
    $res = $oUser->db_create($_POST["username"], 8);
}elseif(isset($_POST["login"])){
    echo json_encode($oLogin->login($_POST["username"], $_POST["password"]));
}elseif(isset($_POST["check_session"])){
    $res = $oLogin->validate_session();
}


?>