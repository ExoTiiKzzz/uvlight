<?php 

class Type_Document{

    const errmessage = "Une erreur s'est produite, signalez la à l'administrateur \n";

    public function db_get_all(){
        global $conn;
        $request = "SELECT * FROM ".DB_TABLE_TYPE_DOCUMENT." WHERE typdo_is_visible = 1;";

        try{
            $sql = $conn->query($request);
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            return $this->errmessage.$e->getMessage();
        }
    }

    public function db_get_by_id($id=0){
        $id = (int) $id;
        if(!$id){
            $response["error"] = true;
            $response["errortext"] = "Incorrect ID";
            return $response;
        }

        global $conn;

        $request = "SELECT * FROM ".DB_TABLE_TYPE_DOCUMENT." WHERE typdo_ID = :id";
        $sql = $conn->prepare($request);
        $sql->bindValue(':id', $id, PDO::PARAM_INT);

        try{
            $sql->execute();
            $response["error"] = false;
            $response["content"] = $sql->fetch(PDO::FETCH_ASSOC);
            return $response;
        }catch(PDOException $e){
            $response["error"] = true;
            $response["errortext"] = $this->errmessage.$e->getMessage();
            return $response;
        }
    }

    public function db_create($libelle=''){

        if(!$libelle){
            $response["error"] = true;
            $response["errortext"] = "Incorrect lib";
            return $response;
        }

        global $conn;
        $request = "INSERT INTO ".DB_TABLE_TYPE_DOCUMENT." (typdo_lib) VALUES(:libelle);";
        $sql = $conn->prepare($request);
        $sql->bindValue(':libelle', $libelle, PDO::PARAM_STR);

        try{
            $sql->execute();
            $response["error"] = false;
            $response["content"] = $conn->lastInsertId();
            return $response;
        }catch(PDOException $e){
            $response["error"] = true;
            $response["errortext"] = $this->errmessage.$e->getMessage();
            return $response;
        }
    }

    public function db_update_lib($id=0, $newlib=''){
        $id = (int) $id;
        if(!$id || !$newlib){
            $response["error"] = true;
            $response["errortext"] = "Incorrect lib";
            return $response;
        }

        global $conn;

        $request = "UPDATE ".DB_TABLE_TYPE_DOCUMENT." SET typdo_lib = :libelle WHERE typdo_ID = :id";
        $sql = $conn->prepare($request);
        $sql->bindValue(':libelle', $newlib, PDO::PARAM_STR);
        $sql->bindValue(':id', $id, PDO::PARAM_INT);
        try{
            $sql->execute();
            $response["error"] = false;
            return $response;
        }catch(PDOException $e){
            $response["error"] = true;
            $response["errortext"] = $this->errmessage.$e->getMessage();
            return $response;
        }
    }

    public function db_soft_delete_one($id=0){
        $id = (int) $id;

        if(!$id) {
            $response["error"] = true;
            $response["errortext"] = "Incorrect lib";
            return $response;
        }

        global $conn;

        $request = "UPDATE ".DB_TABLE_TYPE_DOCUMENT." SET typdo_is_visible = 0 WHERE typdo_ID = :id;";
        $sql = $conn->prepare($request);
        $sql->bindValue(':id', $id, PDO::PARAM_INT);
        try{
            $sql->execute();
            $response["error"] = false;
            return $response;
        }catch(PDOException $e){
            $response["error"] = true;
            $response["errortext"] = $this->errmessage.$e->getMessage();
            return $response;
        }
    }

    public function db_soft_delete_multi($id_array){
        $array = $_POST["array"];
        $finalarray = json_decode($array, true);
        global $conn;

        $variables = $finalarray;
        $placeholders = str_repeat ('?, ',  count ($variables) - 1) . '?';

        $request = "UPDATE ".DB_TABLE_TYPE_DOCUMENT." SET typdo_is_visible = 0 WHERE typdo_ID IN ($placeholders)";
        $sql = $conn->prepare($request);
        try{
            $sql->execute($variables);
            return true;
        }catch(PDOException $e){
            return $this->errmessage.$e->getMessage();
        }
    }

    public function db_soft_delete_all(){
        global $conn;

        $request = "UPDATE ".DB_TABLE_TYPE_DOCUMENT." SET typdo_is_visible = 0";
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