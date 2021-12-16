//create button

const createBtn = document.querySelector(".createBtn");

createBtn.addEventListener("click", (e) =>{

    var lib = document.querySelector(".createLib").value;
    var categorie = document.querySelector(".categorie").value;
    var id = document.querySelector(".updateId").value;

    console.log(categorie);

    var formData = new FormData();
    formData.append("create", "1");
    formData.append("id", id);
    formData.append("lib", lib);
    formData.append("categorie", categorie);

    fetch(url,
        {
            method: "POST",
            body: formData
        }    
    )
    .then(response => response.json())
    .then(result => {
        if(result.error === true){
            console.log(result.errortext);
        }else{
            drawTable();
            document.querySelector(".createCloseBtn").click();
        }
    })
    .catch(err => console.log(err));
})

//updates buttons

const updateBtns = document.querySelectorAll(".updateBtn");

updateBtns.forEach(element => {
    element.addEventListener("click", openUpdateModalListener);
});

function openUpdateModalListener(event){
    const id = event.target.dataset.index;
    console.log(id)

    var formData = new FormData();
    formData.append("getData", "1");
    formData.append("id", id);

    fetch(url,
        {
            method: "POST",
            body: formData
        }
    )
    .then(response => response.json())
    .then(result => {
        console.log(result)
        if(result.error === true){
            console.log(result.errortext);
        }else{
            var lib = result.content.lib;
            var categorie = result.content.categorie;

            document.querySelector(".updateLib").value = lib;
            document.querySelector(".updateCat").value = categorie;
            document.querySelector(".updateId").value = id;
        }
    }).catch(err => console.log(err));
}