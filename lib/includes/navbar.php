<?php
function navbar($path){
        ?>
        <header>
            <nav>
                <div class="logo">
                    <a href=""><img src="<?php echo $path ?>uvlight/assets/img/svg/Logo_login.svg"></a>
                </div>
                <ul class="nav">
                    <div class="profile-pic">

                    </div>
                    <div class="list-item">
                        <li class="ul-el"><a href="<?php echo $path ?>uvlight/">Dashboard</a></li>
                        <li class="ul-el"><span href="#"><i class="far fa-power-off logout"></i></span></li>
                    </div>
                    
                </ul>
            </nav>
        </header>
    <?php
}
?>

