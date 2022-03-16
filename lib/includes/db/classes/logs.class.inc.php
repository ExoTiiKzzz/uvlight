<?php

class Logs{

    const errmessage = "Une erreur s'est produite, signalez la à l'administrateur \n";

    public function db_get_all(){
        global $conn;
        $request = "SELECT id as arrkey, id, query, datetime, ip FROM ".DB_TABLE_LOGS." WHERE cat_is_visible = 1";

        try{
            $sql = $conn->query($request);
            return $sql->fetchAll(\PDO::FETCH_GROUP|\PDO::FETCH_UNIQUE|\PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            return self::errmessage.$e->getMessage();
        }
    }
}
?>