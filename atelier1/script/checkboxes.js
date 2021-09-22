const checkboxes = document.querySelectorAll(".checkbox");
const deleteBtn = document.querySelector(".delete-all");
const checkboxall = document.querySelector(".select-all");

checkboxall.checked = false;

checkboxall.addEventListener("change", () => {
    if(checkboxall.checked){
        checkboxes.forEach(element => {
            element.checked = true;
        });
        deleteBtn.style.display = "inline-block";
    }else{
        checkboxes.forEach(element => {
            element.checked = false;
        });
        deleteBtn.style.display = "none";
    }
})

checkboxes.forEach(element => {
    element.checked = false;
    element.addEventListener("change", () => {
        var checked = false;
        checkboxes.forEach(el => {
            if(el.checked){
                checked = true;
            }
        })
        if(!checked){
            deleteBtn.style.display = 'none';
        }else{
            deleteBtn.style.display = 'inline-block';
        }
    });
});

deleteBtn.addEventListener("click", () => {
    var checkedboxes = [];
    checkboxes.forEach(checkbox => {
        if(checkbox.checked){
            var checkid = checkbox.dataset.index;
            checkedboxes.push(checkid);
        }
    });
    finalArray = JSON.stringify(checkedboxes);
    var formData = new FormData();
    formData.append("multi_delete", "");
    formData.append("array", finalArray);

    fetch(
        url, 
        { 
            method : 'POST',
            body : formData
        }
    ).then(response => response.json() ).then(result => {
        if(result == "ok"){
            checkedboxes.forEach(el => {
                var id = el;
                document.querySelector('tr[data-value="'+id+'"]').style.display = "none";
                document.querySelector('.checkbox[data-index="'+id+'"]').checked = false;
                deleteBtn.style.display = "none";
            });
        }
    })
})

