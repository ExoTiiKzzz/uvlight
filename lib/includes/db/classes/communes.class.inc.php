<?php 

class Communes{
	const errmessage = "Une erreur s'est produite, signalez la à l'administrateur \n";

	public function db_get_all(){
		global $conn;

		$request = "SELECT * FROM ".DB_TABLE_COMMUNES." 
		INNER JOIN ".DB_TABLE_DEPARTEMENT." 
		ON ".DB_TABLE_COMMUNES.".department_code = ".DB_TABLE_DEPARTEMENT.".d_code WHERE c_is_visible = 1;";
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

		$request = "SELECT * FROM ".DB_TABLE_COMMUNES." WHERE c_id = :commune_id";
		$sql = $conn->prepare($request);
		$sql->bindValue(':commune_id', $commune_id, PDO::PARAM_INT);
		try{
			$sql->execute();
			return $sql->fetch(PDO::FETCH_ASSOC);
		}catch(PDOException $e){
			return $this->errmessage.$e->getMessage();
		}
	}

	public function db_create($inseecode=0, $zipcode=0 , $libelle='', $departement_code=0, $lat = '', $lng = ''){

        global $conn;
        $request = "INSERT INTO ".DB_TABLE_COMMUNES." (department_code, insee_code, zip_code, c_name, c_slug , gps_lat, gps_lng) 
		VALUES(:department_code, :insee_code, :zip_code, :name, :slug, :lat, :lng);";
        $sql = $conn->prepare($request);
        $sql->bindValue(':department_code', $departement_code, PDO::PARAM_STR);
        $sql->bindValue(':insee_code', $inseecode, PDO::PARAM_STR);
        $sql->bindValue(':zip_code', $zipcode, PDO::PARAM_STR);
        $sql->bindValue(':name', $libelle, PDO::PARAM_STR);
        $sql->bindValue(':slug', strtolower($libelle), PDO::PARAM_STR);
        $sql->bindValue(':lat', $lat, PDO::PARAM_STR);
        $sql->bindValue(':lng', $lng, PDO::PARAM_STR);

        try{
            $sql->execute();
            return true;
        }catch(PDOException $e){
            return $this->errmessage.$e->getMessage();
        }
    }

    public function db_update_one($commune_id=0, $newlib='', $inseecode='', $zipcode='', $lat='', $lng=''){
       $commune_id = (int) $commune_id;
        if(!$commune_id){
            return false;
        }

        global $conn;

        $request = "UPDATE ".DB_TABLE_COMMUNES." SET c_name = :name, c_slug = :slug, insee_code = :insee_code, zip_code= :zip_code, gps_lat= :lat, gps_lng=:lng  WHERE c_id = :commune_id";
        $sql = $conn->prepare($request);
        $sql->bindValue(':commune_id', $commune_id, PDO::PARAM_STR);
        $sql->bindValue(':insee_code', $inseecode, PDO::PARAM_STR);
        $sql->bindValue(':zip_code', $zipcode, PDO::PARAM_STR);
        $sql->bindValue(':name', $newlib, PDO::PARAM_STR);
        $sql->bindValue(':slug', strtolower($newlib), PDO::PARAM_STR);
        $sql->bindValue(':lat', $lat, PDO::PARAM_STR);
        $sql->bindValue(':lng', $lng, PDO::PARAM_STR);
        try{
            $sql->execute();
            return true;
        }catch(PDOException $e){
            return $this->errmessage.$e->getMessage();
        }
    }

    public function db_soft_delete_one($commune_id=0){
        $commune_id = (int) $commune_id;

        if(!$commune_id) {
            return false;
        }

        global $conn;

        $request = "UPDATE ".DB_TABLE_COMMUNES." SET c_is_visible = 0 WHERE c_id = :id;";
        $sql = $conn->prepare($request);
        $sql->bindValue(':id', $commune_id, PDO::PARAM_INT);
        try{
            $sql->execute();
            return true;
        }catch(PDOException $e){
            return $this->errmessage.$e->getMessage();
        }
    }

}

?>