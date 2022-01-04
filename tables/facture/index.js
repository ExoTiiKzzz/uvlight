const quantitysEl = document.querySelectorAll(".quantite");
const selectTarif = document.querySelector(".selectTarif");
const remiseE = document.querySelector(".remiseE");
const remiseP = document.querySelector(".remiseP");

selectTarif.addEventListener("change", updateAll);

function updateAll(){
    let tarif = parseInt(selectTarif.value);
    quantitysEl.forEach(el => {
        let id = el.dataset.index +'-'+ tarif;
        let prixHT = prix[id].prix;
        document.querySelector(".prixHT[data-index='"+el.dataset.index+"']").innerText = prixht;
        document.querySelector(".prixTTC[data-index='"+el.dataset.index+"']").innerText = parseFloat(prixht +( prixht * document.querySelector(".taxe[data-index='"+id+"']").innerText / 100) ).toFixed(2);
        calculAll();
        calculRemiseP();
    })
}

quantitysEl.forEach(el => {
    el.addEventListener("input", checkQuantity);
    el.addEventListener("input", calculAll);
})

function checkQuantity(event){
    let max = event.target.max;
    let min = event.target.min;
    let value = event.target.value;
    if(parseInt(value)>max) event.target.value = max;
    if(parseInt(value) < 0) event.target.value = min;
    if(value == "") event.target.value = min;
}

function calculAll(){
    calculSousTotal();
    calculAvantRemise();
    calculTotalHT();
    calculTaxes();
    calculTTC();
}

function calculSousTotal(){
    quantitysEl.forEach(el => {
        let id =el.dataset.index;
        let quantite = el.value;
        let prixTTC = document.querySelector(".prixTTC[data-index='"+id+"']").innerText;
        let total = quantite * prixTTC;
        document.querySelector(".sousTotal[data-index='"+id+"']").innerText = parseFloat(total).toFixed(2);
    })
}

function calculAvantRemise(){
    let total = 0;
    quantitysEl.forEach(el => {
        total += el.value * document.querySelector(".prixHT[data-index='"+el.dataset.index+"']").innerText;
    });

    document.querySelector(".totalAvantHT").innerText = parseFloat(total).toFixed(2);
}

remiseE.addEventListener("input", calculRemiseP);
remiseP.addEventListener("input", calculRemiseE);

function calculRemiseP(){
    let el = document.querySelector(".remiseE");
    if(el.value < 0 || el.value === ""){
        el.value = 0;
    }

    if(el.value > max){
        el.value = max;
    }

    let result = el.value / +document.querySelector(".totalAvantHT").innerText * 100;
    document.querySelector(".remiseP").value = parseFloat(result).toFixed(2);
    calculAll();
}

function calculRemiseE(){
    let el = document.querySelector(".remiseP");
    if(el.value < 0 || el.value === ""){
        el.value = 0;
    }

    if(el.value > 100){
        el.value = 100;
    }

    let result = el.value / 100  * +document.querySelector(".totalAvantHT").innerText;
    document.querySelector(".remiseE").value = parseFloat(result).toFixed(2);
    calculAll();
}

function calculTotalHT(){
    let total = document.querySelector(".totalAvantHT").innerText - document.querySelector(".remiseE").value;

    document.querySelector(".totalHT").innerText = parseFloat(total).toFixed(2);
}

function calculTaxes(){
    let total = 0;
    document.querySelectorAll(".taxe").forEach(el => {
        ht = document.querySelector(".prixHT[data-index='"+el.dataset.index+"']").innerText;
       total += ( ht * el.innerText / 100 * document.querySelector(".quantite[data-index='"+el.dataset.index+"']").value);
    });
    document.querySelector(".totalTaxes").innerText = parseFloat(total).toFixed(2);
}

function calculTTC(){
    let total = +document.querySelector(".totalTaxes").innerText + +document.querySelector(".totalHT").innerText ;
    document.querySelector(".totalTTC").innerText = parseFloat(total).toFixed(2);
}

calculAll();



const form = document.querySelector("#upload-form");

form.addEventListener("submit", (e) => {
    e.preventDefault();
    let formData = new FormData(form);
    fetch(form.action, {
        method: "POST",
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            if(data.error === true){
                errorHandler(data.errortext);
            }else{
                const url = new URL(window.location.href);
                const id = url.searchParams.get('id')
                location.href = "./pdf.php?id="+data.content+"";
            }
        })
})



