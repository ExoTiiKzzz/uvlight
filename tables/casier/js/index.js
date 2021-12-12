function deleteRow(index){
    if(isNaN(index)){
        return
    }

    var row = document.querySelector('tr[data-value="'+index+'"]');
    table.api().row(row).remove().draw();
}

const createBtn = document.querySelector(".createBtn");

createBtn.addEventListener("click", (e) =>{

    var lib = document.querySelector(".createLib").value;

    var formData = new FormData();
    formData.append("create", "1");
    formData.append("lib", lib);

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
            location.reload();
        }
    })
    .catch(err => console.log(err));
})

//updates buttons

const updateBtns = document.querySelectorAll(".updateBtn");

updateBtns.forEach(element => {
    element.addEventListener("click", openUpdateModalListener);
});

function openUpdateModalListener(e){
    const id = e.target.dataset.index;

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
            console.log(result.errortext);
        }else{
            var lib = result.content.lib;

            document.querySelector(".updateId").value = id;
            document.querySelector(".updateLib").value = lib;
        }
    }).catch(err => console.log(err));
}