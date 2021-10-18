<?php
function sidenav($path){
        ?>
        <div class="sidenav">
            <i class="fas fa-chevron-left"></i>
            <ul>
                <li><a href="<?php echo $path ?>uvlight/tables/article/index.php">Article</a></li>
                <li><a href="<?php echo $path ?>uvlight/tables/casier/index.php">Casier</a></li>
                <li><a href="<?php echo $path ?>uvlight/tables/categorie/index.php">Categorie</a></li>
                <li><a href="<?php echo $path ?>uvlight/tables/commune/index.php">Commune</a></li>
                <li><a href="<?php echo $path ?>uvlight/tables/departement/index.php">Departement</a></li>
                <li><a href="<?php echo $path ?>uvlight/tables/produit/index.php">Produit</a></li>
                <li><a href="<?php echo $path ?>uvlight/tables/region/index.php">Région</a></li>
            </ul>
        </div>
    <?php
}
?>

