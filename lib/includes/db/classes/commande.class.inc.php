<?php

class Commande
{
    const ETAT = [
        1 => "En cours...",
        2 => "Terminé",
        3 => "Annulé"
    ];

    const TYPE_DOCUMENT = [
        1 => "Devis",
        2 => "Facture",
        3 => "Bon de commande",
        4 => "Bon de livraison"
    ];

    public function db_get_by_id(int $id) : array{
        global $conn;
        $request = "SELECT fk_etat_ID FROM ".DB_TABLE_COMMANDE." WHERE Com_ID = :id";
        $sql = $conn->prepare($request);
        $sql->bindValue(":id", $id, PDO::PARAM_INT);
        $sql->execute();
        $data = $sql->fetch(PDO::FETCH_ASSOC);
        $return = "";
        foreach(SELF::ETAT as $k => $v){
            $selected = "";
            if($k === (int) $data["fk_etat_ID"]) $selected = "selected";
            $return .= "<option value='$k' $selected>$v</option>";
        }

        $response["error"] = false;
        $response["content"] = $return;
        return $response;
    }

    public function db_update(int $id, int $etat): array{
        global $conn;
        $request = "UPDATE ".DB_TABLE_COMMANDE." SET fk_etat_ID = :etat WHERE Com_ID = :id";
        $sql = $conn->prepare($request);
        $sql->bindValue(":id", $id, PDO::PARAM_INT);
        $sql->bindValue(":etat", $etat, PDO::PARAM_INT);
        try{
            $sql->execute();
            $result["error"] = $sql;
            if(!$sql){
                $result["errortext"] = "Une erreur s'est produite.";
            }
        }catch (PDOException $e){
            $result["error"] = true;
            $result["errortext"] = $e;
        }

        return $result;
    }

    public function db_command($comment, $quantity, $article, $tiers, $type=0): array{
        global $conn;

        $listearticle = str_repeat ('?, ',  count ($article) - 1) . '?';


        $request = "SELECT art_ID, art_nom, fk_tiers_ID FROM ".DB_TABLE_ARTICLE." WHERE art_nom  IN ($listearticle)";
        $result = $conn->prepare($request);
        $result->execute($article);

        if(!$result){
            $response["error"] = true;
            $response["errortext"] = "Article invalide";
            return $response;
        }
        $resarticles = $result->fetchAll(PDO::FETCH_ASSOC);

        foreach($resarticles as $element){
            $cpt = 0;
            foreach($article as $subel){
                if($subel === $element["art_nom"]) {
                    $article[$cpt] = $element["art_ID"];
                }
                $cpt++;
            }
        }

        $request = "SELECT tie_ID FROM ".DB_TABLE_TIERS." WHERE tie_raison_sociale =:tiers";
        $sql = $conn->prepare($request);
        $sql->bindValue(":tiers", $tiers);
        $sql->execute();
        $res = (int) $sql->fetch()["tie_ID"];

        $type = (int) $type;
        $conn->query("INSERT INTO ".DB_TABLE_COMMANDE."(fk_etat_ID, fk_tiers_ID, com_is_client) VALUES(1, $res, $type)");
        $commandeID = $conn->lastInsertId();
        $commandLines = $this->db_insert_command_lines($commandeID, $article, $quantity, $type);
        if($commandLines["error"] === true){
            $response["error"] = true;
            $response["errortext"] = $commandLines["errortext"];
            return $response;
            die;
        }
        $createDoc = $this->db_create_document($comment, 3, $commandeID);
        if($createDoc["error"] === true){
            $response["error"] = true;
            $response["errortext"] = $createDoc["errortext"];

            return $response;
            die;
        }

        $response["error"] = false;

        return $response;

    }

    public function db_create_command($comment, $quantity, $article, $tiers): array{
        return $this->db_command($comment, $quantity, $article, $tiers);
    }

    public function db_create_sale($comment, $quantity, $article, $tiers): array{
        return $this->db_command($comment, $quantity, $article, $tiers, 1);
    }

    private function db_create_document(string $comment, int $typeID , int $commandeID): array{
        global $conn;
        $request = "INSERT INTO ".DB_TABLE_DOCUMENT."(doc_commentaire, fk_typdo_ID, fk_com_ID) VALUES(:comment, :type_ID, :commande_ID)";
        $sql = $conn->prepare($request);
        $sql->bindValue(":comment", $comment);
        $sql->bindValue(":type_ID", $typeID, PDO::PARAM_INT);
        $sql->bindValue(":commande_ID", $commandeID, PDO::PARAM_INT);
        try {
            $sql->execute();
            if($sql){
                $response["error"] = false;
            }else{
                $response["error"] = true;
                $response["errortext"] = "Une erreure s'est produite lors de l'insertion";
            }
        }catch (PDOException $e){$response["error"] = true;
            $response["errortext"] = $e->getMessage();
        }
        return $response;
    }

