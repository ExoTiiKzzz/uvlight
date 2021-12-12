<?php

class Tarif{

    const errmessage = "Une erreur s'est produite, signalez la à l'administrateur \n";

    public function db_get_all(){
        global $conn;
        $request = "SELECT * FROM ".DB_TABLE_TARIF.";";

        try{
            $sql = $conn->query($request);
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            return $this->errmessage.$e->getMessage();
        }
    }

    public function db_get_by_id($tarif_id=0){
        $tarif_id = (int) $tarif_id;
        if(!$tarif_id){
            return false;
        }

        global $conn;

        $request = "SELECT * FROM ".DB_TABLE_TARIF." WHERE tar_ID = :id";
        $sql = $conn->prepare($request);
        $sql->bindValue(':id', $tarif_id, PDO::PARAM_INT);

        try{
            $sql->execute();
            return $sql->fetch(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            return $this->errmessage.$e->getMessage();
        }
    }

    public function db_create($libelle=''){

        if(!$libelle){
            return false;
        }

        global $conn;
        $request = "INSERT INTO ".DB_TABLE_TARIF." (tar_lib) VALUES(:libelle);";
        $sql = $conn->prepare($request);
        $sql->bindValue(':libelle', $libelle, PDO::PARAM_STR);

        try{
            $sql->execute();
            return true;
        }catch(PDOException $e){
            return $this->errmessage.$e->getMessage();
        }
    }

    public function db_update_lib($tarif_id=0, $newlib=''){
        $tarif_id = (int) $tarif_id;
        if(!$tarif_id || !$newlib){
            return false;
        }

        global $conn;

        $request = "UPDATE ".DB_TABLE_TARIF." SET tar_lib = :libelle WHERE tar_ID = :id";
        $sql = $conn->prepare($request);
        $sql->bindValue(':libelle', $newlib, PDO::PARAM_STR);
        $sql->bindValue(':id', $tarif_id, PDO::PARAM_INT);
        try{
            $sql->execute();
            return true;
        }catch(PDOException $e){
            return $this->errmessage.$e->getMessage();
        }
    }

    public function db_soft_delete_one($tarif_id=0){
        $tarif_id = (int) $tarif_id;

        if(!$tarif_id) {
            return false;
        }

        global $conn;

        $request = "UPDATE ".DB_TABLE_TARIF." SET tar_is_visible = 0 WHERE tar_ID = :id;";
        $sql = $conn->prepare($request);
        $sql->bindValue(':id', $tarif_id, PDO::PARAM_INT);
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

        $request = "UPDATE ".DB_TABLE_TARIF." SET tar_is_visible = 0 WHERE tar_ID IN (:list_id)";
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

        $request = "UPDATE ".DB_TABLE_TARIF." SET tar_is_visible = 0";
        $sql = $conn->prepare($request);
        try{
            $sql->execute();
            return true;
        }catch(PDOException $e){
            return $this->errmessage.$e->getMessage();
        }
    }

    public function db_create_grid(int $art_ID): array{
        global $conn;

        $request = "INSERT INTO ".DB_TABLE_PAYE."(tar_ID, art_ID) VALUES (0 ,:art)";
        for($i = 1; $i<=20; $i++){
            $request.= ",($i, :art$i)";
        }
        $sql = $conn->prepare($request);
        $sql->bindValue(":art", $art_ID);
        for($i = 1; $i<=20; $i++){
            $sql->bindValue(":art$i", $art_ID);
        }
        try {
            $sql->execute();
            if($sql === false){
                $response["error"] = true;
                $response["errortext"] = "Une erreur s'est produite lors de la création des tarifs";
            }else{
                $response["error"] = false;
            }
        }catch (PDOException $e){
            $response["error"] = true;
            $response["errortext"] = $e->getMessage();
        }
        return  $response;
    }

    public function db_get_grid(int $art_ID): array{
        global $conn;

        $request = "SELECT tar_ID, pay_tarif_vente as prix  FROM ".DB_TABLE_PAYE." WHERE art_ID = :art_id AND tar_ID != 0";
        $sql = $conn->prepare($request);
        $sql->bindValue(":art_id", $art_ID);
        try {
            $sql->execute();
            if($sql === false){
                $response["error"] = true;
                $response["errortext"] = "Une erreur s'est produite lors de la création des tarifs";
            }else{
                $response["error"] = false;
                $response["content"] = $sql->fetchAll();
            }
        }catch (PDOException $e){
            $response["error"] = true;
            $response["errortext"] = $e->getMessage();
        }
        return  $response;
    }

    public function db_update_grid(int $tar_ID, int $art_ID, float $prix): array{
        global $conn;

        $request = "UPDATE ".DB_TABLE_PAYE." SET pay_tarif_vente = :prix WHERE tar_ID = :tar_ID AND art_ID = :art_ID";
        $sql = $conn->prepare($request);
        $sql->bindValue(":prix", $prix);
        $sql->bindValue(":tar_ID", $tar_ID, PDO::PARAM_INT);
        $sql->bindValue(":art_ID", $art_ID, PDO::PARAM_INT);
        try {
            $sql->execute();
            if($sql === false){
                $response["error"] = true;
                $response["errortext"] = "Une erreur s'est produite lors de la création des tarifs";
            }else{
                $response["error"] = false;
            }
        }catch (PDOException $e){
            $response["error"] = true;
            $response["errortext"] = $e->getMessage();
        }
        return  $response;
    }
}
?>