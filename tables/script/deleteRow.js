const deleteBtn = document.querySelector(".delete-all");
const deleteBtns = document.querySelectorAll(".delete-btn");

function deleteEventListener(e){
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger mr-4'
        },
        buttonsStyling: false
    })
        
    swalWithBootstrapButtons.fire({
        title: 'Etes-vous sûr?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Oui, supprimer les donées !',
        cancelButtonText: 'Non, annuler !',
        reverseButtons: true,
    })
    .then((result) => { 
        if (result.isConfirmed) {
            var id = e.target.dataset.index;
            var formData = new FormData();
            formData.append("delete", "1");
            formData.append("id", id);
    
            fetch(
                url,
                { 
                    method : 'POST',
                    body : formData
                }
            )
            .then(response => response.json() ).then(result => {
                if(result.error === false){
                    deleteRow(e.target.dataset.index);
                    swalWithBootstrapButtons.fire(
                        {
                            title: 'Supprimé',
                            text:  'Les données ont été suprimées',
                            showConfirmButton: false,
                            timer: 2000,
                            icon: 'success'
                        }
                    )
                }else{
                    swalWithBootstrapButtons.fire(
                        {
                            title: 'Erreur',
                            text:  result.errortext,
                            showConfirmButton: false,
                            timer: 5000,
                            icon: 'error'
                        }
                    )
                }
            })
            
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            swalWithBootstrapButtons.fire(
                {
                    title: 'Annulé',
                    text:  'Vos données n\' ont pas été supprimées',
                    showConfirmButton: false,
                    timer: 2000,
                    icon: 'error'
                }
            )
        }
    })
}

deleteBtns.forEach(element => {
    element.addEventListener("click", (e) => deleteEventListener(e));
});

deleteBtn.addEventListener("click", () => {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: 'btn btn-success',
          cancelButton: 'btn btn-danger mr-4'
        },
        buttonsStyling: false
    })
      
    swalWithBootstrapButtons.fire({
        title: 'Etes-vous sûr?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Oui, supprimer les donées !',
        cancelButtonText: 'Non, annuler !',
        reverseButtons: true,
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
            if(result == "ok"){
                checkedboxes.forEach(el => {
                    var id = el;
                    deleteRow(id);
                    deleteBtn.style.display = "none";
                });
            }
        })
        swalWithBootstrapButtons.fire(
            {
                title: 'Supprimé',
                text:  'Les données ont été suprimées',
                showConfirmButton: false,
                timer: 2000,
                icon: 'success'
            }
        )
    } else if (result.dismiss === Swal.DismissReason.cancel) {
        swalWithBootstrapButtons.fire(
            {
                title: 'Annulé',
                text:  'Vos données n\' ont pas été supprimées',
                showConfirmButton: false,
                timer: 2000,
                icon: 'error'
            }
        )
    }
    })
    
})
