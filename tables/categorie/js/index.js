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
            console.log(result.errortext);
        }else{
            table.api().order( [ 1, 'asc' ] ).draw();

            var existingid = result.existingid;

            var checkboxEl = document.createElement("td");
            var checkbox = document.querySelector(".checkbox[data-index='"+existingid+"']").cloneNode(true);
            checkbox.dataset.index = result.createdid;
            checkbox.addEventListener("change", checkBoxListener);

            checkboxEl.appendChild(checkbox);

            var tdid = document.createElement("td");
            tdid.innerHTML = "<center>"+result.createdid+"</center>";

            var tdlib = document.createElement("td");
            tdlib.innerHTML = "<center>"+lib+"</center>";

            var tdcomment = document.createElement("td");
            tdcomment.innerHTML = "<center>"+comment+"</center>";

            var update = document.querySelector(".updateBtn[data-index='"+existingid+"']").cloneNode(true);
            update.dataset.index = result.createdid;
            update.addEventListener("click", openUpdateModalListener);
            
            var deleteEl = document.querySelector(".delete-btn[data-index='"+existingid+"']").cloneNode(true);
            deleteEl.dataset.index = result.createdid;
            deleteEl.addEventListener("click", deleteEventListener);

            var td = document.createElement("td");
            td.style.display = "flex";
            td.style.justifyContent = "space-evenly";
            td.appendChild(update);
            td.appendChild(deleteEl);

            

            var newRow = document.createElement("tr");
            newRow.classList.add("tablerow");
            newRow.dataset.rowindex = result.createdid;
            newRow.dataset.value = result.createdid;
            newRow.appendChild(checkboxEl);
            newRow.appendChild(tdid);
            newRow.appendChild(tdlib);
            newRow.appendChild(tdcomment);
            newRow.appendChild(td);

            table.api().row.add(newRow);
            table.api().order( [ 1, 'asc' ] ).draw();

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

function openUpdateModalListener(e){
    const id = e.target.dataset.index;
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
            var comment = result.content.comment;

            document.querySelector(".updateId").value = id;
            document.querySelector(".updateLib").value = lib;
            document.querySelector(".updateComment").value = comment;
        }
    }).catch(err => console.log(err));
}