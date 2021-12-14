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


}