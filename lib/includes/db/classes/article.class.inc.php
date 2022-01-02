<?php 

class Article{

    private $errmessage = "Une erreur s'est produite, signalez la à l'administrateur \n";

    public function db_get_all(){
		global $conn;
		$request = "SELECT art_ID as arrkey, art_ID, art_nom, art_commentaire, fk_cat_ID, fk_cas_ID FROM ".DB_TABLE_ARTICLE." WHERE art_is_visible = 1 AND art_ID != 0";

		try{
			$sql = $conn->query($request);
			return $sql->fetchAll(\PDO::FETCH_GROUP|\PDO::FETCH_UNIQUE|\PDO::FETCH_ASSOC);
		}catch(PDOException $e){
			return $this->errmessage.$e->getMessage();
		}
    }

    public function db_get_all_by_fournisseur(string $fournisseur) : array{
        global $conn;
        $request = "SELECT art_ID, art_nom FROM ".DB_TABLE_ARTICLE." WHERE fk_tiers_ID = 
                    (SELECT tie_ID FROM ".DB_TABLE_TIERS." WHERE tie_raison_sociale = :fournisseur) 
                    AND art_is_visible = 1 AND art_ID IN (SELECT fk_art_ID FROM ".DB_TABLE_LIGNES_COMMANDE.")";
        try {
            $sql = $conn->prepare($request);
            $sql->bindValue(":fournisseur", $fournisseur, PDO::PARAM_STR);
            if($sql->execute() !== false){
                $result = $sql->fetchAll();
                if(!empty($result)){
                    $response["error"] = false;
                    $response["content"] = $result;
                }else{
                    $response["error"] = true;
                    $response["errortext"] = "Aucun article disponible";
                    $response["code"] = 404;
                }
            }else{
                $response["error"] = true;
                $response["errortext"] = "Une erreur s'est produite, vérifier le nom du fournisseur";
            }
            return $response;
        } catch(PDOException $e){
            $response["error"] = true;
            $response["errortext"] = $e->getMessage();
            return $response;
        }


    }


    /**
     * @param int $article_id Une description
     * @return array
     */
	public function db_get_by_id(int $article_id=0): array
    {
		$article_id = (int) $article_id;
		if(!$article_id){
			return false;
		}

		global $conn, $oTarif, $oCompose;

		$request = "SELECT * FROM ".DB_TABLE_ARTICLE." 
					INNER JOIN ".DB_TABLE_CASIER." ON ".DB_TABLE_ARTICLE.".fk_cas_ID = ".DB_TABLE_CASIER.".cas_ID
					INNER JOIN ".DB_TABLE_CATEGORIE." ON ".DB_TABLE_ARTICLE.".fk_cat_ID = ".DB_TABLE_CATEGORIE.".cat_ID
					INNER JOIN ".DB_TABLE_TIERS." ON ".DB_TABLE_ARTICLE.".fk_tiers_ID = ".DB_TABLE_TIERS.".tie_ID
					WHERE art_ID = :id";
		$sql = $conn->prepare($request);
		$sql->bindValue(':id', $article_id, PDO::PARAM_INT);
		try{
			$sql->execute();
            $result = [];
			$result["content"] = $sql->fetch(PDO::FETCH_ASSOC);
            $tarifs = $oTarif->db_get_grid($article_id);
            if($tarifs["error"] === false){
                $result["content"]["tarifs"] = $tarifs["content"];
            }
            if($result["content"]["art_is_composed"] === 1){
                $result["content"]["articles"] = $oCompose->db_get_by_produit_id($article_id);
            }
            return $result;
		}catch(PDOException $e){
			return $this->errmessage.$e->getMessage();
		}
	}

