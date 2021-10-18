<?php 
    require '../../lib/includes/defines.inc.php';
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
    <?php

        $data = $oDepartement->db_get_all();

    ?>
    <div class="main-container sidenav-open">
        <a href="index.php?nav=create">Créer un nouveau departement</a>
        <div class="table-container">
            <table id="table">
                <thead>
                    <th>Code</th>
                    <th>Nom Region</th>
                    <th>Nom</th>
                    <th>Actions</th>
                </thead>
                <tbody>
                    <?php 
                        foreach ($data as $key) {
                            $id = $key["id"];
                            echo "<tr>
                            <td><center>".$key["d_code"]."</center></td>
                            <td><center>".$key["name"]."</center></td>
                            <td><center>".$key["d_name"]."</center></td>
                            <td style='display:flex; justify-content: space-evenly;'>
                                <a href='index.php?nav=update&id=$id'>Modifier</a>
                                <a href='index.php?nav=delete&id=$id'>Supprimer</a>
                            </td>
                            </tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
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

    
<script src="../../script/js/sidenav.js"></script>
</body>
</html>