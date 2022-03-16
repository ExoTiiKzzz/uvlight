<?php 
    require '../../lib/includes/defines.inc.php';
    $oLogin->validate_SESSION();
    require '../../lib/includes/navbar.php';
    require '../../lib/includes/sidenav.php';
    require '../../lib/includes/doctype.php';

    echo doctype("Logs", $path);
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
    <div class="table-container">
        <table id="table">
            <thead>
                <th style='text-align :center'>ID</th>
                <th style='text-align :center'>Requête</th>
                <th style='text-align :center'>Date</th>
                <th style='text-align :center'>Adresse IP</th>
            </thead>
        </table>
    </div>
  </div>



    <!-- jQuery Library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Datatable JS -->
    <script src="../script/jquery.dataTables.min.js"></script>
    <script src="../../script/js/sidenav.js"></script>
    <script src="../../script/js/index.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    
    <script> //initialisation datatable
        var table = $('#table');
        $(document).ready(function(){
            table.dataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'ajaxfile.php'
                },
                'columns': [
                    { data: 'id' },
                    { data: 'query' },
                    { data: 'datetime' },
                    { data: 'ip' },
                ],
                deferRender:    true,
                scrollCollapse: true,
                scroller:       true
            });
        });
    </script>
</body>
</html>