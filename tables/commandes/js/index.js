const createBtn = document.querySelector('.createBtn');
const addArticle = document.querySelector(".addArticle");

addArticle.addEventListener("click", (e) => {
    var id = parseInt(e.target.dataset.index) + 1;
    var row = document.createElement("div");
    row.classList.add("articleRow");
    row.classList.add("row");
    row.dataset.index = id;
    row.innerHTML = `<div class="form-group col-5"><label>Article</label><input class="form-control article" data-index="${id}" style="margin: 0 auto" list="liste_articles" ></div>`+
                    `<div class="form-group col-5"><label>Quantité</label><input class="form-control quantite" data-index="${id}" style="margin: 0 auto"></div>`+
                    `<div class="form-group col-2"><label>Supprimer</label><button class="form-control btn btn-danger" onclick="deleteArticle(${id})">X</button></div>`;
    document.querySelector(".articles-container").appendChild(row);
    e.target.dataset.index = id;
})

function deleteArticle(index){
    document.querySelector(".articles-container").removeChild(document.querySelector(".row[data-index='"+index+"']"));
}

function deleteRow(index){
    if(isNaN(index)){
        return
    }

    var row = document.querySelector('tr[data-value="'+index+'"]');
    table.api().row(row).remove().draw();
}

createBtn.addEventListener("click", () => {
    let comment = document.querySelector(".createComment");
    var articles = [], quantites = [];
    var elements = document.querySelectorAll(".articleRow");
    elements.forEach(element => {
        var id = element.dataset.index;
        var article = document.querySelector(".article[data-index='"+id+"']").value;
        if(article !== null) articles[id] = article;
        var quantite = document.querySelector(".quantite[data-index='"+id+"']").value;
        if(quantite !== null) quantites[id] = quantite;
    });
    var formData = new FormData();
    formData.append("achat", "1");
    formData.append("comment", comment);
    formData.append("articles", articles);
    formData.append("quantites", quantites);
    console.log(articles, quantites);

    // fetch(url, {
    //     method: "POST",
    //     body: formData
    // })
    // .then(res => res.json())
    // .then(data => {
    //     if(data.error === false){
            
    //     }else{
    //         console.log(data.errortext);
    //     }
    // })
})

const updateBtns = document.querySelectorAll(".updateBtn");

function updateModal(event){
    let id = event.target.dataset.index;

    var formData = new FormData();
    formData.append("id", id);
    formData.append("getdata", "1");

    fetch(url,{
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.error === false){
            document.querySelector(".seeDocumentsSpan").style.display = "none";
            document.querySelector(".seeDocuments").style.display = "block";
            document.querySelector(".documents").innerHTML = "";
            document.querySelector(".select_etat").innerHTML = data.content;
            document.querySelector(".seeDocuments").dataset.index = id;
            updateBtn.dataset.index = id;
        }else{
            console.log(data.errortext);
        }
    })

}

const seeDocumentsBtn = document.querySelector(".seeDocuments");
seeDocumentsBtn.addEventListener("click", seeDocuments);

function seeDocuments(event){
    let id = event.target.dataset.index;
    console.log(document.querySelector(".seeDocuments"))
    document.querySelector(".seeDocuments").href = "./vente.php?id="+id+"";
}
