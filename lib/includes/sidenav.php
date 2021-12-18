<?php
function sidenav($path){
        ?>
        <div class="sidenav">
            <i class="fas fa-chevron-left"></i>
            <ul class="list">
                <li><a href="<?php echo $path ?>uvlight/tables/article/index.php">Article</a></li>
                <li><a href="<?php echo $path ?>uvlight/tables/casier/index.php">Casier</a></li>
                <li><a href="<?php echo $path ?>uvlight/tables/categorie/index.php">Categorie</a></li>
                <li><a href="<?php echo $path ?>uvlight/tables/ventes/index.php">Ventes</a></li>
            </ul>
        </div>
    <?php
}
?>

