<?php 
require '../../lib/includes/defines.inc.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.2/datatables.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <title>Casiers</title>
</head>
<body>

<?php
    if(!isset($_GET["nav"]) || $_GET["nav"] === "read"){

        $data = $oCasier->db_get_all();

  ?>
    <a href="index.php?nav=create">Créer un nouveau casier</a>

    <table id="table">
        <thead>
            <th>Selectionner</th>
            <th style='text-align :center'>ID</th>
            <th style='text-align :center'>Lib</th>
            <th style='text-align :center'>Actions</th>
        </thead>
        <tbody>
            <?php 
                foreach ($data as $key) {
                    $id = $key["cas_ID"];
                    echo "<tr>
                    <td style='width: 5%'>
                    <input type='checkbox' data-index=".$id."></td>
                    <td><center>".$id."</center></td>
                    <td><center>".$key["cas_lib"]."</center></td>
                    <td style='display:flex; justify-content: space-evenly;'>
                        <a href='index.php?nav=update&id=$id'>Modifier</a>
                        <a href='index.php?nav=delete&id=$id'>Supprimer</a>
                    </td>
                    </tr>";
                }
            ?>
        </tbody>
    </table>
    <div class="operations-div" style="display: flex; justify-content: space-evenly">
        <button style="" class="btn btn-danger delete">
            Supprimer les éléments selectionnés.
        </button>
    </div>

    <!-- jQuery Library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Datatable JS -->
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.2/datatables.min.js"></script>

    <script> //initialisation datatable
        $(document).ready(function(){
            $('#table').DataTable();
        });
    </script>

    <?php
    }
    elseif($_GET['nav'] === "create"){
        ?>
            <h1>Créer une région</h1>

            <form action="trait.php" method="post">
                <input type="text" name="casier_name">
                <button name="create" type="submit">Enregistrer</button>
            </form>

        <?php
    }

    elseif($_GET["nav"] === "update"){
        $data = $oCasier->db_get_by_id($_GET["id"]);
        ?>
            <form action="trait.php" method="post">
                <input type="text" name="casier_name" value="<?php echo $data["cas_lib"]; ?>">
                <input type="hidden" name="casier_id" value="<?php echo $_GET["id"]; ?>">
                <button name="update" type="submit">Enregistrer</button>
            </form>
        <?php

    }
    elseif($_GET["nav"] === "delete"){
        $data = $oCasier->db_get_by_id($_GET["id"]);
        ?>
            <form action="trait.php" method="post">
                <input disabled type="text" name="casier_name" value="<?php echo $data["cas_lib"]; ?>">
                <input type="hidden" name="casier_id" value="<?php echo $_GET["id"]; ?>">
                <button name="delete" type="submit">Supprimer</button>
            </form>
        <?php

    }
    ?>

    
</body>
</html>