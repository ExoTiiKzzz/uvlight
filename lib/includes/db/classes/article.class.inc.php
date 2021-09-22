<?php 

class Article{

    const errmessage = "Une erreur s'est produite, signalez la à l'administrateur \n";

    public function db_get_all(){
		global $conn;
		$request = "SELECT * FROM ".DB_TABLE_ARTICLE.";";

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

	public function db_create($article_nom='', $article_commentaire='', $fk_categorie_id=0){
		$fk_categorie_id = (int) $fk_categorie_id;

		if(!$article_nom || $fk_categorie_id){
			return false;
		}

		global $conn;
		$request = "INSERT INTO ".DB_TABLE_ARTICLE."(art_nom, art_commentaire, fk_cat_ID) VALUES(:article_nom, :article_commentaire, :fk_categorie_id);";
		$sql = $conn->prepare($request);
		if(!$article_commentaire) $article_commentaire = "0";
		$sql->bindValue(':article_nom', $article_nom, PDO::PARAM_STR);
		$sql->bindValue(':article_commentaire', $article_commentaire, PDO::PARAM_STR);
		$sql->bindValue(':fk_categorie_id', $fk_categorie_id, PDO::PARAM_INT);
		try{
			$sql->execute();
			return true;
		}catch(PDOException $e){
			return $this->errmessage.$e->getMessage();
		}
	}

	public function db_update($article_id=0, $new_article_nom='', $new_article_commentaire='', $new_fk_categorie_id=0){
		$article_id = (int) $article_id;
		if(!$article_id){
			return false;
		}

		global $conn;
		$request = "UPDATE ".DB_TABLE_ARTICLE." SET ";

		if($new_article_nom){
			$request .= "art_nom = :new_article_nom ,";
		}
		if($new_article_commentaire){
			$request .= "art_commentaire = :new_article_commentaire ,";
		}
		if($new_fk_categorie_id){
			$request .= "fk_cat_ID = :new_fk_categorie_id";
		}
		$request .= " WHERE art_ID=:article_id";
		$sql = $conn->prepare($request);

		if($new_article_nom){
			$sql->bindValue(':new_article_nom', $new_article_nom, PDO::PARAM_STR);
		}
		if($new_article_commentaire){
			$sql->bindValue(':new_article_commentaire', $new_article_commentaire, PDO::PARAM_STR);
		}
		if($new_fk_categorie_id){
			$sql->bindValue(':new_fk_categorie_id', $new_fk_categorie_id, PDO::PARAM_INT);
		}
		$sql->bindValue(':article_id', $article_id, PDO::PARAM_INT);
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