    private function db_insert_command_lines(int $commandeID, array $article, array $quantity, int $type = 0): array{
        global $conn;
        $request = "INSERT INTO ".DB_TABLE_LIGNES_COMMANDE."(Lign_quantite, Lign_is_vente, fk_art_ID, fk_com_ID) VALUES";

        $i = 0;
        foreach($article as $el){
            $request .= "(:quantite$i, $type, :article$i, $commandeID), ";
            $i++;
        }
        $request .= "(0,0,0,0)";
        try {
            $sql = $conn->prepare($request);
            $cpt = 0;
            foreach($article as $ar){
                $sql->bindValue(":quantite$cpt", $quantity[$cpt], PDO::PARAM_INT);
                $sql->bindValue(":article$cpt", $article[$cpt]);
                $cpt++;
            }
            $sql->execute();
            $response["error"] = false;
            return $response;
        }catch (PDOException $e){
            $response["error"] = true;
            $response["errortext"] = $e->getMessage();
            return $response;
        }
    }

    public function db_get_documents(int $id) : array{
        global $conn;
        $request = "SELECT * FROM ".DB_TABLE_DOCUMENT." WHERE fk_com_ID = :id";
        try {
            $sql = $conn->prepare($request);
            $sql->bindValue(":id", $id, PDO::PARAM_INT);
            $sql->execute();
            if(!$sql){
                $response["error"] = true;
                $response["errortext"] = "Aucune donnée n'existe";
                return $response;
            }
            $res = $sql->fetchAll(PDO::FETCH_ASSOC);
            $response["error"] = false;
            $response["data"] = $res;
            $response["types"] = SELF::TYPE_DOCUMENT;
            return $response;
        }catch (PDOException $e){
            $response["error"] = true;
            $response["errortext"] = $e->getMessage();
            return $response;
        }

    }

    public function db_get_detailed_all_documents(int $id): array{
        global $conn;
        foreach(SELF::TYPE_DOCUMENT as $type => $value){
            $response["content"][$value] = $this->db_get_document_by_type($type, $id);
            if($response["content"][$value]["error"] === true){
                return $response["content"][$value];
            }
        }
        $response["error"] = false;
        return $response;
    }

    public function get_lignes_livraison(int $doc_ID): array{
        global $conn;
        $request = "SELECT Lignr_quantite, art_nom FROM ".DB_TABLE_LIGNES_RECEPTION." 
                    INNER JOIN ".DB_TABLE_ARTICLE." ON fk_art_ID = art_ID
                    WHERE fk_doc_ID = :doc_id";
        $sql = $conn->prepare($request);
        $sql->bindValue(':doc_id', $doc_ID, PDO::PARAM_INT);
        try {
            $sql->execute();
            if($sql){
                $response["error"] = false;
                $response["data"] = $sql->fetchAll();
            }else{
                $response["error"] = true;
                $response["errortext"] = "Une erreur s'est produite";
            }
        }catch (PDOException $e){
            $response["error"] = true;
            $response["errortext"] = $e->getMessage();
        }
        return $response;

    }

