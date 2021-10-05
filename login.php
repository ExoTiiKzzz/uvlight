<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Login Page</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="assets/css/loader.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    <img src="assets/img/svg/background-log.svg" class="background">
    <img src="assets/img/svg/Illustration.svg" class="illustration">

    <div class="error" >
        
    </div>

    <div class="loader" style="display: none">
        <h2>Connexion...</h2>
        <img src="assets/img/svg/loader.svg">
    </div>

    <nav class="nav">
        <img src="assets/img/svg/Logo_login.svg" class="logo">
        <h1>GLBI</h1>
    </nav>
    

    <form class="log-form">
        <div class="indication goodlogin">
            Login / Mot de passe correct : cool / glbipassword
        </div>
        <div class="alert alert-danger" role="alert" style="display: none"></div>
        <div class="log-section">
            <input placeholder="Nom d'utilisateur" type="text" name="username" class="log-input username_input" readonly onfocus="this.removeAttribute('readonly');" required>
        </div>
        <div class="log-section">
            <input placeholder="Mot de passe" type="password" name="password" class="log-input password_input " readonly onfocus="this.removeAttribute('readonly');" required>
        </div>
        <div class="log-section">
            <button type="submit" class="log-button sub_btn" name="login">Login</button>
        </div>
    </form>

    <script src="./script/js/subform.js"></script>
    <script src="./script/js/logincheckjwt.js"></script>
    <!-- <script src="./script/js/https.js"></script> -->
</body>
</html>