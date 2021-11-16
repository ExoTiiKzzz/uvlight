<?php 

class Article{

    const errmessage = "Une erreur s'est produite, signalez la à l'administrateur \n";


    public function db_get_all(){
		global $conn;
		$request = "SELECT art_ID, art_nom, art_commentaire, fk_cat_ID, fk_cas_ID FROM ".DB_TABLE_ARTICLE." WHERE art_is_visible = 1";

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

		$request = "SELECT * FROM ".DB_TABLE_ARTICLE." 
					INNER JOIN ".DB_TABLE_CASIER." ON ".DB_TABLE_ARTICLE.".fk_cas_ID = ".DB_TABLE_CASIER.".cas_ID
					INNER JOIN ".DB_TABLE_CATEGORIE." ON ".DB_TABLE_ARTICLE.".fk_cat_ID = ".DB_TABLE_CATEGORIE.".cat_ID
					WHERE art_ID = :id";
		$sql = $conn->prepare($request);
		$sql->bindValue(':id', $article_id, PDO::PARAM_INT);
		try{
			$sql->execute();
			return $sql->fetch(PDO::FETCH_ASSOC);
		}catch(PDOException $e){
			return $this->errmessage.$e->getMessage();
		}
	}

    public function db_get_liste_articles($data){

        $list = "<datalist id='articles'>";
        foreach ($data as $key) {
            $list .= "<option value='".$key["art_nom"]."'>";
        }
        $list .="</datalist>";

        return $list;

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

    public function db_create_command($article, $quantity){

        if(empty($article) || empty($quantity)){
            $response["error"] = true;
            $response["errortext"] = is_array($article);
            return $response;
        }


        global $conn;

        $listearticle = str_repeat ('?, ',  count ($article) - 1) . '?';


        $request = "SELECT art_ID FROM ".DB_TABLE_ARTICLE." WHERE art_nom  IN ($listearticle)";
        $result = $conn->prepare($request);
        $result->execute($article);

        if(!$result){
            $response["error"] = true;
            $response["errortext"] = "Article invalide";
            return $response;
        }
        $resarticles = $result->fetchAll(PDO::FETCH_ASSOC);

        for($cpt = 0 ; $cpt >= count($article); $cpt++){
            $article[$cpt] = $resarticles[$cpt]["art_ID"];
        }

        $conn->query("INSERT INTO ".DB_TABLE_COMMANDE."(fk_doc_ID) VALUES(2)");

        $commandeID = $conn->lastInsertId();


        $request = "INSERT INTO ".DB_TABLE_LIGNES_COMMANDE."(Lign_quantite, Lign_is_vente, fk_art_ID, fk_com_ID) VALUES";

        $i = 0;
        foreach($article as $el){
            $request .= "(:quantite$i, 0, :article$i, $commandeID), ";
            $i++;
        }
        $request .= "(0,0,0,0)";
        try {
            $sql = $conn->prepare($request);
            $cpt = 0;
            foreach($article as $ar){
                $sql->bindValue(":quantite$cpt", $quantity[$cpt]);
                $sql->bindValue(":article$cpt", $article[$cpt]);
                $cpt++;
            }
            $sql->execute();
//            $request = "SELECT COUNT(*) as total FROM ".DB_TABLE_LIGNES_COMMANDE." WHERE fk_art_ID = :article AND Lign_is_vente = 1";
//            $sql = $conn->prepare($request);
//            $sql->execute([":article" => $article]);
//            if($sql->fetch(PDO::FETCH_ASSOC)["total"] === 0){
//                $request = "INSERT INTO ".DB_TABLE_LIGNES_COMMANDE."(Lign_quantite, Lign_is_vente, fk_art_ID, fk_com_ID) VALUES(0, 1, :article, $commandeID)";
//                $conn->prepare($request)->execute([":article"=>$article]);
//            }
            $response["errortext"] = $result->fetch(PDO::FETCH_ASSOC)["art_ID"];
            $response["error"] = true;
            return $response;
        }catch (PDOException $e){
            $response["error"] = true;
            $response["errortext"] = $this->errmessage.$e->getMessage();
            return $response;
        }


    }

	public function db_create($article_nom='', $article_commentaire='', $categorie='', $casier=''){
		global $conn, $oCasier, $oCategorie;

		$fk_categorie_id = (int) $oCategorie->db_get_by_lib($categorie)["cat_ID"];
		$fk_casier_id = (int) $oCasier->db_get_by_lib($casier)["cas_ID"];
		if(!$article_nom || !$fk_categorie_id || !$fk_casier_id){
			return false;
		}
		$request = "INSERT INTO ".DB_TABLE_ARTICLE."(art_nom, art_commentaire, fk_cat_ID, fk_cas_ID) VALUES(:article_nom, :article_commentaire, :fk_categorie_id, :fk_casier_id);";
		$sql = $conn->prepare($request);
		if(!$article_commentaire) $article_commentaire = "0";
		$sql->bindValue(':article_nom', $article_nom, PDO::PARAM_STR);
		$sql->bindValue(':article_commentaire', $article_commentaire, PDO::PARAM_STR);
		$sql->bindValue(':fk_categorie_id', $fk_categorie_id, PDO::PARAM_INT);
		$sql->bindValue(':fk_casier_id', $fk_casier_id, PDO::PARAM_INT);
		try{
			$sql->execute();
			$return = [];
			$return["lastid"] = $conn->lastInsertId();
			$return["cat"] = $oCategorie->db_get_by_id((int) $fk_categorie_id)["cat_nom"];
			$return["cas"] = $oCasier->db_get_by_id((int) $fk_casier_id)["cas_lib"];
			return $return;
		}catch(PDOException $e){
			return $this->errmessage.$e->getMessage();
		}
	}

	public function db_update($article_id=0, $article_nom='', $article_commentaire='', $categorie=0, $casier=0){
		$article_id = (int) $article_id;
		global $conn, $oCasier, $oCategorie;
		$fk_categorie_id = (int) $oCategorie->db_get_by_lib($categorie)["cat_ID"];
		$fk_casier_id = (int) $oCasier->db_get_by_lib($casier)["cas_ID"];

		if(!$article_id || !$fk_categorie_id || !$fk_casier_id){
			return false;
		}

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

}

?>