<?php 

require '../../defines.inc.php';

if(isset($_POST["create"])){
    $res = $oUser->db_create($_POST["username"], 8);
}elseif(isset($_POST["login"])){
    $res = $oLogin->log_in($_POST["username"], $_POST["password"]);
}elseif(isset($_POST["checkjwt"])){
    $res = $oLogin->validate_token();
}

echo json_encode($res);


?>