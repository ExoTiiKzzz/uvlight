const logoutBtn = document.querySelector(".logout");

var cookieName = "user_jwt";

logoutBtn.addEventListener("click", () => {
    delete_cookie(cookieName);
    window.location.replace("login.php");
})