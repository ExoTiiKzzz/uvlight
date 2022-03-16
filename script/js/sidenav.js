const sidenav = document.querySelector(".sidenav");
const closeBtn = document.querySelector(".fa-chevron-left");
const mainContainer = document.querySelector(".main-container");
const list = document.querySelector(".list");

closeBtn.addEventListener("click" , () => {
    sidenav.classList.toggle("inactive");
    closeBtn.classList.toggle("inactive");
    list.classList.toggle("inactive");
    mainContainer.classList.toggle("sidenav-open");
})



const urls = [
    {
        title: "article",
        class: "a_art"
    },
    {
        title: "casier",
        class: "a_cas"
    },
    {
        title: "sous_categorie",
        class: "a_scat"
    },
    {
        title: "categorie",
        class: "a_cat"
    },
    {
        title: "commande",
        class: "a_com"
    },
    {
        title: "vente",
        class: "a_ven"
    },
    {
        title: "tiers",
        class: "a_tie"
    }
]

let bool = true;
urls.forEach(el => {
    if (window.location.href.indexOf(el.title) > -1) {
        if(bool){
            document.querySelector("."+el.class).classList.add("active");
            bool = false;
        }
    }
})
