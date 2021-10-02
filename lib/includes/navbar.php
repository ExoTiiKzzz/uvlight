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
                        <li class="dropdown-container ul-el">
                            <div class="button">
                                <a href="#">Test<i class="fas fa-chevron-down"></i></a>
                            </div>
                            <div class="dropdown">
                                <ul>
                                    <li><a href="#">Test</a></li>
                                    <li><a href="#">Test</a></li>
                                    <li><a href="#">Test</a></li>
                                    <li><a href="#">Test</a></li>
                                    <li><a href="#">Test</a></li>
                                </ul>
                            </div> 
                        </li>
                        <li class="ul-el"><span href="#"><i class="far fa-power-off logout"></i></span></li>
                    </div>
                    
                </ul>
            </nav>
        </header>
    <?php
}
?>

