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
    <div class="error" >
       
    </div>

    <div class="loader" style="display: none">
        <h2>Connexion...</h2>
        <img src="assets/img/svg/loader.svg">
    </div>

    <div class="text-center goodlogin">
        Login / Mot de passe correct : cool / glbipassword
    </div>
    

    <form class="col-4 mx-auto">
        <div class="alert alert-danger" role="alert" style="display: none"></div>
        <div class="form-group">
            <input placeholder="username" type="text" name="username" class="username_input form-control" readonly onfocus="this.removeAttribute('readonly');" required>
        </div>
        <div class="form-group">
            <input placeholder="password" type="password" name="password" class="password_input form-control" readonly onfocus="this.removeAttribute('readonly');" required>
        </div>
        <div class="form-group">
            <button type="submit" class="sub_btn btn btn-primary form-control" name="login">Login</button>
        </div>
    </form>

    <script src="./script/js/subform.js"></script>
    <script src="./script/js/logincheckjwt.js"></script>
    <script src="./script/js/https.js"></script>
    <script src="./script/js/index.js"></script>
</body>
</html>