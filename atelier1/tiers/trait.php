<?php 
    require '../../lib/includes/defines.inc.php';
    if(isset($_POST["create"])){
        $city_id = (int) $_POST["ville"];
        if(!$city_id){
            ?>
                <script>
                    alert("Veuillez sélectionner une des villes proposées");
                    window.location.replace("index.php");
                </script>
            <?php
            die;
        }
        $res = $oTiers->db_create(
            $_POST["raison_sociale"], 
            $_POST["type_societe"], 
            $_POST["telephone"], 
            $_POST["email"], 
            $_POST["adresse"], 
            $_POST["ville"],
            $_POST["type_tiers"],
            $_POST["type_reglement"], 
            $_POST["iban"], 
            $_POST["bic"],
            $_POST["code_banque"],
            $_POST["code_guichet"],
            $_POST["numero_compte"],
            $_POST["cle_rib"],
            $_POST["code_tarif"],
            $_POST["domiciliation"]
        );

       if(!$res){
           echo "probleme";
       }else{
           echo "ca a marché";
       }
    }elseif(isset($_POST["update"])){
        $res = $oTiers->db_update(
            $_POST["tiers_id"],
            $_POST["raison_sociale"], 
            $_POST["type_societe"], 
            $_POST["telephone"], 
            $_POST["email"], 
            $_POST["adresse"], 
            $_POST["ville"],
            $_POST["type_tiers"],
            $_POST["type_reglement"], 
            $_POST["iban"], 
            $_POST["bic"],
            $_POST["code_banque"],
            $_POST["code_guichet"],
            $_POST["numero_compte"],
            $_POST["cle_rib"],
            $_POST["code_tarif"],
            $_POST["domiciliation"]
        );

        if($res === true){
            ?>
                <script>
                    alert("L'action a bien été effectuée")
                    window.location.replace("index.php")
                </script>
            <?php
        }else{
            ?>
                <script>
                    alert("Une erreur s'est produite")
                    window.location.replace("index.php")
                </script>
            <?php
        }
    }

?>