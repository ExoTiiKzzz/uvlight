const updateBtn = document.querySelector('.updateRowBtn');

function updateRow(){
    let id = document.querySelector(".updateId").value;
    let lib = document.querySelector(".updateLib").value;
    let comment = document.querySelector(".updateComment").value;
    let catref = document.querySelector(".ucatref").value;

    let formData = new FormData();

    formData.append("update","1");
    formData.append("id", id);
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
            document.querySelector(".updateCloseBtn").click();
        }
    }).catch(err => errorHandler(err));
    
}

updateBtn.addEventListener("click", updateRow);