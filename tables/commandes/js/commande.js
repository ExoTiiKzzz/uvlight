const trait_url = "trait.php";
const updateCommandBtn = document.querySelector(".updateCommandBtn");
const quantitysEl = document.querySelectorAll(".updateCommandQuant");
updateCommandBtn.addEventListener("click", updateCommand);

function updateCommand(){
    let array = [];
    let cpt = 0;
    let comment = document.querySelector(".comment").value;
    quantitysEl.forEach(el => {
        if(parseInt(el.value) !== 0){
            array[cpt] = [el.dataset.artid, el.value];
            cpt++;
        }
    })

    let url_string = window.location.href;
    let url = new URL(url_string);
    let id = url.searchParams.get("id");
    console.log(array);
    array = JSON.stringify(array);

    let formData = new FormData();
    formData.append("updateCommand", "1");
    formData.append("array", array);
    formData.append("com_ID", id);
    formData.append("comment", comment);

    fetch(trait_url, {
        method: "POST",
        body: formData
    })
        .then(res=> res.json())
        .then(data => {
            console.log(data);
            if(data.error === true){
                console.log(data.errortext);
            }else{
                table.api().draw();
            }
        })
}

quantitysEl.forEach(el => {
    el.addEventListener("input", checkQuantity);
})

function checkQuantity(event){
    let max = event.target.max;
    let value = event.target.value;
    if(parseInt(value)>max) event.target.value = max;
    if(parseInt(value) < 0) event.target.value = 0;
    if(value == "") event.target.value = 0;
}