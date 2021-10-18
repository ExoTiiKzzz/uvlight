<?php
function sidenav($path){
        ?>
        <div class="sidenav">
            <i class="fas fa-chevron-left"></i>
            <ul>
                <li><a href="<?php echo $path ?>tables/article/index.php">Article</a></li>
                <li><a href="<?php echo $path ?>tables/casier/index.php">Casier</a></li>
                <li><a href="<?php echo $path ?>tables/categorie/index.php">Categorie</a></li>
                <li><a href="<?php echo $path ?>tables/commune/index.php">Commune</a></li>
                <li><a href="<?php echo $path ?>tables/departement/index.php">Departement</a></li>
                <li><a href="<?php echo $path ?>tables/produit/index.php">Produit</a></li>
                <li><a href="<?php echo $path ?>tables/region/index.php">Région</a></li>
            </ul>
        </div>
    <?php
}
?>

