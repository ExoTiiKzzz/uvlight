<?php

class Categorie{

    const errmessage = "Une erreur s'est produite, signalez la à l'administrateur \n";

    public function db_get_all(){
        global $conn;
        $request = "SELECT cat_ID as arrkey, cat_ID, cat_nom, cat_description FROM ".DB_TABLE_CATEGORIE." WHERE cat_is_visible = 1;";

        try{
            $sql = $conn->query($request);
            return $sql->fetchAll(\PDO::FETCH_GROUP|\PDO::FETCH_UNIQUE|\PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            return $this->errmessage.$e->getMessage();
        }
    }

    public function db_get_by_id($categorie_id=0){
        $categorie_id = (int) $categorie_id;
        if(!$categorie_id){
            return false;
        }

        global $conn;

        $request = "SELECT * FROM ".DB_TABLE_CATEGORIE." WHERE cat_ID = :id";
        $sql = $conn->prepare($request);
        $sql->bindValue(':id', $categorie_id, PDO::PARAM_INT);

        try{
            $sql->execute();
            return $sql->fetch(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            return $this->errmessage.$e->getMessage();
        }
    }

    public function db_get_by_lib($categorie_lib=''){
		if(!$categorie_lib){
			return false;
		}

		global $conn;

		$request = "SELECT * FROM ".DB_TABLE_CATEGORIE." WHERE cat_nom = :lib";
		$sql = $conn->prepare($request);
		$sql->bindValue(':lib', $categorie_lib, PDO::PARAM_STR);
		try{
			$sql->execute();
			return $sql->fetch(PDO::FETCH_ASSOC);
		}catch(PDOException $e){
			return $this->errmessage.$e->getMessage();
		}
	}

    public function db_get_one(){
		global $conn;

		$request = "SELECT cat_ID FROM ".DB_TABLE_CATEGORIE." WHERE cat_is_visible = 1 AND cat_ID != 0 LIMIT 1";
		try{
			$sql = $conn->query($request);
			return $sql->fetch(PDO::FETCH_ASSOC);
		}catch(PDOException $e){
			return $this->errmessage.$e->getMessage();
		}
	}

    public function db_create($nom='', $description=''){

        if(!$nom || !$description){
            return false;
        }

        global $conn;
        $request = "INSERT INTO ".DB_TABLE_CATEGORIE." (cat_nom, cat_description) VALUES(:nom, :description);";
        $sql = $conn->prepare($request);
        $sql->bindValue(':nom', $nom, PDO::PARAM_STR);
        $sql->bindValue(':description', $description, PDO::PARAM_STR);

        try{
            $sql->execute();
            $res["lastid"] = $conn->lastInsertId();
            return $res;
        }catch(PDOException $e){
            return $this->errmessage.$e->getMessage();
        }
    }

    public function db_update($categorie_id=0, $newnom='', $newdescription=''){
        $categorie_id = (int) $categorie_id;
        if(!$categorie_id || !$newnom || !$newdescription){
            return false;
        }

        global $conn;

        $request = "UPDATE ".DB_TABLE_CATEGORIE." SET cat_nom = :nom, cat_description = :description WHERE cat_ID = :id";
        $sql = $conn->prepare($request);
        $sql->bindValue(':nom', $newnom, PDO::PARAM_STR);
        $sql->bindValue(':description', $newdescription, PDO::PARAM_STR);
        $sql->bindValue(':id', $categorie_id, PDO::PARAM_INT);
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

        $request = "UPDATE ".DB_TABLE_CATEGORIE." SET cat_is_visible = 0 WHERE cat_ID = :id;";
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

        $sql = $conn -> prepare ("UPDATE ".DB_TABLE_CATEGORIE." SET cat_is_visible = 0 WHERE cat_ID IN($placeholders)");
        try{
            $sql->execute($variables);
            return true;
        }catch(PDOException $e){
            return $this->errmessage.$e->getMessage();
        }
    }

    public function db_soft_delete_all(){
        global $conn;

        $request = "UPDATE ".DB_TABLE_CATEGORIE." SET cat_is_visible = 0";
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