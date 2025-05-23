<?php 

class Produit{

    const errmessage = "Une erreur s'est produite, signalez la à l'administrateur \n";

    public function db_get_all(){
		global $conn;
		$request = "SELECT * FROM ".DB_TABLE_PRODUIT."
					INNER JOIN ".DB_TABLE_CASIER." ON ".DB_TABLE_PRODUIT.".fk_cas_ID = ".DB_TABLE_CASIER.".cas_ID
					WHERE pro_is_visible = 1";

		try{
			$sql = $conn->query($request);
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}catch(PDOException $e){
			return $this->errmessage.$e->getMessage();
		}
    }

	public function db_get_by_id($produit_id=0){
		$produit_id = (int) $produit_id;
		if(!$produit_id){
			return false;
		}

		global $conn;

		$request = "SELECT * FROM ".DB_TABLE_PRODUIT." WHERE pro_ID = :id";
		$sql = $conn->prepare($request);
		$sql->bindValue(':id', $produit_id, PDO::PARAM_INT);
		try{
			$sql->execute();
			return $sql->fetch(PDO::FETCH_ASSOC);
		}catch(PDOException $e){
			return $this->errmessage.$e->getMessage();
		}
	}

	public function db_get_one(){
		global $conn;

		$request = "SELECT pro_ID FROM ".DB_TABLE_PRODUIT." WHERE pro_is_visible = 1 AND pro_ID != 0 LIMIT 1";
		try{
			$sql = $conn->query($request);
			return $sql->fetch(PDO::FETCH_ASSOC)["pro_ID"];
		}catch(PDOException $e){
			return $this->errmessage.$e->getMessage();
		}
	}

	public function db_create($produit_nom='', $produit_commentaire, $fk_casier ,$articles=[], $quantite=[]){
		if(!$produit_nom || !is_array($articles) || !is_array($quantite)){
			return "coucou";
		}

		global $conn;
		global $oCasier;

		$fk_casier_id = (int) $oCasier->db_get_by_lib($fk_casier)["cas_ID"];

		if(!$fk_casier_id){
			$fk_casier_id = 1;
		}

		$request = "INSERT INTO ".DB_TABLE_PRODUIT."(pro_lib, pro_commentaire, fk_cas_ID) VALUES(:nom_produit, :commentaire, :casier_ID)";
        $sql = $conn->prepare($request);
        $sql->bindValue(":nom_produit", $produit_nom, PDO::PARAM_STR);
        $sql->bindValue(":commentaire", $produit_commentaire, PDO::PARAM_STR);
        $sql->bindValue(":casier_ID", $fk_casier_id, PDO::PARAM_INT);
		try{
			$sql->execute();
            $id = $conn->lastInsertId();
            if($sql){
                $request = "INSERT INTO ".DB_TABLE_COMPOSE." VALUES";
                for ($i=0; $i < count($articles); $i++) { 
                    if($i != 0) $request .= ",";
                    $request .= "($id, :article".$i.", :quantite".$i.")";
                }
                $sql = $conn->prepare($request);
                for ($i=0; $i < count($articles); $i++) {
                    $sql->bindValue(":article$i", $articles[$i], PDO::PARAM_INT);
                    $sql->bindValue(":quantite$i", $quantite[$i], PDO::PARAM_INT);
                }
                try{
                    $sql->execute();
                    return $id;
                }catch(PDOException $e){
                    return $this->errmessage.$e->getMessage();
                }
            }else{
                return false;
            }
		}catch(PDOException $e){
			return $this->errmessage.$e->getMessage();
		}
	}

	public function db_update($produit_id=0, $produit_nom='', $produit_commentaire=''){
		//rajouter casier + categorie
		$produit_id = (int) $produit_id;
		if(!$produit_id){
			return false;
		}

		global $conn;
		$request = "UPDATE ".DB_TABLE_PRODUIT." SET pro_lib= :pro_lib, pro_commentaire = :pro_commentaire WHERE pro_ID = :produit_ID";
		$sql = $conn->prepare($request);
		$sql->bindValue(":pro_lib", $produit_nom, PDO::PARAM_STR);
		$sql->bindValue(":pro_commentaire", $produit_commentaire, PDO::PARAM_STR);
		$sql->bindValue(":produit_ID", $produit_id, PDO::PARAM_INT);
		try{
			$sql->execute();
			return true;
		}catch(PDOException $e){
			return $this->errmessage.$e->getMessage();
		}
	}

	public function db_soft_delete_one($produit_id=0){
		$produit_id = (int) $produit_id;

		if(!$produit_id){
			return false;
		}

		global $conn;

		$request = "UPDATE ".DB_TABLE_PRODUIT." SET pro_is_visible = 0 WHERE pro_ID=:produit_id";
		$sql = $conn->prepare($request);
		$sql->bindValue(':produit_id', $produit_id, PDO::PARAM_INT);
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

        $sql = $conn -> prepare ("UPDATE ".DB_TABLE_PRODUIT." SET pro_is_visible = 0 WHERE pro_ID IN($placeholders)");
        try{
            $sql->execute($variables);
            return true;
        }catch(PDOException $e){
            return $this->errmessage.$e->getMessage();
        }
    }

	public function db_soft_delete_all(){
		global $conn;

		$request = "UPDATE ".DB_TABLE_ARTICLE." SET art_is_visible = 0";
		try{
			$conn->query($request);
			return true;
		}catch(PDOException $e){
			return $this->errmessage.$e->getMessage();
		}
	}

}

?>