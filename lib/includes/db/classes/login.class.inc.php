<?php
use Carbon\Carbon;

class Login{

    public function log_in($username='', $password=''){
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
            if(!isset($_COOKIE["user_jwt"])){
                $user_id = $res["use_ID"];
                $this->create_jwt($user_id);
            }
            return true;
        }else{
            return false;
        }
    }

    public function create_jwt($user_id=0){
        $user_id = (int) $user_id;
        if(!$user_id){
            return false;
        }

        global $oUser;

        $user = $oUser->db_get_by_id($user_id);

        $secret = getenv('SECRET');
        $header = json_encode([
            'typ' => 'JWT',
            'alg' => 'HS256'
        ]);
        $payload = json_encode([
            'user_id' => $user["use_ID"],
            'fonction' => $user["fk_fonction_id"],
            'exp' => time()+ 3600 * 24
        ]);
        $base64UrlHeader = base64UrlEncode($header);
        $base64UrlPayload = base64UrlEncode($payload);
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
        $base64UrlSignature = base64UrlEncode($signature);
        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

        setcookie("user_jwt", $jwt, time()+3600*24*365*8, "/");
    }

    public function validate_token(){
        $secret = getenv('SECRET');

        if (!isset($_COOKIE["user_jwt"])) {
            return false;
        }

        $jwt = $_COOKIE["user_jwt"];
        $tokenParts = explode('.', $jwt);
        $header = base64_decode($tokenParts[0]);
        $payload = base64_decode($tokenParts[1]);
        $signatureProvided = $tokenParts[2];

        // check the expiration time - note this will cause an error if there is no 'exp' claim in the token
        $expiration = Carbon::createFromTimestamp(json_decode($payload)->exp);
        $tokenExpired = (Carbon::now()->diffInSeconds($expiration, false) < 0);

        // build a signature based on the header and payload using the secret
        $base64UrlHeader = base64UrlEncode($header);
        $base64UrlPayload = base64UrlEncode($payload);
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
        $base64UrlSignature = base64UrlEncode($signature);

        // verify it matches the signature provided in the token
        $signatureValid = ($base64UrlSignature === $signatureProvided);

        if ($tokenExpired) {
            return false;
        }

        if (!$signatureValid) {
            return false;
        } 

        return true;
    }

    public function get_user_id_jwt(){
        if (!isset($_COOKIE["user_jwt"])) {
            return false;
        }

        $jwt = $_COOKIE["user_jwt"];
        $tokenParts = explode('.', $jwt);

        $payload = base64_decode($tokenParts[1]);

        $user_id = json_decode($payload)->user_id;

        if($user_id){
            return $user_id;
        }else{
            return false;
        }
    }

}

?>