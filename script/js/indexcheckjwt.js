function getCookie(name) {
    var match = document.cookie.match(RegExp('(?:^|;\\s*)' + name + '=([^;]*)')); 
    return match ? match[1] : null;
}

function delete_cookie(name) {
    document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}

var cookieName = "user_jwt";

if(getCookie(cookieName)){
    var formData = new FormData();
    formData.append("checkjwt", "jhvh");
    fetch("lib/includes/db/classes/trait.php", {
        method: 'POST',
        body: formData
    })
    .then((response) => response.json())
    .then((text) => {
        if(text != true){
            window.location.replace("login.php");
            delete_cookie(cookieName);
        }
    })
}else{
    window.location.replace("login.php");
}