var checkboxes = document.querySelectorAll(".checkbox");
const checkboxall = document.querySelector(".select-all");

checkboxall.checked = false;

checkboxall.addEventListener("change", () => {
    var newcheckboxes = document.querySelectorAll(".checkbox");
    if(checkboxall.checked){
        newcheckboxes.forEach(element => {
            element.checked = true;
        });
        deleteBtn.style.display = "inline-block";
    }else{
        newcheckboxes.forEach(element => {
            element.checked = false;
        });
        deleteBtn.style.display = "none";
    }
})

checkboxes.forEach(element => {
    element.checked = false;
    element.addEventListener("change", checkBoxListener);
});

function checkBoxListener(){
    var checked = false;
    var allchecked = true;
    var newcheckboxes = document.querySelectorAll(".checkbox");
    newcheckboxes.forEach(el => {
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
}



