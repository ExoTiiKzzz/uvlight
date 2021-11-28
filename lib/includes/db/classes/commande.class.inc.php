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

    public function db_create_command($comment, $quantity, $article): array{
        global $conn;

        $listearticle = str_repeat ('?, ',  count ($article) - 1) . '?';


        $request = "SELECT art_ID, art_nom FROM ".DB_TABLE_ARTICLE." WHERE art_nom  IN ($listearticle)";
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

        $conn->query("INSERT INTO ".DB_TABLE_COMMANDE."(fk_etat_ID) VALUES(1)");
        $commandeID = $conn->lastInsertId();
        $commandLines = $this->db_insert_command_lines($commandeID, $article, $quantity);
        if($commandLines["error"] === true){
            $response["error"] = true;
            $response["errortext"] = $commandLines["errortext"];
            $response["content"] = $article;
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

    private function db_create_document(string $comment, int $typeID , int $commandeID ): array{
        global $conn;
        $request = "INSERT INTO ".DB_TABLE_DOCUMENT."(doc_commentaire, fk_typdo_ID, fk_com_ID) VALUES(:comment, :typeID, :commandeID)";
        $sql = $conn->prepare($request);
        $sql->bindValue(":comment", $comment);
        $sql->bindValue(":typeID", $typeID, PDO::PARAM_INT);
        $sql->bindValue(":commandeID", $commandeID, PDO::PARAM_INT);
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

    private function db_insert_command_lines(int $commandeID, array $article, array $quantity): array{
        global $conn;
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

//    public function get_documents(int $id){
//        global $conn;
//        $request = "SELECT "
//    }
}