<?php 

class Article{

    const errmessage = "Une erreur s'est produite, signalez la à l'administrateur \n";

    public function db_get_all(){
		global $conn;
		$request = "SELECT * FROM ".DB_TABLE_ARTICLE." INNER JOIN ".DB_TABLE_CATEGORIE." ON ".DB_TABLE_ARTICLE.".fk_cat_ID = ".DB_TABLE_CATEGORIE.".cat_ID 
					INNER JOIN ".DB_TABLE_CASIER." ON ".DB_TABLE_ARTICLE.".fk_cas_ID = ".DB_TABLE_CASIER.".cas_ID WHERE art_is_visible = 1 AND art_ID != 0 ;";

		try{
			$sql = $conn->query($request);
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}catch(PDOException $e){
			return $this->errmessage.$e->getMessage();
		}
    }

	public function db_get_by_id($article_id=0){
		$article_id = (int) $article_id;
		if(!$article_id){
			return false;
		}

		global $conn;

		$request = "SELECT * FROM ".DB_TABLE_ARTICLE." WHERE art_ID = :id";
		$sql = $conn->prepare($request);
		$sql->bindValue(':id', $article_id, PDO::PARAM_INT);
		try{
			$sql->execute();
			return $sql->fetch(PDO::FETCH_ASSOC);
		}catch(PDOException $e){
			return $this->errmessage.$e->getMessage();
		}
	}

	public function db_get_article_by_produit_id($produit_id=0){
		$produit_id = (int) $produit_id;
		if(!$produit_id){
			return false;
		}
		global $conn;

		$request = "SELECT * FROM ".DB_TABLE_COMPOSE."
					INNER JOIN ".DB_TABLE_ARTICLE." ON ".DB_TABLE_COMPOSE.".art_ID = ".DB_TABLE_ARTICLE.".art_ID 
					INNER JOIN ".DB_TABLE_CASIER." ON ".DB_TABLE_ARTICLE.".fk_cas_ID = ".DB_TABLE_CASIER.".cas_ID
					WHERE ".DB_TABLE_COMPOSE.".pro_ID = :id";
		$sql = $conn->prepare($request);
		$sql->bindValue(':id', $produit_id, PDO::PARAM_INT);
		try{
			$sql->execute();
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}catch(PDOException $e){
			return $this->errmessage.$e->getMessage();
		}
	}

	public function db_create($article_nom='', $article_commentaire='', $fk_categorie_id=0, $fk_casier_id=0){
		$fk_categorie_id = (int) $fk_categorie_id;
		$fk_casier_id = (int) $fk_casier_id;
		if(!$article_nom || !$fk_categorie_id || !$fk_casier_id){
			return false;
		}

		global $conn;
		$request = "INSERT INTO ".DB_TABLE_ARTICLE."(art_nom, art_commentaire, fk_cat_ID, fk_cas_ID) VALUES(:article_nom, :article_commentaire, :fk_categorie_id, :fk_casier_id);";
		$sql = $conn->prepare($request);
		if(!$article_commentaire) $article_commentaire = "0";
		$sql->bindValue(':article_nom', $article_nom, PDO::PARAM_STR);
		$sql->bindValue(':article_commentaire', $article_commentaire, PDO::PARAM_STR);
		$sql->bindValue(':fk_categorie_id', $fk_categorie_id, PDO::PARAM_INT);
		$sql->bindValue(':fk_casier_id', $fk_casier_id, PDO::PARAM_INT);
		try{
			$sql->execute();
			return true;
		}catch(PDOException $e){
			return $this->errmessage.$e->getMessage();
		}
	}

	public function db_update($article_id=0, $article_nom='', $article_commentaire='', $fk_categorie_id=0, $fk_casier_id=0){
		$article_id = (int) $article_id;
		$fk_categorie_id = (int) $fk_categorie_id;
		$fk_casier_id = (int) $fk_casier_id;
		if(!$article_id || !$fk_categorie_id || !$fk_casier_id){
			return false;
		}

		global $conn;
		$request = "UPDATE ".DB_TABLE_ARTICLE." SET art_nom= :art_nom, art_commentaire = :art_commentaire, fk_cat_ID = :fk_cat_ID, fk_cas_ID = :fk_cas_ID WHERE art_ID = :article_ID";
		$sql = $conn->prepare($request);
		$sql->bindValue(":art_nom", $article_nom, PDO::PARAM_STR);
		$sql->bindValue(":art_commentaire", $article_commentaire, PDO::PARAM_STR);
		$sql->bindValue(":fk_cat_ID", $fk_categorie_id, PDO::PARAM_INT);
		$sql->bindValue(":fk_cas_ID", $fk_casier_id, PDO::PARAM_INT);
		$sql->bindValue(":article_ID", $article_id, PDO::PARAM_INT);
		try{
			$sql->execute();
			return true;
		}catch(PDOException $e){
			return $this->errmessage.$e->getMessage();
		}
	}

	public function db_soft_delete_one($article_id=0){
		$article_id = (int) $article_id;

		if(!$article_id){
			return false;
		}

		global $conn;

		$request = "UPDATE ".DB_TABLE_ARTICLE." SET art_is_visible = 0 WHERE art_ID=:article_id";
		$sql = $conn->prepare($request);
		$sql->bindValue(':article_id', $article_id, PDO::PARAM_INT);
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

        $list_id = implode(',', $id_array);

        $request = "UPDATE ".DB_TABLE_ARTICLE." SET art_is_visible = 0 WHERE art_ID IN (:list_id)";
        $sql = $conn->prepare($request);
        $sql->bindValue(':list_id', $list_id, PDO::PARAM_STR);
        try{
            $sql->execute();
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