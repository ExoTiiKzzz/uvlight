const deleteBtns = document.querySelectorAll(".delete-btn, .delete-all");

deleteBtns.forEach(element => {
    element.addEventListener("click", (e) => {
        var res = confirm("Etes vous sûr de vouloir supprimer cet élément ?");
        if(!res){
            e.preventDefault();
        }
    })
});