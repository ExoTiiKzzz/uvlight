<?php 

class Communes{
	const errmessage = "Une erreur s'est produite, signalez la à l'administrateur \n";

	public function db_get_all(){
		global $conn;

		$request = "SELECT * FROM ".DB_TABLE_COMMUNES.";";
		try{
			$sql = $conn->query($request);
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}catch(PDOException $e){
			return $this->errmessage.$e->getMessage();
		}
	}

	public function db_get_by_id($commune_id=0){
		$commune_id = (int) $commune_id;
		if(!$commune_id){
			return false;
		}

		global $conn;

		$request = "SELECT * FROM ".DB_TABLE_COMMUNES." WHERE com_ID = :commune_id";
		$sql = $conn->prepare($request);
		$sql->bindValue(':commune_id', $commune_id, PDO::PARAM_INT);
		try{
			$sql = $conn->execute($request);
			return $sql->fetch(PDO::FETCH_ASSOC);
		}catch(PDOException $e){
			return $this->errmessage.$e->getMessage();
		}
	}

}

?>