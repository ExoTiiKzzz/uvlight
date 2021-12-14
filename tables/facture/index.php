<?php
if(!isset($_GET["id"]) || empty($_GET["id"])) header('Location: ./');
require '../../lib/includes/defines.inc.php';
require '../../lib/includes/navbar.php';
require '../../lib/includes/sidenav.php';
require '../../lib/includes/doctype.php';

echo doctype("Commande ".$_GET['id'], $path);
echo navbar($path);
echo sidenav($path);



?>
<body>
<style>

</style>
<?php


?>


<div class="main-container sidenav-open mb-5">

</div>


<!-- jQuery Library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script src="../../script/js/sidenav.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

<script> //initialisation datatable

</script>
</body>
</html>