	public function db_get_one(){
		global $conn;

		$request = "SELECT art_ID FROM ".DB_TABLE_ARTICLE." WHERE art_is_visible = 1 AND art_ID != 0 LIMIT 1";
		try{
			$sql = $conn->query($request);
			return $sql->fetch(PDO::FETCH_ASSOC)["art_ID"];
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





	public function db_create($article_nom='', $article_commentaire='',$fournisseur='', $categorie='', $casier='', $articles = [], $quantitys = []){
		global $conn, $oCasier, $oCategorie, $oTiers, $oTarif;

        $fk_tiers_id = (int) $oTiers->db_get_by_lib($fournisseur)["tie_ID"];
		$fk_categorie_id = (int) $oCategorie->db_get_by_lib($categorie)["cat_ID"];
		$fk_casier_id = (int) $oCasier->db_get_by_lib($casier)["cas_ID"];
		if(!$article_nom || !$fk_categorie_id || !$fk_casier_id || !$fk_tiers_id){
            $response["error"] = true;
            $response["errortext"] = "Données invalide";
			return $response;
		}

		$request = "INSERT INTO ".DB_TABLE_ARTICLE."(art_nom, art_commentaire, fk_tiers_ID, fk_cat_ID, fk_cas_ID, art_is_composed) VALUES(:article_nom, :article_commentaire,:fk_tiers_ID, :fk_categorie_id, :fk_casier_id, :iscomposed);";
		$sql = $conn->prepare($request);
		if(!$article_commentaire) $article_commentaire = "0";
        $iscomposed = 0;
        if(!empty($articles)) $iscomposed = 1;
		try{
			$sql->execute([":article_nom" => $article_nom, ":article_commentaire" => $article_commentaire,":fk_tiers_ID" => $fk_tiers_id, ":fk_categorie_id" => $fk_categorie_id, ":fk_casier_id" => $fk_casier_id, ":iscomposed" => $iscomposed]);
            $id = $conn->lastInsertId();
            if($iscomposed === 1){
                $request = "INSERT INTO ".DB_TABLE_COMPOSE." VALUES (:pro_ID, (SELECT art_ID FROM ".DB_TABLE_ARTICLE." WHERE art_nom = :article LIMIT 1), :quantity)";
                for($i=0; $i<count($articles); $i++){
                    $sql = $conn->prepare($request);
                    $sql->bindValue(":pro_ID", $id, PDO::PARAM_INT);
                    $sql->bindValue(":article", $articles[$i]);
                    $sql->bindValue(":quantity", $quantitys[$i], PDO::PARAM_INT);
                    $sql->execute();
                }
            }
            $conn->prepare("INSERT INTO ".DB_TABLE_LIGNES_COMMANDE."(Lign_quantite, Lign_is_vente, fk_art_ID, fk_com_ID) VALUES(0, 1, $id, 0), (0, 0, $id, 0)")->execute();
            $creategrid = $oTarif->db_create_grid($id);
            if($creategrid["error"] === true){
                $return["error"] = true;
                $return["errortext"] = $creategrid["errortext"];
            }else{
                $return["error"] = false;
            }
			return $return;
		}catch(PDOException $e){
            $return["error"] = true;
            $return["errortext"] = $e->getMessage();
			return $return;
		}
	}

    public function db_update($id,$article_nom='', $article_commentaire='',$fournisseur='', $categorie='', $casier='', $articles = [], $quantitys = []){
        global $conn, $oCasier, $oCategorie, $oTiers, $oTarif;

        $fk_tiers_id = (int) $oTiers->db_get_by_lib($fournisseur)["tie_ID"];
        $fk_categorie_id = (int) $oCategorie->db_get_by_lib($categorie)["cat_ID"];
        $fk_casier_id = (int) $oCasier->db_get_by_lib($casier)["cas_ID"];

        $request = "UPDATE ".DB_TABLE_ARTICLE." SET art_nom = :article_nom, 
                                                art_commentaire = :article_commentaire, 
                                                fk_tiers_ID = :fk_tiers_ID, 
                                                fk_cat_ID = :fk_categorie_id, 
                                                fk_cas_ID = :fk_casier_id, 
                                                art_is_composed = :iscomposed
                                                WHERE art_ID = :art_ID;";
        $sql = $conn->prepare($request);
        if(!$article_commentaire) $article_commentaire = "0";
        $iscomposed = 0;
        if(!empty($articles)) $iscomposed = 1;
        try{
            $bool = false;
            $sql->execute([":article_nom" => $article_nom,
                            ":article_commentaire" => $article_commentaire,
                            ":fk_tiers_ID" => $fk_tiers_id,
                            ":fk_categorie_id" => $fk_categorie_id,
                            ":fk_casier_id" => $fk_casier_id,
                            ":iscomposed" => $iscomposed,
                            ":art_ID" => $id]);
            if($sql === false){
                $bool = true;
                $return["errortext"] = "Problème lors de la modification d'article";
            }
            if($iscomposed === 1){
                $request = "DELETE FROM ".DB_TABLE_COMPOSE." WHERE pro_ID = :pro_ID";
                $sql = $conn->prepare($request);
                $sql->bindValue(":pro_ID", $id, PDO::PARAM_INT);
                $sql->execute();
                if($sql === false){
                    $bool = true;
                    $return["errortext"] = "Problème lors de la suppression des éléments de la composition";
                }
                $request = "INSERT INTO ".DB_TABLE_COMPOSE." VALUES (:pro_ID, (SELECT art_ID FROM ".DB_TABLE_ARTICLE." WHERE art_nom = :article LIMIT 1), :quantity)";
                for($i=0; $i<count($articles); $i++){
                    $sql = $conn->prepare($request);
                    $sql->bindValue(":pro_ID", $id, PDO::PARAM_INT);
                    $sql->bindValue(":article", $articles[$i]);
                    $sql->bindValue(":quantity", $quantitys[$i], PDO::PARAM_INT);
                    $sql->execute();
                    if($sql === false){
                        $bool = true;
                        $return["errortext"] = "Problème lors de l'insertion dans la table compose";
                    }
                }
            }
            $return["error"] = $bool;
            return $return;
        }catch(PDOException $e){
            $return["error"] = true;
            $return["errortext"] = $e->getMessage();
            return $return;
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

        $variables = $id_array;
        $placeholders = str_repeat ('?, ',  count ($variables) - 1) . '?';

        $sql = $conn -> prepare ("UPDATE ".DB_TABLE_ARTICLE." SET art_is_visible = 0 WHERE art_ID IN($placeholders)");
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

    public function db_get_all_by_article_name_arrkey(){
        global $conn;
        $request = "SELECT  art_nom as arrkey, art_ID, art_nom FROM ".DB_TABLE_ARTICLE;
        try{
            $sql = $conn->query($request);
            return $sql->fetchAll(\PDO::FETCH_GROUP|\PDO::FETCH_UNIQUE|\PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            return $this->errmessage.$e->getMessage();
        }
    }

}

?>