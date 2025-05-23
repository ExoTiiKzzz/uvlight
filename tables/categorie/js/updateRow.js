const updateBtn = document.querySelector('.updateRowBtn');

function updateRow(){
    var id = document.querySelector(".updateId").value;
    var lib = document.querySelector(".updateLib").value;
    var comment = document.querySelector(".updateComment").value;

    var formData = new FormData();

    formData.append("update","1");
    formData.append("id", id);
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
            document.querySelector(".updateCloseBtn").click();
        }
    }).catch(err => errorHandler(err));
    
}

updateBtn.addEventListener("click", updateRow);