    public function db_get_all_articles(int $com_ID): array{
        global $conn;

        $docs = $this->db_get_document_by_type(4, $com_ID);
        if($docs["error"] === true){
            $response["error"] = true;
            $response["errortext"] = $docs["errortext"];
        }else{
            $request = "SELECT SUM(Lignr_quantite) as total, art_nom, art_ID, art_taxe
                    FROM ".DB_TABLE_LIGNES_RECEPTION." LR
                    INNER JOIN ".DB_TABLE_ARTICLE." A ON LR.fk_art_ID = A.art_ID
                    WHERE fk_doc_ID IN(";
            foreach ($docs["data"] as $doc){
                $request.= (int) $doc["doc_ID"].", ";
            }
            $request.= "0) GROUP BY art_ID";
            $sql = $conn->prepare($request);
            try {
                $sql->execute();
                if($sql === false){
                    $response["error"] = true;
                    $response["errortext"] = "Une erreure s'est produite";
                }else{
                    $response["error"] = false;
                    $response["content"] = $sql->fetchAll();
                }
            }catch (PDOException $e){
                $response["error"] = true;
                $response["errortext"] = $e->getMessage();
            }
        }
        return $response;

    }


    private function db_get_document_by_type(int $type, int $com_ID) : array{
        global $conn;
        $request = "SELECT * FROM ".DB_TABLE_DOCUMENT." WHERE fk_typdo_ID = :type AND fk_com_ID = :com_ID";
        try {
            $sql = $conn->prepare($request);
            $sql->bindValue(":type", $type, PDO::PARAM_INT);
            $sql->bindValue(":com_ID", $com_ID, PDO::PARAM_INT);
            $sql->execute();
            if(!$sql){
                $response["error"] = true;
                $response["errortext"] = "Une erreur s'est produite";
                return $response;
            }
            $result = $sql->fetchAll();
            $response["error"] = false;
            $response["data"] = $result;
            return $response;
        }catch (PDOException $e){
            $response["error"] = true;
            $response["errortext"] = $e->getMessage();
            return $response;
        }

    }

    public function db_get_lignes_commande(int $com_ID) : array {
        global $conn;
        $request = "SELECT *, SUM(Lign_quantite - Lign_received_quantity) as difference 
                    FROM ".DB_TABLE_LIGNES_COMMANDE."
                    WHERE fk_com_ID = :com_ID GROUP BY fk_art_ID";
        try {
            $sql = $conn->prepare($request);
            $sql->bindValue(":com_ID", $com_ID, PDO::PARAM_INT);
            $sql->execute();
            if(!$sql){
                $response["error"] = true;
                $response["errortext"] = "Une erreur s'est produite";
                return $response;
            }
            $result = $sql->fetchAll();
            $response["error"] = false;
            $response["data"] = $result;
            return $response;
        }catch (PDOException $e){
            $response["error"] = true;
            $response["errortext"] = $e->getMessage();
            return $response;
        }
    }

    public function updateCommand(int $com_ID, array $lignes) : array {

    }

    public function db_get_document_by_id(int $id) : array
    {
        global $conn;
        $request = "SELECT * FROM ".DB_TABLE_DOCUMENT." WHERE doc_ID = :doc_ID";
        $sql = $conn->prepare($request);
        $sql->bindParam(":doc_ID",$id);
        try{
            $sql->execute();
            $res = $sql->fetch(PDO::FETCH_ASSOC);
            if($res !== false){
                $response["error"] = false;
                $response["content"] = $res;
                return $response;
            }
            $response["error"] = true;
            $response["errortext"] = "Pas de données";
            return $response;
        }catch (PDOException $e){
            $response["error"] = true;
            $response["errortext"] = $e->getMessage();
            return $response;
        }
    }

    private function updateLigne(int $com_ID, int $article_ID, int $quantite) : array {
        global $conn;
        $request = "UPDATE ".DB_TABLE_LIGNES_COMMANDE." 
                    SET Lign_received_quantity = Lign_received_quantity + :quantite
                    WHERE fk_art_ID = :article_ID AND fk_com_ID = :command_ID";
        try {
            $sql = $conn->prepare($request);
            $sql->bindValue(":quantite", $quantite, PDO::PARAM_INT);
            $sql->bindValue(":article_ID", $article_ID, PDO::PARAM_INT);
            $sql->bindValue(":command_ID", $com_ID, PDO::PARAM_INT);
            $sql->execute();
            if(!$sql){
                $response["error"] = true;
                $response["errortext"] = "Une erreur s'est produite";
                return $response;
            }
            $result = $sql->fetchAll();
            $response["error"] = false;
            $response["data"] = $result;
            return $response;
        }catch (PDOException $e){
            $response["error"] = true;
            $response["errortext"] = $e->getMessage();
            return $response;
        }
    }

    public function db_get_tiers(int $com_ID) : array{
        global $conn;
        $request = "SELECT tie_raison_sociale, tie_ID, fk_tar_ID FROM ".DB_TABLE_TIERS."
                    INNER JOIN ".DB_TABLE_COMMANDE." ON tie_ID = fk_tiers_ID
                    WHERE ".DB_TABLE_COMMANDE.".com_ID = :com_ID";
        try {
            $sql = $conn->prepare($request);
            $sql->bindValue(":com_ID", $com_ID, PDO::PARAM_INT);
            $sql->execute();
            if(!$sql){
                $response["error"] = true;
                $response["errortext"] = "Une erreur s'est produite";
                return $response;
            }
            $result = $sql->fetch();
            $response["error"] = false;
            $response["data"] = $result;
            return $response;
        }catch (PDOException $e){
            $response["error"] = true;
            $response["errortext"] = $e->getMessage();
            return $response;
        }
    }

    public function db_is_command_received(int $com_ID) : bool {
        $lignes = $this->db_get_lignes_commande($com_ID);
        $bool = true;
        foreach($lignes["data"] as $ligne){
            if($ligne["Lign_is_received"] !== 1){
                $bool = false;
            }
        }

        return $bool;
    }

    public function db_update_lignes_commande(array $data, int $com_ID, string $commentaire) : array{
        global $conn, $oEtatDocument;
        $bool = false;
        $request = "INSERT INTO ".DB_TABLE_DOCUMENT."(doc_commentaire, fk_typdo_ID, fk_com_ID) VALUES (:comment, 4,:com_ID)";

        $sql = $conn->prepare($request);
        $sql->bindValue(":com_ID", $com_ID, PDO::PARAM_INT);
        $sql->bindValue(":comment", $commentaire);
        $sql->execute();
        $id = $conn->lastInsertId();
        $request = "UPDATE ".DB_TABLE_LIGNES_COMMANDE." 
                    SET Lign_received_quantity = Lign_received_quantity + :quantity
                    WHERE fk_com_ID = :com_ID AND fk_art_ID = :art_ID";
        foreach($data as $ligne){
            if($ligne[1] != 0){
                try {
                    $sql = $conn->prepare($request);
                    $sql->bindValue(":quantity", $ligne[1], PDO::PARAM_INT);
                    $sql->bindValue(":com_ID", $com_ID, PDO::PARAM_INT);
                    $sql->bindValue(":art_ID", $ligne[0], PDO::PARAM_INT);
                    if($sql->execute() === false){
                        $response["error"] = true;
                        $response["errortext"] = "Une erreur s'est produite";
                        return $response;
                    }
                } catch(PDOException $e){
                    $response["error"] = true;
                    $response["errortext"] = $e->getMessage();
                    return $response;
                }
            }
        }
        if($this->check_if_complete($com_ID)) $this->finish_command($com_ID);
        $request = "INSERT INTO ".DB_TABLE_LIGNES_RECEPTION."(Lignr_quantite, fk_art_ID, fk_doc_ID) 
                    VALUES(:quantity, :art_ID, :doc_ID)";
        $bool = false;
        foreach($data as $ligne){
            try {
                $sql = $conn->prepare($request);
                $sql->bindValue(":quantity", $ligne[1], PDO::PARAM_INT);
                $sql->bindValue(":art_ID", $ligne[0], PDO::PARAM_INT);
                $sql->bindValue(":doc_ID", $id, PDO::PARAM_INT);
                if($sql->execute() === false){
                    $bool = true;
                    $response["errortext"] = "Une erreur s'est produite";
                }
            } catch(PDOException $e){
                $bool = true;
                $response["errortext"] = $e->getMessage();
            }
        }
        $response["error"] = $bool;
        return $response;
    }

    public function db_get_by_doc_ID($doc_ID) : array{
        global $conn;
        $request = "SELECT fk_com_ID FROM ".DB_TABLE_DOCUMENT." WHERE doc_ID = :doc_ID";
        $sql = $conn->prepare($request);
        $sql->bindValue(":doc_ID", $doc_ID);
        try{
            $sql->execute();
            $res = $sql->fetch(PDO::FETCH_ASSOC);
            if($res){
                $response["error"] = false;
                $response["content"] = $res["fk_com_ID"];
                return $response;
            }
            $response["error"] = true;
            $response["errortext"] = "Aucun document valide";
            return $response;

        }catch(PDOException $e){
            $response["error"] = true;
            $response["errortext"] = $e->getMessage();
            return $response;
        }
    }

    public function check_if_complete($com_ID){
        global $conn;
        $request = "SELECT SUM(Lign_quantite - Lign_received_quantity) as difference 
                      FROM ".DB_TABLE_LIGNES_COMMANDE."
                      WHERE fk_com_ID = :com_ID 
                      GROUP BY fk_art_ID";
        $sql = $conn->prepare($request);
        $sql->bindValue(":com_ID", $com_ID, PDO::PARAM_INT);
        try{
            $sql->execute();
            $res = $sql->fetchAll(PDO::FETCH_ASSOC);
            if($res){
                $bool = true;
                foreach($res as $ligne){
                    if($ligne["difference"] > 0){
                        $bool = false;
                    }
                }
                return $bool;
            }
            return false;

        }catch(PDOException $e){
            return false;
        }
    }

    public function finish_command($com_ID){
        global $conn;
        $request = "UPDATE ".DB_TABLE_COMMANDE." SET fk_etat_ID = 2 WHERE com_ID = :com_ID";
        $sql = $conn->prepare($request);
        $sql->bindValue(":com_ID", $com_ID, PDO::PARAM_INT);
        $sql->execute();
    }


}