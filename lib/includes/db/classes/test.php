<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="trait.php" method="post">
        <input type="text" name="username" class="username_input">
        <button class="subbtn" type="submit" name="create">Créer</button>
    </form>

    <script>
        const subButtons = document.querySelectorAll(".subbtn");

        subButtons.forEach(element => {
            element.addEventListener("click", (e) =>{
                e.preventDefault();
                const url = "trait.php";
                if(e.target.name == "create"){
                    var formData = new FormData();
                    formData.append("create", "jhvh");
                    formData.append("username", document.querySelector(".username_input").value);
                    fetch(url, {
                        method: 'POST',
                        body: formData
                    })
                    .then((response) => JSON.stringify(response))
                    .then((text) => {
                        console.log(text);
                    })
                }

            })
        });
    </script>
</body>
</html>