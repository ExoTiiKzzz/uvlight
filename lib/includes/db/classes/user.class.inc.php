<?php 

class User{

    const errmessage = "Une erreur s'est produite, signalez la à l'administrateur \n";

    public function db_get_all(){
		global $conn;
		$request = "SELECT * FROM ".DB_TABLE_USERS.";";

		try{
			$sql = $conn->query($request);
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}catch(PDOException $e){
			return $this->errmessage.$e->getMessage();
		}
    }

	public function db_get_by_id($user_id=0){
		$user_id = (int) $user_id;
		if(!$user_id){
			return false;
		}

		global $conn;

		$request = "SELECT * FROM ".DB_TABLE_USERS." WHERE use_ID = :id";
		$sql = $conn->prepare($request);
		$sql->bindValue(':id', $user_id, PDO::PARAM_INT);
		try{
			$sql->execute();
			return $sql->fetch(PDO::FETCH_ASSOC);
		}catch(PDOException $e){
			return $this->errmessage.$e->getMessage();
		}
	}

	public function create_hash_password($password=""){
		if(!$password){
			return false;
		}

		$start = getenv("hash_start");
		$end = getenv("hash_end");

		$password = $start.$password.$end;

		$hashedpassword = password_hash($password, PASSWORD_BCRYPT);

		return $hashedpassword;
	}

	public function db_create($username='', $fk_fonction_id=0){
		$fk_fonction_id = (int) $fk_fonction_id;

		if(!$username || !$fk_fonction_id){
			return false;
		}

        $basicpassword = "glbipassword";

		$password = $this->create_hash_password($basicpassword);
		if(!$password){
			return false;
		}

		global $conn;
		$request = "INSERT INTO ".DB_TABLE_USERS."(use_name, use_password, fk_fonction_ID) VALUES(:user_nom, :user_password, :fk_fonction_id);";
		$sql = $conn->prepare($request);
		$sql->bindValue(':user_nom', $username, PDO::PARAM_STR);
		$sql->bindValue(':user_password', $password, PDO::PARAM_STR);
		$sql->bindValue(':fk_fonction_id', $fk_fonction_id, PDO::PARAM_INT);
		try{
			$sql->execute();
			return true;
		}catch(PDOException $e){
			return $this->errmessage.$e->getMessage();
		}
	}

}

?>