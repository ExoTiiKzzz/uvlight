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
    <title>Regions</title>
</head>
<body>

<?php
    if(!isset($_GET["nav"]) || $_GET["nav"] === "read"){

        $data = $oRegion->db_get_all();

  ?>
    <a href="index.php?nav=create">Créer une nouvelle région</a>

    <table id="table">
        <thead>
            <th>Code</th>
            <th>Nom</th>
            <th>Actions</th>
        </thead>
        <tbody>
            <?php 
                foreach ($data as $key) {
                    $id = $key["id"];
                    echo "<tr>
                    <td><center>".$key["code"]."</center></td>
                    <td><center>".$key["name"]."</center></td>
                    <td style='display:flex; justify-content: space-evenly;'>
                        <a href='index.php?nav=update&id=$id'>Modifier</a>
                        <a href='index.php?nav=delete&id=$id'>Supprimer</a>
                    </td>
                    </tr>";
                }
            ?>
        </tbody>
    </table>

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
                <input placeholder="Nom de la région" type="text" name="region_name">
                <input placeholder="Code de la région" type="text" name="region_code">
                <button name="create" type="submit">Enregistrer</button>
            </form>

        <?php
    }
    elseif($_GET["nav"] === "update"){
        $data = $oRegion->db_get_by_id($_GET["id"]);
        ?>
            <form action="trait.php" method="post">
                <input type="text" name="region_name" value="<?php echo $data["name"]; ?>">
                <input type="hidden" name="region_id" value="<?php echo $_GET["id"]; ?>">
                <button name="update" type="submit">Enregistrer</button>
            </form>
        <?php

    }
    elseif($_GET["nav"] === "delete"){
        $data = $oRegion->db_get_by_id($_GET["id"]);
        ?>
            <form action="trait.php" method="post">
                <input disabled type="text" name="region_name" value="<?php echo $data["name"]; ?>">
                <input type="hidden" name="region_id" value="<?php echo $_GET["id"]; ?>">
                <button name="delete" type="submit">Supprimer</button>
            </form>
        <?php

    }
    
    
    ?>

    
</body>
</html>