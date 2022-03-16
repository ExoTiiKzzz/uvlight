//create button

const createBtn = document.querySelector(".createBtn");

createBtn.addEventListener("click", (e) =>{

    var lib = document.querySelector(".createLib").value;
    var comment = document.querySelector(".createComment").value;

    var formData = new FormData();
    formData.append("create", "1");
    formData.append("lib", lib);
    formData.append("comment", comment);

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
        }
    })
    .catch(err => errorHandler(err));
})

//updates buttons

function updateModal(event){
    const id = event.target.dataset.index;

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
        if(result.error === true){
            errorHandler(result.errortext);
        }else{
            var lib = result.content.lib;
            var comment = result.content.comment;

            document.querySelector(".updateId").value = id;
            document.querySelector(".updateLib").value = lib;
            document.querySelector(".updateComment").value = comment;
        }
    }).catch(err => errorHandler(err));
}