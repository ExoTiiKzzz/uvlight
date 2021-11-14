const updateBtn = document.querySelector('.updateRowBtn');
console.log(updateBtn)

function updateRow(){
    var id = document.querySelector(".updateId").value;
    var lib = document.querySelector(".updateLib").value;
    var comment = document.querySelector(".updateComment").value;
    var cas = document.querySelector(".updateCas").value;
    var cat = document.querySelector(".updateCat").value;

    var formData = new FormData();

    formData.append("update","1");
    formData.append("id", id);
    formData.append("lib", lib);
    formData.append("comment", comment);
    formData.append("cas", cas);
    formData.append("cat", cat);

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