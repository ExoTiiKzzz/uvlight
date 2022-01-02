var table = $('#table');
var fourniListe;
$(document).ready(function(){
    table.dataTable({
        processing: true,
        serverSide: true,
        serverMethod: 'post',
        ajax: {
            'url':'ajaxfile.php'
        },
        columns: [
            { data: 'checkbox'},
            { data: 'art_id' },
            { data: 'art_nom' },
            { data: 'art_commentaire' },
            { data: 'stock' },
            { data: 'fournisseur'},
            { data: 'categorie' },
            { data: 'casier' },
            { data: 'actions'}
        ],
        deferRender:    true,
        scrollCollapse: true,
        scroller:       true
    });
    document.querySelector(".searchInput").focus();
    emptyInputs();
});

function emptyInputs(){
    document.querySelectorAll("input").forEach(input => {
        input.value = "";
    })

}


const createBtn = document.querySelector(".createBtn");
const openCreateBtn = document.querySelector(".openCreateBtn");
openCreateBtn.addEventListener("click", openCreateModalListener);
function openCreateModalListener(){
    document.querySelector(".createBtn").innerText = "Créer";
    document.querySelector(".createBtn").dataset.action = "create";
    document.querySelector(".createIsProduit").disabled = false;
    document.querySelector(".createIsProduit").checked = false;
    document.querySelector(".produitArticleContainer").classList.remove("active");
    document.querySelector(".seeTarifs").style.display = "none";
    document.querySelector(".articlesContainer").innerHTML = "";
    addArticleListener();
}
createBtn.addEventListener("click", (e) =>{
    e.preventDefault();

    var lib = document.querySelector(".createLib").value;
    var fournisseur = document.querySelector(".createFourni").value;
    var comment = document.querySelector(".createComment").value;
    var cas = document.querySelector(".createCas").value;
    var cat = document.querySelector(".createCat").value;




    var formData = new FormData();
    formData.append(document.querySelector(".createBtn").dataset.action, "1");
    if(document.querySelector(".createBtn").dataset.action == "update"){
        formData.append("id", document.querySelector(".updateId").value);
    }
    formData.append("fournisseur", fournisseur);
    formData.append("lib", lib);
    formData.append("comment", comment);
    formData.append("cas", cas);
    formData.append("cat", cat);
    if(document.querySelector(".createIsProduit").checked === true){
        let articlesEls = document.querySelectorAll(".createArtLib");
        let articles = [];
        articlesEls.forEach(el => {
            formData.append("articles[]", el.value);
            formData.append("quantitys[]", document.querySelector(".createArtQte[data-index='"+el.dataset.index+"']").value);
        });
    }
    fetch(url,
        {
            method: "POST",
            body: formData
        }
    )
    .then(response => response.json())
    .then(result => {
        if(result.error === true){
            console.log(result.errortext);
        }else{
            drawTable();
            document.querySelector(".createCloseBtn").click();
            emptyInputs();
            updateListeArticle(lib);
        }
    })
    .catch(err => console.log(err));
})


//updates buttons

const updateBtns = document.querySelectorAll(".updateBtn");

updateBtns.forEach(element => {
    element.addEventListener("click", openUpdateModalListener);
});

function openUpdateModalListener(e){
    const id = e.target.dataset.index;

    var formData = new FormData();
    formData.append("getData", "1");
    formData.append("id", id);

    document.querySelector(".createBtn").innerText = "Enregistrer";

    fetch(url,
        {
            method: "POST",
            body: formData
        }
    )
    .then(response => response.json())
    .then(result => {
        if(result.error === true){
            console.log(result.errortext);
        }else{
            let lib = result.content.lib;
            let comment = result.content.comment;
            let cas = result.content.cas;
            let cat = result.content.cat;
            let fourni = result.content.fourni;
            let isComposed = result.content.isComposed;

            if(isComposed === 1){

                document.querySelector(".createIsProduit").checked = true;
                document.querySelector(".createIsProduit").disabled = true;
                document.querySelector(".produitArticleContainer").classList.add("active");
                let articles = result.content.articles;
                let index = 0;
                document.querySelector(".articlesContainer").innerHTML = "";
                articles.forEach(el => {
                    let content = "<div class=\"form-group col-7\">\n" +
                        "<input class=\"form-control createArtLib\" list=\"all_articles\" value='"+articles[index]["article"]+"' placeholder=\"Article\" data-index=\""+index+"\" autocomplete=\"off\">\n" +
                        "</div>\n" +
                        "<div class=\"form-group col-3\">\n" +
                        "<input placeholder=\"Quantité\" class=\"form-control createArtQte\" value='"+articles[index]["quantite"]+"' data-index=\""+index+"\">\n" +
                        "</div>\n" +
                        "<div class=\"form-group col-2\">\n" +
                        "<button class=\"btn btn-danger form-control deleteArticleRow\" data-index=\""+index+"\">&times;</button>\n" +
                        "</div>\n";
                    let row = document.createElement("div");
                    row.classList.add("row")
                    row.dataset.index = index;
                    row.innerHTML = content;
                    document.querySelector(".articlesContainer").appendChild(row);
                    document.querySelector(".deleteArticleRow[data-index='"+index+"']").addEventListener("click", deleteArticle);
                    index++;
                })
            }
            document.querySelector(".modalTitle").innerText = "Modifier l'article " + lib;
            document.querySelector(".updateId").value = id;
            document.querySelector(".createFourni").value = fourni;
            document.querySelector(".createFourni").disabled = true;
            document.querySelector(".createLib").value = lib;
            document.querySelector(".createComment").value = comment;
            document.querySelector(".createCas").value = cas;
            document.querySelector(".createCat").value = cat;
            document.querySelector(".seeTarifs").dataset.index = id;
            document.querySelector(".seeTarifs").classList.add("active");
            document.querySelector(".createBtn").dataset.action = "update";

            result.content.tarifs.forEach(el => {
                document.querySelector(".updateTarifInput[data-index='"+el.tar_ID+"']").value = el.prix;
            });
        }
    }).catch(err => console.log(err));
}

