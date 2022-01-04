<?php 
    require '../../lib/includes/defines.inc.php';
$oLogin->validate_SESSION();
    require '../../lib/includes/navbar.php';
    require '../../lib/includes/sidenav.php';
    require '../../lib/includes/doctype.php';

    echo doctype("Article", $path);
    echo navbar($path);
    echo sidenav($path);
?>
<body>
    <style>
        <?php 
            require '../static/css/table.css';
        ?>
    </style>
    <div class="main-container sidenav-open">

<?php
    if(!isset($_GET["nav"]) || $_GET["nav"] === "read"){

  ?>
    <a class="btn btn-success mb-4" href="index.php?nav=create">Créer une nouvelle commune</a>

    <div class="table-container">
        <table id="table">
            <thead>
                <th>Code INSEE</th>
                <th>Code ZIP</th>
                <th>Nom Departement</th>
                <th>Nom</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Actions</th>
            </thead>
        </table>
    </div>
    
  </div>
    

    <script> //initialisation datatable
        $(document).ready(function(){
            $('#table').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'ajaxfile.php'
                },
                'columns': [
                    { data: 'insee_code' },
                    { data: 'zip_code' },
                    { data: 'd_name' },
                    { data: 'c_name' },
                    { data: 'gps_lat' },
                    { data: 'gps_lng' },
                    { data: 'actions' }
                ],
                deferRender:    true,
                scrollCollapse: true,
                scroller:       true
            });
        });
    </script>

    <?php
    }
    elseif($_GET['nav'] === "create"){
        $departements = $oDepartement->db_get_all();
        ?>
            <h1>Créer une ville</h1>

            <form action="trait.php" method="post">
                <input placeholder="Nom de la commune" type="text" name="city_name">
                <input placeholder="Code INSEE" type="text" name="insee_code">
                <input placeholder="Code ZIP" type="text" name="zip_code">
                <input placeholder="latitude" type="text" name="lat">
                <input placeholder="longitude" type="text" name="lng">
                <label for="">Departement</label>
                    <input type="text" id="departement" class="form-control" name='departement' onkeyup="autocomplet()">
                    <datalist id="departement_list" type="text"  placeholder="Departement" required></datalist>
                <button name="create" type="submit">Enregistrer</button>
            </form>

        <?php
    }
    elseif($_GET["nav"] === "update"){
        $data = $oCommunes->db_get_by_id($_GET["id"]);
        ?>
            <form action="trait.php" method="post">
                <input type="hidden" name="city_id" value="<?php echo $data["c_id"] ?>">
                <input placeholder="Nom de la commune" type="text" name="city_name" value="<?php echo $data["c_name"] ?>">
                <input placeholder="Code INSEE" type="text" name="insee_code" value="<?php echo $data["insee_code"] ?>">
                <input placeholder="Code ZIP" type="text" name="zip_code" value="<?php echo $data["zip_code"] ?>">
                <input placeholder="latitude" type="text" name="lat" value="<?php echo $data["gps_lat"] ?>">
                <input placeholder="longitude" type="text" name="lng" value="<?php echo $data["gps_lng"] ?>">
                <button name="update" type="submit">Enregistrer</button>
            </form>
        <?php

    }
    elseif($_GET["nav"] === "delete"){
        $data = $oCommunes->db_get_by_id($_GET["id"]);
        ?>
            <form action="trait.php" method="post">
                <input disabled type="text" name="city_name" value="<?php echo $data["c_name"]; ?>">
                <input type="hidden" name="city_id" value="<?php echo $_GET["id"]; ?>">
                <button name="delete" type="submit">Supprimer</button>
            </form>
        <?php

    }
    
    
    ?>

    <!-- Datatable JS -->
    <script src="../script/jquery.dataTables.min.js"></script>
    <script src="../../script/js/sidenav.js"></script>
</body>
</html>