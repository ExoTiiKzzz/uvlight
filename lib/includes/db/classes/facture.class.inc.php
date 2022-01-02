<?php

class Facture
{
    public function db_get_by_id(int $id) : array{
        global $conn;
        $request = "SELECT * FROM ".DB_TABLE_LIGNES_FACTURE." WHERE fk_doc_ID = :id";
        $sql = $conn->prepare($request);
        $sql->bindValue(":id", $id, PDO::PARAM_INT);
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
        return $response;
    }

    public function db_insert_lignes_facture($articles, $quantitys, $tarif, $comment, $com_ID, $remise) : array{

        $doc_ID = $this->db_create_facture_document($comment, $com_ID);


        if($doc_ID["error"] === true){
            return $doc_ID;
        }

        $doc_ID = $doc_ID["content"];


        $this->db_create_info($doc_ID, $remise, $tarif);

        global $conn, $oCommande;

        $request = "INSERT INTO " . DB_TABLE_LIGNES_FACTURE . "(Lignf_quantite, fk_art_ID, fk_doc_ID) VALUES (0, 0 ,$doc_ID)";
        $i = 0;
        $forbidden = [];
        foreach ($articles as $el) {
            if($this->db_check_max_value($com_ID, $articles[$i], $quantitys[$i])){
                $request .= ",(:quantite$i, :article$i, $doc_ID) ";
            }else{
                $forbidden[$i] = 0;
            }
            $i++;
        }
        if(count($forbidden) === count($articles)){
            $response["error"] = true;
            $response["errortext"] = "Les valeurs rentrées sont trop grandes";
            return $response;
        }
        try {
            $sql = $conn->prepare($request);
            $cpt = 0;
            foreach ($articles as $ar) {
                if(array_key_exists($cpt, $forbidden)){
                    $sql->bindValue(":quantite$cpt", $quantitys[$cpt]);
                    $sql->bindValue(":article$cpt", (int) $articles[$cpt], PDO::PARAM_INT);
                }
                $cpt++;
            }
            $sql->execute();

            $com_ID = $oCommande->db_get_by_doc_ID($doc_ID);
            if($com_ID["error"] === true){
                return $com_ID;
            }
//            $this->db_check_if_finished($com_ID["content"]);

            $response["error"] = false;
            return $response;
        } catch (PDOException $e) {
            $response["error"] = true;
            $response["errortext"] = $e->getMessage();
            return $response;
        }
    }

    private function db_create_facture_document($comment, $com_ID) : array{
        global $conn;
        $request = "INSERT INTO ".DB_TABLE_DOCUMENT."(doc_commentaire, fk_typdo_ID, fk_com_ID) VALUES (:comment, 2, :com_ID)";
        $sql = $conn->prepare($request);
        $sql->bindValue(":comment", $comment);
        $sql->bindValue(":com_ID", $com_ID);
        try{
            $sql->execute();
            if($sql === false){
                $response["error"] = true;
                $response["errortext"] = "Une erreur s'est produite";
                return $response;
            }
             $response["error"] = false;
            $response["content"] = $conn->lastInsertId();
            return $response;
        }catch (PDOException $e){
            $response["error"] = true;
            $response["errortext"] = $e->getMessage();
            return $response;
        }
    }


    private function db_create_info($doc_ID, $remise, $tarif) : void {
        global $conn;
        $request = "INSERT INTO ".DB_TABLE_INFORMATIONS_FACTURE." VALUES (NULL, :remise, :tarif, :doc_ID)";
        $sql = $conn->prepare($request);
        $sql->bindValue(":remise", $remise);
        $sql->bindValue(":tarif", $tarif);
        $sql->bindValue(":doc_ID", $doc_ID);
        $sql->execute();
    }

    public function db_get_lignes_facture($doc_ID) : array{
        global $conn;

        $request = "SELECT * FROM ".DB_TABLE_LIGNES_FACTURE." WHERE fk_doc_ID = :doc_ID AND lignf_quantite != 0";
        $sql = $conn->prepare($request);
        $sql->bindValue(":doc_ID", $doc_ID);
        $sql->execute();
        $result = $sql->fetchAll(PDO::FETCH_ASSOC);
        $response["error"] = false;
        $response["content"] = $result;
        return $response;
    }

//    private function db_check_if_finished(int $com_ID)
//    {
//        $request = "SELECT "
//    }

    private function db_check_max_value($com_ID, $article, $quantite)
    {
        global $conn;
        $request = "SELECT (lcommande.total - facture.total) as total, lcommande.fk_art_ID 
                    FROM 
                         (SELECT SUM(Lign_quantite) as total, fk_art_ID 
                         FROM lignes_commande 
                         WHERE fk_com_ID = :com_id1 AND fk_art_ID = :art_id1
                         GROUP BY fk_art_ID) as lcommande, 
                         (SELECT SUM(Lignf_quantite) as total, fk_art_ID 
                         FROM lignes_facture 
                         WHERE fk_doc_ID IN (SELECT doc_ID 
                                            FROM document 
                                            WHERE fk_com_ID = :com_id2 AND fk_typdo_ID = 2) 
                         AND Lignf_quantite != 0 AND fk_art_ID = :art_id2
                         GROUP BY fk_art_ID) as facture 
                    GROUP BY lcommande.fk_art_ID ";

        $sql = $conn->prepare($request);
        $sql->bindValue(":com_id1", $com_ID);
        $sql->bindValue(":art_id1", $article);
        $sql->bindValue(":com_id2", $com_ID);
        $sql->bindValue(":art_id2", $article);
        $sql->execute();
        if($sql === false){
            return false;
        }else {
            return ((int) $sql->fetch(PDO::FETCH_ASSOC)["total"] - (int) $quantite >= 0);
        }

    }

    /**
     * SELECT SUM(Lignf_quantite) as total, fk_art_ID
    FROM lignes_facture
    WHERE fk_doc_ID IN (SELECT doc_ID
    FROM document
    WHERE fk_com_ID = 93
    AND fk_typdo_ID = 2)
    AND Lignf_quantite != 0
    GROUP BY fk_art_ID;
     */

    /**
     * SELECT SUM(Lign_quantite) as total, fk_art_ID
     * FROM lignes_commande
     * WHERE fk_com_ID = 93
     * GROUP BY fk_art_ID;
     */

}