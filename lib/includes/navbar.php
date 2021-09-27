<?php
function navbar(){
        $path = "arthurlecompte.com/uvlight/atelier1/";
        ?>
        <header>
            <ul class="menu">
                <a data-toggle="tooltip" data-placement="bottom" title="Accueil" href="<?php echo $path ?>"><i class="fas fa-home"></i></a>
                <a data-toggle="tooltip" data-placement="bottom" title="Tiers" href="<?php echo $path ?>tiers/"><i class="fas fa-user"></i></a>
                <a data-toggle="tooltip" data-placement="bottom" title="Produits" href="#">Produits(pas encore dispo)</a>
                <a class="logout" data-toggle="tooltip" data-placement="bottom" title="Se déconnecter"><i class="fas fa-power-off"></i></a>
            </ul>
        </header>
    <?php
}
?>

