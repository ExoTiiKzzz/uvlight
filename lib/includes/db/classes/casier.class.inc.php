<?php

class Casier{

    const errmessage = "Une erreur s'est produite, signalez la à l'administrateur \n";

    public function db_get_all(){
        global $conn;
        $request = "SELECT cas_ID, cas_lib FROM ".DB_TABLE_CASIER." WHERE cas_is_visible=1;";

        try{
            $sql = $conn->query($request);
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            return $this->errmessage.$e->getMessage();
        }
    }

    public function db_get_by_id($id=0){
        $id = (int) $id;
        if(!$id){
            return false;
        }

        global $conn;

        $request = "SELECT * FROM ".DB_TABLE_CASIER." WHERE cas_ID = :id";
        $sql = $conn->prepare($request);
        $sql->bindValue(':id', $id, PDO::PARAM_INT);

        try{
            $sql->execute();
            return $sql->fetch(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            return $this->errmessage.$e->getMessage();
        }
    }

	public function db_get_by_lib($casier_lib=''){
		if(!$casier_lib){
			return false;
		}

		global $conn;

		$request = "SELECT * FROM ".DB_TABLE_CASIER." WHERE cas_lib = :lib";
		$sql = $conn->prepare($request);
		$sql->bindValue(':lib', $casier_lib, PDO::PARAM_STR);
		try{
			$sql->execute();
			return $sql->fetch(PDO::FETCH_ASSOC);
		}catch(PDOException $e){
			return $this->errmessage.$e->getMessage();
		}
	}

    public function db_get_one(){
        global $conn;

		$request = "SELECT cas_ID FROM ".DB_TABLE_CASIER." WHERE cas_is_visible = 1 AND cas_ID != 0 LIMIT 1";
		try{
			$sql = $conn->query($request);
			return $sql->fetch(PDO::FETCH_ASSOC);
		}catch(PDOException $e){
			return $this->errmessage.$e->getMessage();
		}
    }

    public function db_create($libelle=''){

        if(!$libelle){
            return false;
        }

        global $conn;
        $request = "INSERT INTO ".DB_TABLE_CASIER." (cas_lib) VALUES(:libelle);";
        $sql = $conn->prepare($request);
        $sql->bindValue(':libelle', $libelle, PDO::PARAM_STR);

        try{
            $sql->execute();
            $res["lastid"] = $conn->lastInsertId();
            return $res;
        }catch(PDOException $e){
            return $this->errmessage.$e->getMessage();
        }
    }

    public function db_update($casier_id=0, $newlib=''){
        $casier_id = (int) $casier_id;
        if(!$casier_id || !$newlib){
            return false;
        }

        global $conn;

        $request = "UPDATE ".DB_TABLE_CASIER." SET cas_lib = :libelle WHERE cas_ID = :id";
        $sql = $conn->prepare($request);
        $sql->bindValue(':libelle', $newlib, PDO::PARAM_STR);
        $sql->bindValue(':id', $casier_id, PDO::PARAM_INT);
        try{
            $sql->execute();
			return true;
        }catch(PDOException $e){
            return $this->errmessage.$e->getMessage();
        }
    }

    public function db_soft_delete_one($casier_id=0){
        $casier_id = (int) $casier_id;

        if(!$casier_id) {
            return false;
        }

        global $conn;

        $request = "UPDATE ".DB_TABLE_CASIER." SET cas_is_visible = 0 WHERE cas_ID = :id;";
        $sql = $conn->prepare($request);
        $sql->bindValue(':id', $casier_id, PDO::PARAM_INT);
        try{
            $sql->execute();
            return true;
        }catch(PDOException $e){
            return $this->errmessage.$e->getMessage();
        }
    }

    public function db_soft_delete_multi($id_array){
        if(!is_array($id_array)){
            return false;
        }

        foreach ($id_array as $key => $value) {
            if(is_nan($id_array[$key]) || !$id_array[$key]){
                return false;
            }
        }

        global $conn;

        $variables = $id_array;
        $placeholders = str_repeat ('?, ',  count ($variables) - 1) . '?';

        $sql = $conn -> prepare ("UPDATE ".DB_TABLE_CASIER." SET cas_is_visible = 0 WHERE cas_ID IN($placeholders)");
        try{
            $sql->execute($variables);
            return true;
        }catch(PDOException $e){
            return $this->errmessage.$e->getMessage();
        }
    }

    public function db_soft_delete_all(){
        global $conn;

        $request = "UPDATE ".DB_TABLE_CASIER." SET cas_is_visible = 0";
        $sql = $conn->prepare($request);
        try{
            $sql->execute();
            return true;
        }catch(PDOException $e){
            return $this->errmessage.$e->getMessage();
        }
    }
}
?>