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

            var existingid = document.querySelector("tr.odd").dataset.value;

            var checkboxEl = document.createElement("td");
            var checkbox = document.querySelector(".checkbox[data-index='"+existingid+"']").cloneNode(true);
            checkbox.dataset.index = id;
            checkbox.addEventListener("change", checkBoxListener);

            checkboxEl.appendChild(checkbox);

            var tdid = document.createElement("td");
            tdid.innerHTML = "<center>"+id+"</center>";

            var tdlib = document.createElement("td");
            tdlib.innerHTML = "<center>"+lib+"</center>";

            var tdcomment = document.createElement("td");
            tdcomment.innerHTML = "<center>"+comment+"</center>";

            var update = document.querySelector(".updateBtn[data-index='"+existingid+"']").cloneNode(true);
            update.dataset.index = id;
            
            var deleteEl = document.querySelector(".delete-btn[data-index='"+existingid+"']").cloneNode(true);
            deleteEl.dataset.index = id;
            deleteEl.addEventListener("click", deleteEventListener);

            var td = document.createElement("td");
            td.style.display = "flex";
            td.style.justifyContent = "space-evenly";
            td.appendChild(update);
            td.appendChild(deleteEl);

            
            var row = document.querySelector("tr[data-value='"+id+"']");
            table.api().row(row).remove();

            var newRow = document.createElement("tr");
            newRow.classList.add("tablerow");
            newRow.dataset.value = id;
            newRow.appendChild(checkboxEl);
            newRow.appendChild(tdid);
            newRow.appendChild(tdlib);
            newRow.appendChild(tdcomment);
            newRow.appendChild(td);

            table.api().row.add(newRow);
            table.api().order( [ 1, 'asc' ] ).draw();
            document.querySelector(".updateCloseBtn").click();
        }
    }).catch(err => errorHandler(err));
    
}

updateBtn.addEventListener("click", updateRow);