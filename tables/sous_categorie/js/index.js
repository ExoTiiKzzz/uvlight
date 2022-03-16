//create button

const createBtn = document.querySelector(".createBtn");

createBtn.addEventListener("click", (e) =>{

    let lib = document.querySelector(".createLib").value;
    let comment = document.querySelector(".createComment").value;
    let catref = document.querySelector(".ccatref").value;

    let formData = new FormData();
    formData.append("create", "1");
    formData.append("lib", lib);
    formData.append("comment", comment);
    formData.append("catref", catref);

    fetch(url,
        {
            method: "POST",
            body: formData
        }    
    )
    .then(response => response.json())
    .then(result => {
        if(result.error === true){
            errorHandler(result.errortext);
        }else{
            drawTable();
            document.querySelector(".createCloseBtn").click();
        }
    })
    .catch(err => errorHandler(err));
})

//updates buttons

function updateModal(event){
    const id = event.target.dataset.index;

    let formData = new FormData();
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
        if(result.error === true){
            errorHandler(result.errortext);
        }else{
            document.querySelector(".updateId").value = id;
            document.querySelector(".updateLib").value = result.content.cat_nom;
            document.querySelector(".updateComment").value = result.content.cat_description;
            document.querySelector(".ucatref").value = result.content.catref;
        }
    }).catch(err => errorHandler(err));
}