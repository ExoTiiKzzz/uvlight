<?php

if(isset($_SESSION["error"])){
    ?>
    <script>
        let div = document.createElement('div');
        div.classList.add("error");
        document.querySelector("html").appendChild(div);
        setTimeout(() => {
            div.classList.add("errorManager");
        }, 1500)
        div.innerText = "<?= $_SESSION['error'] ?>";
        <?php unset($_SESSION["error"]); ?>
        setTimeout(() => {
            div.classList.remove("errorManager");
            setTimeout(() => {
                document.querySelector("html").removeChild(div);
            }, 2000)
        }, 8000);

    </script>
    <?php
}