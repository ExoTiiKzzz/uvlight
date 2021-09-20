<?php 

class Compose{

    const errmessage = "Une erreur s'est produite, signalez la à l'administrateur \n";

    public function db_get_all(){
		global $conn;
		$request = "SELECT * FROM ".DB_TABLE_COMPOSE.";";

		try{
			$sql = $conn->query($request);
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}catch(PDOException $e){
			return $this->errmessage.$e->getMessage();
		}
    }

	public function db_get_by_article_id($article_id=0){
		$article_id = (int) $article_id;

		if(!$article_id){
			return false;
		}

		global $conn;

		$request = "SELECT * FROM ".DB_TABLE_COMPOSE." WHERE art_ID = :article_id";
		$sql = $conn->prepare($request);
		$sql->bindValue(':article_id', $article_id, PDO::PARAM_INT);

		try{
			$sql->execute();
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}catch(PDOException $e){
			return $this->errmessage.$e->getMessage();
		}
	}

	public function db_get_by_produit_id($produit_id=0){
		$produit_id = (int) $produit_id;

		if(!$produit_id){
			return false;
		}

		global $conn;

		$request = "SELECT * FROM ".DB_TABLE_COMPOSE." WHERE pro_ID = :produit_id";
		$sql = $conn->prepare($request);
		$sql->bindValue(':produit_id', $produit_id, PDO::PARAM_INT);

		try{
			$sql->execute();
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}catch(PDOException $e){
			return $this->errmessage.$e->getMessage();
		}
	}

	public function db_create_one($articlesarray=[], $produit_id=0){
		$produit_id = (int) $produit_id;

		if(!$produit_id || !is_array($articlesarray) || empty($articlesarray)){
			return false;
		}

		$verifiedarticles = [];
		foreach ($articlesarray as $key) {
			if(is_object($key)){
				array_push($verifiedarticles, $key);
			}
		}

		if(empty($verifiedarticles)){
			return false;
		}

		global $conn;
		$request = "INSERT INTO ".DB_TABLE_COMPOSE." VALUES";

		foreach ($verifiedarticles as $row) {
			$articleid = (int) $row->get_id();
			$quantite = (int) $row->get_quantite();
			if($articleid){
				$request.=" ($produit_id, $articleid, $quantite ),";
			}
		}

		$request = substr($request, 0, -1);
		try{
			$sql = $conn->query($request);
			return true;
		}catch(PDOException $e){
			return $this->errmessage.$e->getMessage();
		}
	}
}

?>