const commandBtn = document.querySelector(".commandBtn");

commandBtn.addEventListener("click", (e) => {
    let comment = document.querySelector(".createCommandComment").value;
    console.log(comment);
    let articles = [], quantitys = [];
    let fourni = document.querySelector(".commandFourni").value;

    articleEls = document.querySelectorAll(".commandArticle");

    let bool = true;


    articleEls.forEach(article => {
        let index = article.dataset.index;
        articles[index] = article.value;
        quantitys[index] = document.querySelector(".commandQuantite[data-index='"+index+"']").value;
    })

    // console.log(articles, quantitys);

    let formData = new FormData();
    formData.append("command", "1");
    formData.append("comment", comment);
    formData.append("article", JSON.stringify(articles));
    formData.append("quantity", JSON.stringify(quantitys));
    formData.append("tiers", fourni);

    fetch(url, {
        method: "POST",
        body: formData
    })
        .then(result => result.json())
        .then(data => {
            if(data.error === false){
                drawTable();
                document.querySelector(".commandCloseBtn").click();
                document.querySelectorAll(".row").forEach(el => {
                    document.querySelector(".commandListArticles").removeChild(el);
                })
                commandAddArticle(getMaxCommandArticleIndex());
            }else{
                console.log(data.errortext);
            }
        })

})

const commandAddArticleBtn = document.querySelector(".commandAddArticleBtn");

commandAddArticleBtn.addEventListener("click", (e) => {
    let id = getMaxCommandArticleIndex();
    commandAddArticle(id);
})

function getMaxCommandArticleIndex(){
    let articles = document.querySelectorAll(".commandArticle");
    let maxindex;
    articles[articles.length - 1] === undefined ? maxindex = 0 : maxindex = parseInt(articles[articles.length - 1].dataset.index) + 1;


    return maxindex;
}

function commandAddArticle(index){
    let textToAdd = '<div class="form-group col-5">' +
                        '<label for="article">Nom de l\'article : </label>' +
                        '<input placeholder="Nom de l\'article" class="form-control commandArticle"' +
                                'style="margin: 0 auto" type="text" list="liste_articles" data-index="'+index+'">' +
                    '</div>' +
                    '<div class="form-group col-4">' +
                        '<label for="article">Quantité souhaitée : </label>' +
                        '<input placeholder="Quantité" class="form-control commandQuantite"' +
                                'style="margin: 0 auto" type="number" data-index="'+index+'">' +
                    '</div>'+
                    '<div class="form-group col-3" >'+
                            '<label> Retirer </label>'+
                            '<button class="form-control btn btn-danger commandDeleteArticle" onClick="commandDeleteArticle('+index+')">X</button>'+
                    '</div>';

    let row = document.createElement("div");
    row.classList.add("row");
    row.classList.add("commandRow");
    row.dataset.index = index;
    row.innerHTML = textToAdd;

    document.querySelector(".commandListArticles").appendChild(row);
}

function commandDeleteArticle(index){
    document.querySelector(".commandListArticles").removeChild(document.querySelector(".row[data-index='"+index+"']"));
}

let commandTiers = document.querySelector(".commandFourni");
commandTiers.addEventListener("blur", loadFourniList);

function loadFourniList(event){
    if(event.target.value == ""){
        return;
    }
    let fourni = event.target.value;

    var formData = new FormData();
    formData.append("getFourniArticles", "1");
    formData.append("fournisseur", fourni);

    fetch(url, {
        method: "POST",
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            if(data.error === true){
                document.querySelector(".commandBtn").disabled = true;
                if(typeof data.code !== undefined){
                    if(data.code === 404){
                        document.getElementById("liste_articles").innerHTML = "<option value='Aucun article'></option>";
                    }
                }
                console.log(data.errortext);
            }else{
                document.querySelector(".commandBtn").removeAttribute("disabled");
                let list = "";
                fourniListe = data.content;
                fourniListe.forEach(el => {
                    list += "<option value='"+el.art_nom+"'></option>";
                });
                document.getElementById("liste_articles").innerHTML = list;
            }
        })
}

const updateTarifEls = document.querySelectorAll(".updateTarifInput");
updateTarifEls.forEach(el => {
    el.addEventListener("blur", updateTarif);
})

