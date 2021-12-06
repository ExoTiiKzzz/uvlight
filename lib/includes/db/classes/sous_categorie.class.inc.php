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
            $id = (int) $id;
            if(!$id){
                return false;
            }
    
            global $conn;
    
            $request = "SELECT * FROM ".DB_TABLE_SOUS_CATEGORIE." WHERE id = :scat_ID";
            $sql = $conn->prepare($request);
            $sql->bindValue(':scat_ID', $id, PDO::PARAM_INT);
    
            try{
                $sql->execute();
                return $sql->fetch(PDO::FETCH_ASSOC);
            }catch(PDOException $e){
                return BASIC_ERROR.$e->getMessage();
            }
        }

        /**
         * @param string $libelle
         * @param string $categorie 
         * @return boolean
         */

        public function db_create($libelle='', $categorie=''){
            global $conn, $oCategorie;
            
            $cat_ID = $oCategorie->db_get_by_lib($categorie)['cat_ID'];

            $request = "INSERT INTO ".DB_TABLE_SOUS_CATEGORIE." (scat_lib, fk_cat_ID) VALUES (:scat_lib, :fk_cat_ID)";
            $sql = $conn->prepare($request);
            $sql->bindValue(':scat_lib', $libelle, PDO::PARAM_STR);
            $sql->bindValue(':fk_cat_ID', $cat_ID, PDO::PARAM_STR);
    
            try{
                $sql->execute();
                return true;
            }catch(PDOException $e){
                return BASIC_ERROR.$e->getMessage();
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
    
            $request = "UPDATE ".DB_TABLE_SOUS_CATEGORIE." SET scat_is_visible = 0 WHERE scat_ID = :id;";
            $sql = $conn->prepare($request);
            $sql->bindValue(':id', $id, PDO::PARAM_INT);
            try{
                $sql->execute();
                return true;
            }catch(PDOException $e){
                return $this->errmessage.$e->getMessage();
            }
        }

        public function db_update($sous_categorie_id=0, $newlib='', $newcat=''){
            $sous_categorie_id = (int) $sous_categorie_id;
            if(!$sous_categorie_id || !$newlib || !$newcat){
                return false;
            }
    
            global $conn;
    
            $request = "UPDATE ".DB_TABLE_SOUS_CATEGORIE." INNER JOIN ".DB_TABLE_CATEGORIE." SET scat_lib = :scat_lib, cat = :cat WHERE scat_ID = :id";
            $sql = $conn->prepare($request);
            $sql->bindValue(':scat_lib', $newlib, PDO::PARAM_STR);
            $sql->bindValue(':cat', $newcat, PDO::PARAM_STR);
            $sql->bindValue(':id', $sous_categorie_id, PDO::PARAM_INT);
            try{
                $sql->execute();
                return true;
            }catch(PDOException $e){
                return $this->errmessage.$e->getMessage();
            }
        }
    }
?>