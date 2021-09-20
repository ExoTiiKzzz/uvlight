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
}
?>