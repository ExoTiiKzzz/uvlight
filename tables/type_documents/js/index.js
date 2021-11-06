const createBtn = document.querySelector('.createBtn');

function deleteRow(index){
    if(isNaN(index)){
        return
    }

    var row = document.querySelector('tr[data-value="'+index+'"]');
    table.api().row(row).remove().draw();
}

createBtn.addEventListener("click", () => {
    var lib = document.querySelector(".createLib").value;
    var formData = new FormData();
    formData.append("lib", lib);
    formData.append("create", "1");

    fetch(url, {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.error === false){
            var newid = data.content;

            var checkboxEl = document.createElement("td");
            var checkbox = document.querySelector(".checkbox").cloneNode(true);
            checkbox.dataset.index = newid;
            checkbox.addEventListener("change", checkBoxListener);

            checkboxEl.appendChild(checkbox);

            var tdid = document.createElement("td");
            tdid.innerHTML = "<center>"+newid+"</center>";

            var tdlib = document.createElement("td");
            tdlib.innerHTML = "<center>"+lib+"</center>";

            var update = document.querySelector(".updateBtn").cloneNode(true);
            update.dataset.index = newid;
            
            var deleteEl = document.querySelector(".delete-btn").cloneNode(true);
            deleteEl.dataset.index = newid;
            deleteEl.addEventListener("click", () => alert(2));

            var td = document.createElement("td");
            td.style.display = "flex";
            td.style.justifyContent = "space-evenly";
            td.appendChild(update);
            td.appendChild(deleteEl);

            

            var newRow = document.createElement("tr");
            newRow.classList.add("tablerow");
            newRow.dataset.value = newid;
            newRow.appendChild(checkboxEl);
            newRow.appendChild(tdid);
            newRow.appendChild(tdlib);
            newRow.appendChild(td);

            table.api().row.add(newRow);
            table.api().order( [ 1, 'asc' ] ).draw();

            document.querySelector(".createLib").value = "";
        }else{
            console.log(data.errortext);
        }
    })
})

const updateBtns = document.querySelectorAll(".updateBtn");

updateBtns.forEach(element => {
    element.addEventListener("click", updateModal);
});

function updateModal(e){
    let id = e.target.dataset.index;

    var formData = new FormData();
    formData.append("id", id);
    formData.append("getdata", "1");

    fetch(url,{
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.error === false){
            document.querySelector(".updateLib").value = data.content.typdo_lib;
            updateBtn.dataset.index = id;
        }else{
            console.log(data.errortext);
        }
    })

}
