<?php 

class Departement{

    const errmessage = "Une erreur s'est produite, signalez la à l'administrateur \n";

    public function db_get_all(){
        global $conn;
        $request = "SELECT * FROM ".DB_TABLE_DEPARTEMENT." 
        INNER JOIN ".DB_TABLE_REGION." ON ".DB_TABLE_DEPARTEMENT.".d_region_code = ".DB_TABLE_REGION.".code
        WHERE ".DB_TABLE_DEPARTEMENT.".is_visible = 1 ORDER BY d_name ";

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

        $request = "SELECT * FROM ".DB_TABLE_DEPARTEMENT." WHERE d_id = :id";
        $sql = $conn->prepare($request);
        $sql->bindValue(':id', $id, PDO::PARAM_INT);

        try{
            $sql->execute();
            return $sql->fetch(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            return $this->errmessage.$e->getMessage();
        }
    }

    public function db_create($code=0, $libelle='', $region_id=0){
        $code = (int) $code;
        if(!$libelle || !$code){
            return false;
        }

        global $conn;
        $request = "INSERT INTO ".DB_TABLE_DEPARTEMENT." (d_code, d_name, d_region_code, d_slug) VALUES(:code, :libelle, :region_id, :slug);";
        $sql = $conn->prepare($request);
        $sql->bindValue(':code', $code, PDO::PARAM_INT);
        $sql->bindValue(':libelle', $libelle, PDO::PARAM_STR);
        $sql->bindValue(':slug', strtolower($libelle), PDO::PARAM_STR);
        $sql->bindValue(':region_id', $region_id, PDO::PARAM_INT);

        try{
            $sql->execute();
            return true;
        }catch(PDOException $e){
            return $this->errmessage.$e->getMessage();
        }
    }

    public function db_update_one($departement_id=0, $newlib=''){
       $departement_id = (int) $departement_id;
        if(!$departement_id){
            return false;
        }

        global $conn;

        $request = "UPDATE ".DB_TABLE_DEPARTEMENT." SET d_name = :libelle, d_slug = :slug WHERE d_id = :id";
        $sql = $conn->prepare($request);
        $slug = strtolower($newlib);
        $sql->bindValue(':libelle', $newlib, PDO::PARAM_STR);
        $sql->bindValue(':slug', $slug, PDO::PARAM_STR);
        $sql->bindValue(':id', $departement_id, PDO::PARAM_INT);
        try{
            $sql->execute();
            return true;
        }catch(PDOException $e){
            return $this->errmessage.$e->getMessage();
        }
    }

    public function db_soft_delete_one($departement_id=0){
        $departement_id = (int) $departement_id;

        if(!$departement_id) {
            return false;
        }

        global $conn;

        $request = "UPDATE ".DB_TABLE_DEPARTEMENT." SET is_visible = 0 WHERE d_id = :id;";
        $sql = $conn->prepare($request);
        $sql->bindValue(':id', $departement_id, PDO::PARAM_INT);
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

        $request = "UPDATE ".DB_TABLE_DEPARTEMENT." SET cas_is_visible = 0 WHERE cas_ID IN (:list_id)";
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

        $request = "UPDATE ".DB_TABLE_DEPARTEMENT." SET cas_is_visible = 0";
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