function updateTarif(event){
    let art_ID = parseInt(document.querySelector(".seeTarifs").dataset.index);
    let tar_ID = parseInt(event.target.dataset.index);
    let prix = parseFloat(event.target.value);
    let formData = new FormData();
    formData.append("updateTarif", "1");
    formData.append("art_ID", art_ID);
    formData.append("tar_ID", tar_ID);
    formData.append("prix", prix);

    fetch(url, {
        method: "POST",
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            if(data.error === true){
                console.log(data.errortext);
            }
        })
}


const isProduitBtn = document.querySelector(".createIsProduit");
isProduitBtn.addEventListener("change",() => {
    document.querySelector(".produitArticleContainer").classList.toggle("active");
})

function deleteArticle(event){
    let row = document.querySelector(".articlesContainer .row[data-index='"+event.target.dataset.index+"']");
    document.querySelector(".articlesContainer").removeChild(row);
}

const addArticleBtn = document.querySelector(".addArticleBtn");
var index = 1;
addArticleBtn.addEventListener("click", addArticleListener);

function addArticleListener(){
    let content = "<div class=\"form-group col-7\">\n" +
        "<input class=\"form-control createArtLib\" list=\"all_articles\" placeholder=\"Article\" data-index=\""+index+"\" autocomplete=\"off\">\n" +
        "</div>\n" +
        "<div class=\"form-group col-3\">\n" +
        "<input placeholder=\"Quantité\" class=\"form-control createArtQte\" data-index=\""+index+"\">\n" +
        "</div>\n" +
        "<div class=\"form-group col-2\">\n" +
        "<button class=\"btn btn-danger form-control deleteArticleRow\" data-index=\""+index+"\">&times;</button>\n" +
        "</div>\n";
    let row = document.createElement("div");
    row.classList.add("row")
    row.dataset.index = index;
    row.innerHTML = content;
    document.querySelector(".articlesContainer").appendChild(row);
    document.querySelector(".deleteArticleRow[data-index='"+index+"']").addEventListener("click", deleteArticle);
    index++;
}





//sale functions

const saleBtn = document.querySelector(".saleBtn");

saleBtn.addEventListener("click", (e) => {
    let comment = document.querySelector(".createSaleComment").value;
    let articles = [], quantitys = [];
    let client = document.querySelector(".saleClient").value;

    articleEls = document.querySelectorAll(".saleArticle");

    let bool = true;


    articleEls.forEach(article => {
        let index = article.dataset.index;
        articles[index] = article.value;
        quantitys[index] = document.querySelector(".saleQuantite[data-index='"+index+"']").value;
    })


    let formData = new FormData;
    formData.append("sale", "1");
    formData.append("comment", comment);
    formData.append("article", JSON.stringify(articles));
    formData.append("quantity", JSON.stringify(quantitys));
    formData.append("tiers", client);

    fetch(url, {
        method: "POST",
        body: formData
    })
        .then(result => result.json())
        .then(data => {
            if(data.error === false){
                drawTable();
                document.querySelector(".saleCloseBtn").click();
                document.querySelectorAll(".saleRow").forEach(el => {
                    document.querySelector(".saleListArticles").removeChild(el);
                })
                saleAddArticle(getMaxSaleArticleIndex());
                saleAddArticle(getMaxSaleArticleIndex());
            }else{
                console.log(data.errortext);
            }
        })

})

const saleAddArticleBtn = document.querySelector(".saleAddArticleBtn");

saleAddArticleBtn.addEventListener("click", (e) => {
    let id = getMaxSaleArticleIndex();
    saleAddArticle(id);
})

function getMaxSaleArticleIndex(){
    let articles = document.querySelectorAll(".saleArticle");
    let maxindex;
    articles[articles.length - 1] === undefined ? maxindex = 0 : maxindex = parseInt(articles[articles.length - 1].dataset.index) + 1;


    return maxindex;
}

function saleAddArticle(index){
    let textToAdd = '<div class="form-group col-5">' +
        '<label for="article">Nom de l\'article : </label>' +
        '<input placeholder="Nom de l\'article" class="form-control saleArticle"' +
        'style="margin: 0 auto" type="text" list="all_articles" data-index="'+index+'">' +
        '</div>' +
        '<div class="form-group col-4">' +
        '<label for="article">Quantité souhaitée : </label>' +
        '<input placeholder="Quantité" class="form-control saleQuantite"' +
        'style="margin: 0 auto" type="number" data-index="'+index+'">' +
        '</div>'+
        '<div class="form-group col-3" >'+
        '<label> Retirer </label>'+
        '<button class="form-control btn btn-danger saleDeleteArticle" onClick="saleDeleteArticle('+index+')">X</button>'+
        '</div>';

    let row = document.createElement("div");
    row.classList.add("saleRow");
    row.classList.add("row");
    row.dataset.index = index;
    row.innerHTML = textToAdd;

    document.querySelector(".saleListArticles").appendChild(row);
}

function saleDeleteArticle(index){
    document.querySelector(".saleListArticles").removeChild(document.querySelector(".saleRow[data-index='"+index+"']"));
}