<?php 

class Etat_Document{

    const errmessage = "Une erreur s'est produite, signalez la à l'administrateur \n";

    public function db_get_all(){
        global $conn;
        $request = "SELECT * FROM ".DB_TABLE_ETAT_DOCUMENT.";";

        try{
            $sql = $conn->query($request);
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            return $this->errmessage.$e->getMessage();
        }
    }

    public function db_get_by_id($etatdoc_id=0){
        $etatdoc_id = (int) $etatdoc_id;
        if(!$etatdoc_id){
            return false;
        }

        global $conn;

        $request = "SELECT * FROM ".DB_TABLE_ETAT_DOCUMENT." WHERE eta_ID = :id";
        $sql = $conn->prepare($request);
        $sql->bindValue(':id', $etatdoc_id, PDO::PARAM_INT);

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
        $request = "INSERT INTO ".DB_TABLE_ETAT_DOCUMENT." (eta_lib) VALUES(:libelle);";
        $sql = $conn->prepare($request);
        $sql->bindValue(':libelle', $libelle, PDO::PARAM_STR);

        try{
            $sql->execute();
            return true;
        }catch(PDOException $e){
            return $this->errmessage.$e->getMessage();
        }
    }

    public function db_update_lib($etatdoc_id=0, $newlib=''){
        $etatdoc_id = (int) $etatdoc_id;
        if(!$etatdoc_id || !$newlib){
            return false;
        }

        global $conn;

        $request = "UPDATE ".DB_TABLE_ETAT_DOCUMENT." SET eta_lib = :libelle WHERE eta_ID = :id";
        $sql = $conn->prepare($request);
        $sql->bindValue(':libelle', $newlib, PDO::PARAM_STR);
        $sql->bindValue(':id', $etatdoc_id, PDO::PARAM_INT);
        try{
            $sql->execute();
            return true;
        }catch(PDOException $e){
            return $this->errmessage.$e->getMessage();
        }
    }

    public function db_soft_delete_one($etatdoc_id=0){
        $etatdoc_id = (int) $etatdoc_id;

        if(!$etatdoc_id) {
            return false;
        }

        global $conn;

        $request = "UPDATE ".DB_TABLE_ETAT_DOCUMENT." SET eta_is_visible = 0 WHERE eta_ID = :id;";
        $sql = $conn->prepare($request);
        $sql->bindValue(':id', $etatdoc_id, PDO::PARAM_INT);
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

        $request = "UPDATE ".DB_TABLE_ETAT_DOCUMENT." SET eta_is_visible = 0 WHERE eta_ID IN (:list_id)";
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

        $request = "UPDATE ".DB_TABLE_ETAT_DOCUMENT." SET eta_is_visible = 0";
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