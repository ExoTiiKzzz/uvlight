const subButton = document.querySelector(".sub_btn");
const url = "lib/includes/db/classes/trait.php";
const errorAlert = document.querySelector(".alert-danger");


subButton.addEventListener("click", (e) =>{
    e.preventDefault();

    var username = document.querySelector(".username_input").value;
    var password = document.querySelector(".password_input").value;

    if(username && password){
        var formData = new FormData();
        formData.append("login", "jhvh");
        formData.append("username", username);
        formData.append("password", password);
        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then((response) => response.json())
        .then((text) => {
            form.style.display = "none";
            loader.style.display = "block";
            goodlogin.style.display = "none";
            setTimeout(() => {
                if(text === true){
                    window.location.replace("index.php");
                }else{
                    loader.style.display = "none";
                    errorAlert.innerHTML = "Nom d'utilisateur ou mot de passe incorrect";
                    errorAlert.style.display = "block";
                    form.style.display = "block";
                    goodlogin.style.display = "block";
                }
            }, 1500);
        })
    }else{
        errorAlert.innerHTML = "Veuillez remplir tout les champs";
        errorAlert.style.display = "block";
    }
})


