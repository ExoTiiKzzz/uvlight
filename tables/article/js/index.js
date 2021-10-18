const createBtn = document.querySelector(".createBtn");

function deleteRow(index){
    if(isNaN(index)){
        return
    }

    var row = document.querySelector('tr[data-value="'+index+'"]');
    table.api().row(row).remove().draw();
}

createBtn.addEventListener("click", (e) =>{
    e.preventDefault();

    var lib = document.querySelector(".createLib").value;
    var comment = document.querySelector(".createComment").value;
    var cas = document.querySelector(".createCas").value;
    var cat = document.querySelector(".createCat").value;

    var formData = new FormData();
    formData.append("create", "1");
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
        console.log(result);
        if(result.error === true){
            console.log(result.errortext);
        }else{
            table.api().order( [ 1, 'asc' ] ).draw();

            var id = result.existingid;

            var checkboxEl = document.createElement("td");
            var checkbox = document.querySelector(".checkbox[data-index='"+id+"']").cloneNode(true);
            checkbox.dataset.index = result.createdid;
            checkbox.addEventListener("change", checkBoxListener);

            checkboxEl.appendChild(checkbox);

            var tdid = document.createElement("td");
            tdid.innerHTML = "<center>"+result.createdid+"</center>";

            var tdlib = document.createElement("td");
            tdlib.innerHTML = "<center>"+lib+"</center>";

            var tdcomment = document.createElement("td");
            tdcomment.innerHTML = "<center>"+comment+"</center>";

            var tdcasier = document.createElement("td");
            tdcasier.innerHTML = "<center>"+result.cas+"</center>";

            var tdcat = document.createElement("td");
            tdcat.innerHTML = "<center>"+result.cat+"</center>";

            var update = document.querySelector(".updateBtn[data-index='"+id+"']").cloneNode(true);
            update.dataset.index = result.createdid;
            
            var deleteEl = document.querySelector(".delete-btn[data-index='"+id+"']").cloneNode(true);
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
            newRow.appendChild(tdcasier);
            newRow.appendChild(tdcat);
            newRow.appendChild(td);

            table.api().row.add(newRow);
            table.api().order( [ 1, 'asc' ] ).draw();

            document.querySelector(".createCloseBtn").click();
        }
    })
    .catch(err => console.log(err));
})