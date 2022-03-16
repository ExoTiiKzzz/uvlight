const updateBtn = document.querySelector('.updateBtn');

function updateRow(){
    let updateForm = document.querySelector(".updateForm");
    let formData = new FormData(updateForm);

    formData.append("update","1");
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