<?php 


class Tiers{

    const errmessage = "Une erreur s'est produite, signalez la à l'administrateur \n";

    public function db_get_all(){
        global $conn;
        $request = "SELECT * FROM ".DB_TABLE_TIERS." 
                    INNER JOIN ".DB_TABLE_COMMUNES." ON ".DB_TABLE_TIERS.".fk_com_ID = ".DB_TABLE_COMMUNES.".c_ID
                    INNER JOIN ".DB_TABLE_TYPE_REGLEMENT." ON ".DB_TABLE_TIERS.".fk_typre_ID = ".DB_TABLE_TYPE_REGLEMENT.".typre_ID
                    INNER JOIN ".DB_TABLE_TARIF." ON ".DB_TABLE_TIERS.".fk_tar_id = ".DB_TABLE_TARIF.".tar_ID
                    INNER JOIN ".DB_TABLE_TYPE_SOCIETE." ON ".DB_TABLE_TIERS.".fk_typso_ID = ".DB_TABLE_TYPE_SOCIETE.".typso_ID WHERE tie_is_visible = 1";

        try{
            $sql = $conn->query($request);
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            return $this->errmessage.$e->getMessage();
        }
    }

    public function db_get_by_id($tiers_id=0){
        $tiers_id = (int) $tiers_id;
        if(!$tiers_id){
            return false;
        }

        global $conn;

        $request = "SELECT * FROM ".DB_TABLE_TIERS." WHERE tie_ID = :id";
        $sql = $conn->prepare($request);
        $sql->bindValue(':id', $tiers_id, PDO::PARAM_INT);

        try{
            $sql->execute();
            return $sql->fetch(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            return $this->errmessage.$e->getMessage();
        }
    }

    public function db_create($raison_sociale='', $type_societe=0, $telephone='', $email='', $adresse ='', $ville=0, 
                              $type_tiers=0, $type_reglement=0, $iban='', $bic='', $code_banque='', $code_guichet='', 
                              $numero_compte='', $cle_rib='', $code_tarif=0, $domiciliation=''){


        $type_societe = (int) $type_societe;
        $type_reglement = (int) $type_reglement;
        $ville = (int) $ville;
        $type_tiers= (int) $type_tiers;
        $code_tarif = (int) $code_tarif;

        if(!$raison_sociale || !$type_societe || !$telephone || !$email || !$adresse || !$ville || !$type_tiers || !$type_reglement || !$iban || !$bic || !$code_banque || !$code_guichet || !$numero_compte || !$cle_rib || !$code_tarif || !$domiciliation){
            return false;
        }

        $fields = array( 
            'adresse' => $adresse,
            'telephone' => $telephone,
            'email' => $email,
            'raison_sociale' => $raison_sociale,
            'iban' => $iban,
            'bic' => $bic,
            'code_banque' => $code_banque,
            'code_guichet' => $code_guichet,
            'numero_compte' => $numero_compte,
            'cle_rib' => $cle_rib,
            'domiciliation' => $domiciliation
        );

        global $conn;
        $request = "INSERT INTO ".DB_TABLE_TIERS."
                    (tie_adresse, tie_tel, tie_email, tie_raison_sociale, tie_IBAN, tie_BIC, tie_code_banque,
                    tie_code_guichet, tie_num_compte, tie_cle_rib, tie_domiciliation, fk_com_ID, fk_typre_ID, fk_tar_id, fk_typso_ID, fk_typti_ID) 
                    VALUES(:adresse, :telephone, :email, :raison_sociale, :iban, :bic, :code_banque,
                           :code_guichet, :numero_compte, :cle_rib, :domiciliation, :ville, :type_reglement, :code_tarif, :type_societe, :type_tiers);";
        $sql = $conn->prepare($request);
        foreach($fields as $key => $value){
            $sql->bindValue(':'.$key, $value,PDO::PARAM_STR);
            $sql->bindValue(':ville', $ville, PDO::PARAM_INT);
            $sql->bindValue(':type_reglement', $type_reglement, PDO::PARAM_INT);
            $sql->bindValue(':code_tarif', $code_tarif, PDO::PARAM_INT);
            $sql->bindValue(':type_societe', $type_societe, PDO::PARAM_INT);
            $sql->bindValue(':type_tiers', $type_tiers, PDO::PARAM_INT);
        }

        try{
            $sql->execute();
            return true;
        }catch(PDOException $e){
            return $this->errmessage.$e->getMessage();
        }
    }

    public function db_update($tiers_id=0, $raison_sociale='', $type_societe=0, $telephone='', $email='', $adresse ='', $ville=0, 
                              $type_tiers=0, $type_reglement=0, $iban='', $bic='', $code_banque='', $code_guichet='', 
                              $numero_compte='', $cle_rib='', $code_tarif=0, $domiciliation=''){
        $tiers_id = (int) $tiers_id;
        $type_societe = (int) $type_societe;
        $type_reglement = (int) $type_reglement;
        $ville = (int) $ville;
        $type_tiers= (int) $type_tiers;
        $code_tarif = (int) $code_tarif;

        if(!$tiers_id){
            return false;
        }

        if(!$tiers_id || !$raison_sociale || !$type_societe || !$telephone || !$email || !$adresse || !$ville || !$type_tiers || !$type_reglement || !$iban || !$bic || !$code_banque || !$code_guichet || !$numero_compte || !$cle_rib || !$code_tarif || !$domiciliation){
            return false;
        }

        $fields = array( 
            'adresse' => $adresse,
            'telephone' => $telephone,
            'email' => $email,
            'raison_sociale' => $raison_sociale,
            'iban' => $iban,
            'bic' => $bic,
            'code_banque' => $code_banque,
            'code_guichet' => $code_guichet,
            'numero_compte' => $numero_compte,
            'cle_rib' => $cle_rib,
            'domiciliation' => $domiciliation
        );

        global $conn;
        $request = "UPDATE ".DB_TABLE_TIERS."
                    SET tie_adresse = :adresse, tie_tel = :telephone, tie_email = :email, tie_raison_sociale = :raison_sociale, tie_IBAN = :iban, tie_BIC = :bic, 
                    tie_code_banque = :code_banque, tie_code_guichet = :code_guichet, tie_num_compte = :numero_compte, tie_cle_rib = :cle_rib, tie_domiciliation = :domiciliation, 
                    fk_com_ID = :ville, fk_typre_ID = :type_reglement, fk_tar_id = :code_tarif, fk_typso_ID = :type_societe,  fk_typti_ID = :type_tiers WHERE tie_ID = :tiers_id";
        $sql = $conn->prepare($request);
        foreach($fields as $key => $value){
            $sql->bindValue(':'.$key, $value,PDO::PARAM_STR);
            $sql->bindValue(':ville', $ville, PDO::PARAM_INT);
            $sql->bindValue(':type_reglement', $type_reglement, PDO::PARAM_INT);
            $sql->bindValue(':code_tarif', $code_tarif, PDO::PARAM_INT);
            $sql->bindValue(':type_societe', $type_societe, PDO::PARAM_INT);
            $sql->bindValue(':tiers_id', $tiers_id, PDO::PARAM_INT);
            $sql->bindValue(':type_tiers', $type_tiers, PDO::PARAM_INT);
        }

        try{
            $sql->execute();
            return true;
        }catch(PDOException $e){
            return $this->errmessage.$e->getMessage();
        }
    }

    public function db_soft_delete_one($tiers_id=0){
        $tiers_id = (int) $tiers_id;

        if(!$tiers_id) {
            return false;
        }

        global $conn;

        $request = "UPDATE ".DB_TABLE_TIERS." SET tie_is_visible = 0 WHERE tie_ID = :id;";
        $sql = $conn->prepare($request);
        $sql->bindValue(':id', $tiers_id, PDO::PARAM_INT);
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

        $variables = $id_array;
        $placeholders = str_repeat ('?, ',  count ($variables) - 1) . '?';

        $sql = $conn -> prepare ("UPDATE ".DB_TABLE_TIERS." SET tie_is_visible = 0 WHERE tie_ID IN($placeholders)");
        try{
            $sql->execute($variables);
            return true;
        }catch(PDOException $e){
            return $this->errmessage.$e->getMessage();
        }
    }

    public function db_soft_delete_all(){
        global $conn;

        $request = "UPDATE ".DB_TABLE_TIERS." SET tie_is_visible = 0";
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