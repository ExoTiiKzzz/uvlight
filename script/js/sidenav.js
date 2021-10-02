const sidenav = document.querySelector(".sidenav");
const closeBtn = document.querySelector(".fa-chevron-left");
const mainContainer = document.querySelector(".main-container");

closeBtn.addEventListener("click" , () => {
    sidenav.classList.toggle("inactive");
    closeBtn.classList.toggle("inactive");
    mainContainer.classList.toggle("sidenav-open");
})