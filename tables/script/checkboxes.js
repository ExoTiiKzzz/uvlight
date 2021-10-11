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
        var allchecked = true;
        checkboxes.forEach(el => {
            if(el.checked){
                checked = true;
            }else{
                checkboxall.checked = false;
                allchecked = false;
            }
        })
        if(!checked){
            deleteBtn.style.display = 'none';
        }else{
            deleteBtn.style.display = 'inline-block';
        }
        if(allchecked){
            checkboxall.checked = true;
        }else{
            checkboxall.checked = false;
        }
    });
});

deleteBtn.addEventListener("click", () => {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: 'btn btn-success',
          cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })
      
    swalWithBootstrapButtons.fire({
        title: 'Etes-vous sûr?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Oui, supprimer les donées !',
        cancelButtonText: 'Non, annuler !',
        reverseButtons: true
    }).then((result) => {
    if (result.isConfirmed) {
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
            console.log(result);
            if(result == "ok"){
                checkedboxes.forEach(el => {
                    var id = el;
                    document.querySelector('tr[data-value="'+id+'"]').style.display = "none";
                    document.querySelector('.checkbox[data-index="'+id+'"]').checked = false;
                    deleteBtn.style.display = "none";
                });
            }
        })
        swalWithBootstrapButtons.fire(
        'Supprimé',
        'Les données ont été suprimées',
        'success'
        )
    } else if (result.dismiss === Swal.DismissReason.cancel) {
        swalWithBootstrapButtons.fire(
            'Annulé',
            'Vos données n\' ont pas été supprimées',
            'error'
        )
    }
    })
    
})

