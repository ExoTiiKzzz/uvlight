<?php
use Carbon\Carbon;

class Login{

    public function login($username='', $password=''){
        if(!$username || !$password){
            return false;
        }
        global $conn;
        $request = "SELECT use_ID, use_password FROM users WHERE use_name = :username";
        $sql = $conn->prepare($request);
        $sql->bindValue(":username", $username, PDO::PARAM_STR);
        $sql->execute();
        
        $res = $sql->fetch(PDO::FETCH_ASSOC);
        if(!$res){
            return false;
        }

        $start = getenv("hash_start");
		$end = getenv("hash_end");

        $password = $start.$password.$end;

        if(password_verify($password, $res["use_password"])){
            if(!isset($_SESSION["session_user_ID"])){
                $user_id = $res["use_ID"];
                $this->create_session($user_id);
            }
            return true;
        }else{
            return false;
        }
    }

    public function create_session(int $user_id=0){

        $_SESSION["session_user_ID"] = $user_id;

    }

    public function validate_SESSION(){
        if(!isset($_SESSION["session_user_ID"])){
            $url = "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
            header("Location: /uvlight/login.php?next=$url");
            die;
        }
    }

    public function logout(){
        unset($_SESSION["session_user_ID"]);
        header("Location: ".$path."login.php");
        die;
    }

    public function is_user_connected(){
        return isset($_SESSION["session_user_ID"]);
    }

}

?>