const quantitysEl = document.querySelectorAll(".updateCommandQuant");

quantitysEl.forEach(el => {
    el.addEventListener("input", checkQuantity);
})

function checkQuantity(event){
    let max = event.target.max;
    let min = event.target.min;
    let value = event.target.value;
    if(parseInt(value)>max) event.target.value = max;
    if(parseInt(value) < 0) event.target.value = min;
    if(value == "") event.target.value = min;
}