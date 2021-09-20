<?php 

class Region{

    const errmessage = "Une erreur s'est produite, signalez la à l'administrateur \n";

    public function db_get_all(){
        global $conn;
        $request = "SELECT * FROM ".DB_TABLE_REGION." WHERE is_visible = 1";

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
            return false;
        }

        global $conn;

        $request = "SELECT * FROM ".DB_TABLE_REGION." WHERE id = :id";
        $sql = $conn->prepare($request);
        $sql->bindValue(':id', $id, PDO::PARAM_INT);

        try{
            $sql->execute();
            return $sql->fetch(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            return $this->errmessage.$e->getMessage();
        }
    }

    public function db_create($code=0, $libelle=''){
        $code = (int) $code;
        if(!$libelle || !$code){
            return false;
        }

        global $conn;
        $request = "INSERT INTO ".DB_TABLE_REGION." (code, name) VALUES(:code, :libelle);";
        $sql = $conn->prepare($request);
        $sql->bindValue(':code', $code, PDO::PARAM_INT);
        $sql->bindValue(':libelle', $libelle, PDO::PARAM_STR);

        try{
            $sql->execute();
            return true;
        }catch(PDOException $e){
            return $this->errmessage.$e->getMessage();
        }
    }

    public function db_update_one($region_id=0, $newlib=''){
       $region_id = (int) $region_id;
        if(!$region_id){
            return false;
        }

        global $conn;

        $request = "UPDATE ".DB_TABLE_REGION." SET name = :libelle, slug = :slug WHERE id = :id";
        $sql = $conn->prepare($request);
        $slug = strtolower($newlib);
        $sql->bindValue(':libelle', $newlib, PDO::PARAM_STR);
        $sql->bindValue(':slug', $slug, PDO::PARAM_STR);
        $sql->bindValue(':id', $region_id, PDO::PARAM_INT);
        try{
            $sql->execute();
            return true;
        }catch(PDOException $e){
            return $this->errmessage.$e->getMessage();
        }
    }

    public function db_soft_delete_one($region_id=0){
        $region_id = (int) $region_id;

        if(!$region_id) {
            return false;
        }

        global $conn;

        $request = "UPDATE ".DB_TABLE_REGION." SET is_visible = 0 WHERE id = :id;";
        $sql = $conn->prepare($request);
        $sql->bindValue(':id', $region_id, PDO::PARAM_INT);
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

        $request = "UPDATE ".DB_TABLE_REGION." SET cas_is_visible = 0 WHERE cas_ID IN (:list_id)";
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

        $request = "UPDATE ".DB_TABLE_REGION." SET cas_is_visible = 0";
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