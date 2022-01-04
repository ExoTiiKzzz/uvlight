const logBtn = document.querySelector(".log-button");
const errorAlert = document.querySelector(".error");
const loader = document.querySelector(".loader");
const goodlogin = document.querySelector(".goodlogin");
const form = document.querySelector(".log-form");
logBtn.addEventListener("click", (e) => {
    e.preventDefault();

    let username = document.querySelector(".username_input").value;
    let password = document.querySelector(".password_input").value;

    if(username && password){
        form.style.display = "none";
        loader.style.display = "block";
        goodlogin.style.display = "none";
        let formData = new FormData();
        formData.append("login", "1");
        formData.append("username", username);
        formData.append("password", password);
        fetch("/uvlight/lib/includes/db/classes/trait.php", {
            method: 'POST',
            body: formData
        })
            .then((response) => response.json())
            .then((data) => {
                if(data === true){
                    currenturl = new URL(window.location.href);

                    if (currenturl.searchParams.get('next')) {
                        window.location.replace(currenturl.searchParams.get('next'));
                    }else{
                        window.location.replace("index.php");
                    }
                }else{
                    loader.style.display = "none";
                    errorAlert.innerHTML = "Nom d'utilisateur ou mot de passe incorrect";
                    errorAlert.style.display = "block";
                    form.style.display = "block";
                    goodlogin.style.display = "block";
                }
            })
    }else{
        errorAlert.innerHTML = "Veuillez remplir tout les champs";
        errorAlert.style.display = "block";
    }
});