const updateBtn = document.querySelector('.updateSaveBtn');

function updateRow(e){
    var id = e.target.dataset.index;
    var etat = document.querySelector(".select_etat").value;

    var formData = new FormData();

    formData.append("update","1");
    formData.append("id", id);
    formData.append("etat", etat);

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
            drawTable();
            document.querySelector(".updateCloseBtn").click();
        }
    }).catch(err => console.log(err));

    
}

updateBtn.addEventListener("click", updateRow);