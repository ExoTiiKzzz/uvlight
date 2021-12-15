<?php
if(isset($_POST["prevalidation"])){
    ?>
    <pre>
        <?php
            var_dump($_POST["article"]);
            var_dump($_POST["quantite"]);
        ?>
    </pre>

    <?php
}