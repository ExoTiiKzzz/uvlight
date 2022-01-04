function drawTable(){
    table.api().draw();
}

function errorHandler(errormessage){
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: errormessage
    })
}