function getCookie(name) {
    var match = document.cookie.match(RegExp('(?:^|;\\s*)' + name + '=([^;]*)')); 
    return match ? match[1] : null;
}

function delete_cookie(name) {
    document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}

var cookieName = "user_jwt";
const form = document.querySelector('form');
const loader = document.querySelector(".loader");
const goodlogin = document.querySelector(".goodlogin");

if(getCookie(cookieName)){
    var formData = new FormData();
    formData.append("checkjwt", "jhvh");
    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then((response) => response.json())
    .then((text) => {
        form.style.display = "none";
        loader.style.display = "block";
        setTimeout(() => {
            if(text === true){
                window.location.replace("index.php");
            }else{
                delete_cookie(cookieName);
                form.style.display = "block";
                loader.style.display = "none";
            }
        }, 1500);
        
        
    })
}