const deleteBtns = document.querySelectorAll(".delete-btn");

deleteBtns.forEach(element => {
    element.addEventListener("click", (e) => {
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
                var produitId = e.target.dataset.index;
                var formData = new FormData();
                formData.append("delete", "");
                formData.append("id", produitId);
        
                fetch(
                    url,
                    { 
                        method : 'POST',
                        body : formData
                    }
                )
                .then(response => response.json() ).then(result => {
                    console.log(result)
                    if(result.error === false){
                        deletetablerow(e.target.dataset.index);
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
    })
});