const dropdownBtn = document.querySelector(".dropdown-container .button");
const dropDownEl = document.querySelector(".dropdown-container .dropdown");
const arrowDown = document.querySelector(".fa-chevron-down");

dropdownBtn.addEventListener("click" , () => {
    dropDownEl.classList.toggle("active");
    dropdownBtn.classList.toggle("active");
    arrowDown.classList.toggle("active");
})