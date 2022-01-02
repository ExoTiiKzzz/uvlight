<?php
function sidenav($path){
        ?>
        <div class="sidenav">
            <i class="fas fa-chevron-left"></i>
            <ul class="list">
                <li><a href="<?php echo $path ?>uvlight/tables/article">Article</a></li>
                <li><a href="<?php echo $path ?>uvlight/tables/casier">Casier</a></li>
                <li><a href="<?php echo $path ?>uvlight/tables/categorie">Categorie</a></li>
                <li><a href="<?php echo $path ?>uvlight/tables/commandes">Commandes</a></li>
                <li><a href="<?php echo $path ?>uvlight/tables/ventes">Ventes</a></li>
            </ul>
        </div>
    <?php
}
?>

