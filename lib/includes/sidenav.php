<?php
function sidenav($path){
        ?>
        <div class="sidenav">
            <i class="fas fa-chevron-left"></i>
            <ul class="list">
                <li><a class="a_art" href="<?php echo $path ?>uvlight/tables/article">Articles</a></li>
                <li><a class="a_cas" href="<?php echo $path ?>uvlight/tables/casier">Casiers</a></li>
                <li><a class="a_cat" href="<?php echo $path ?>uvlight/tables/categorie">Catégories</a></li>
                <li><a class="a_scat" href="<?php echo $path ?>uvlight/tables/sous_categorie">Sous Catégories</a></li>
                <li><a class="a_tie" href="<?php echo $path ?>uvlight/tables/tiers">Tiers</a></li>
                <li><a class="a_com" href="<?php echo $path ?>uvlight/tables/commandes">Commandes</a></li>
                <li><a class="a_ven" href="<?php echo $path ?>uvlight/tables/ventes">Ventes</a></li>
            </ul>
        </div>
    <?php
}
?>

