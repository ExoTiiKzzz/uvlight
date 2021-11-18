<?php

    class Sous_categorie{

        public function db_get_all(){
            global $conn;
            $request = "SELECT * FROM ".DB_TABLE_SOUS_CATEGORIE." WHERE scat_is_visible = 1";

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

        public function db_create($libelle='', $categorie=''){
            global $conn;
            $request = "SELECT cat_ID FROM".DB_TABLE_CATEGORIE." WHERE cat_nom = :cat_nom";
            $sql = $conn->prepare($request);
            $sql->bindValue(':cat_nom', $categorie, PDO::PARAM_STR);
            try{
                $sql->execute();
                $cat_ID=$sql->fetch(PDO::FETCH_ASSOC)['cat_ID'];
            }catch(PDOException $e){
                return BASIC_ERROR.$e->getMessage();
            }

            $request = "INSERT INTO ".DB_TABLE_SOUS_CATEGORIE." (scat_lib, fk_cat_ID) VALUES (:libelle, :fk_cat_ID)";
            $sql = $conn->prepare($request);
            $sql->bindValue(':libelle', $libelle, PDO::PARAM_STR);
            $sql->bindValue(':fk_cat_ID', $cat_ID, PDO::PARAM_STR);
    
            try{
                $sql->execute();
                return true;
            }catch(PDOException $e){
                return BASIC_ERROR.$e->getMessage();
            }
        }
    }