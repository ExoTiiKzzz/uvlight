const updateBtn = document.querySelector('.updateSaveBtn');

function updateRow(e){
    var id = e.target.dataset.index;
    var lib = document.querySelector(".updateLib").value;

    var formData = new FormData();

    formData.append("update","1");
    formData.append("id", id);
    formData.append("lib", lib);

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

            var checkbox = document.createElement("td");
            checkbox.appendChild(document.querySelector(".checkbox"));

            var tdid = document.createElement("td");
            tdid.innerHTML = "<center>"+id+"</center>";

            var tdlib = document.createElement("td");
            tdlib.innerHTML = "<center>"+lib+"</center>";

            var update = document.querySelector(".updateBtn");
            
            var deleteEl = document.querySelector(".delete-btn");

            var td = document.createElement("td");
            td.style.display = "flex";
            td.style.justifyContent = "space-evenly";
            td.appendChild(update);
            td.appendChild(deleteEl);

            

            var newRow = document.createElement("tr");
            newRow.classList.add("tablerow");
            newRow.dataset.rowindex = id;
            newRow.dataset.value = id;
            newRow.appendChild(checkbox);
            newRow.appendChild(tdid);
            newRow.appendChild(tdlib);
            newRow.appendChild(td);


            var row = document.querySelector('tr[data-value="'+id+'"]');

            table.api().row.add(newRow);
            table.api().row(row).remove();
            table.api().order( [ 1, 'asc' ] ).draw();
            document.querySelector(".updateCloseBtn").click();
        }
    }).catch(err => console.log(err));

    
}

updateBtn.addEventListener("click", updateRow);