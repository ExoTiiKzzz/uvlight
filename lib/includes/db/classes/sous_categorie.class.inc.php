<?php

    class Sous_categorie{

        public function db_get_all(){
            global $conn;
            $request = "SELECT scat_ID, scat_lib, cat_nom FROM ".DB_TABLE_SOUS_CATEGORIE." INNER JOIN ".DB_TABLE_CATEGORIE." ON ".DB_TABLE_SOUS_CATEGORIE.".fk_cat_ID = ".DB_TABLE_CATEGORIE.".cat_ID WHERE scat_is_visible = 1";

            try{
                $sql = $conn->query($request);
                return $sql->fetchAll(PDO::FETCH_ASSOC);
            }catch(PDOException $e){
                return BASIC_ERROR.$e->getMessage();
            }
        }

        public function db_get_by_id($id=0){
    
            global $conn, $oCategorie;
    
            $request = "SELECT * FROM ".DB_TABLE_CATEGORIE ." WHERE cat_id = :cat_ID";
            $sql = $conn->prepare($request);
            $sql->bindValue(':cat_ID', $id, PDO::PARAM_INT);
    
            try{
                $sql->execute();
                if($sql === false){
                    $response["error"] = true;
                    $response["errortext"] = "Une erreur s'est produite";
                    return $response;
                }
                $response["error"] = false;
                $response["content"] = $sql->fetch(PDO::FETCH_ASSOC);
                $response["content"]["catref"] = $oCategorie->db_get_by_id($response["content"]["cat_ref"])["cat_nom"];
                return $response;
            }catch(PDOException $e){
                $response["error"] = true;
                $response["errortext"] = $e->getMessage();
                return $response;
            }
        }

        /**
         * @param string $libelle
         * @param string $categorie 
         * @return boolean
         */

        public function db_create($libelle='', $description, $categorie='') :array{
            global $conn, $oCategorie;
 
            $cat_ID = $oCategorie->db_get_by_lib($categorie)["cat_ID"];

            $request = "INSERT INTO ".DB_TABLE_CATEGORIE." (cat_nom, cat_description, cat_is_scat, cat_ref) VALUES (:cat_lib, :description, 1, $cat_ID)";
            $sql = $conn->prepare($request);
            $sql->bindValue(':cat_lib', $libelle, PDO::PARAM_STR);
            $sql->bindValue(':description', $description, PDO::PARAM_INT);
    
            try{
                $sql->execute();
                if($sql === false){
                    $response["error"] = true;
                    $response["errortext"] = "Une erreur s'est produite";
                    return $response;
                }
                $response["error"] = false;
                return $response;
            }catch(PDOException $e){
                $response["error"] = true;
                $response["errortext"] = $e->getMessage();
                return $response;
            }
        }

        public function db_get_one(){
            global $conn;
    
            $request = "SELECT scat_ID FROM ".DB_TABLE_SOUS_CATEGORIE." WHERE scat_is_visible = 1 AND scat_ID != 0 LIMIT 1";
            try{
                $sql = $conn->query($request);
                return $sql->fetch(PDO::FETCH_ASSOC);
            }catch(PDOException $e){
                return $this->errmessage.$e->getMessage();
            }
        }

        public function db_soft_delete_one($id=0){
    
            global $conn;
    
            $request = "UPDATE ".DB_TABLE_CATEGORIE." SET cat_is_visible = 0 WHERE cat_ID = :id;";
            $sql = $conn->prepare($request);
            $sql->bindValue(':id', $id, PDO::PARAM_INT);
            try{
                $sql->execute();
                return true;
            }catch(PDOException $e){
                return $this->errmessage.$e->getMessage();
            }
        }

        public function db_update($cat_ID, $libelle='', $description, $categorie='') :array{
            global $conn, $oCategorie;

            $ref = $oCategorie->db_get_by_lib($categorie)["cat_ID"];

            $request = "UPDATE ".DB_TABLE_CATEGORIE." SET cat_nom = :cat_nom, cat_description = :cat_description, cat_ref = :cat_ref  WHERE cat_ID = :cat_ID";
            $sql = $conn->prepare($request);
            $sql->bindValue(':cat_nom', $libelle);
            $sql->bindValue(':cat_description', $description);
            $sql->bindValue(':cat_ref', $ref, PDO::PARAM_INT);
            $sql->bindValue(':cat_ID', $cat_ID, PDO::PARAM_INT);

            try{
                $sql = $sql->execute();
                if($sql === false){
                    $response["error"] = true;
                    $response["errortext"] = "Une erreur s'est produite";
                    return $response;
                }
                $response["error"] = false;
                return $response;
            }catch(PDOException $e){
                $response["error"] = true;
                $response["errortext"] = $e->getMessage();
                return $response;
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
    
            $variables = $id_array;
            $placeholders = str_repeat ('?, ',  count ($variables) - 1) . '?';
    
            $sql = $conn -> prepare ("UPDATE ".DB_TABLE_CATEGORIE." SET cat_is_visible = 0 WHERE cat_ID IN($placeholders)");
            try{
                $sql->execute($variables);
                return true;
            }catch(PDOException $e){
                return $this->errmessage.$e->getMessage();
            }
        }

        public function db_soft_delete_all(){
            global $conn;
    
            $request = "UPDATE ".DB_TABLE_CATEGORIE." SET cat_is_visible = 0";
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