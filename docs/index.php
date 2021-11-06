<?php 

require '../lib/includes/defines.inc.php';
require '../lib/includes/doctype.php';
require '../lib/includes/navbar.php';
require '../lib/includes/sidenav.php';
require '../lib/includes/footer.php';

echo doctype("Documents", $path);
echo navbar($path);
echo sidenav($path);

?>

<div class="main-container sidenav-open">
    <center><h1>Bonjour</h1></center>
</div>

<?php
echo footer($path);