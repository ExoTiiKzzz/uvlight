const createBtn = document.querySelector(".createBtn");
createBtn.addEventListener("click", (e) =>{
    e.preventDefault();

    var lib = document.querySelector(".createLib").value;
    var comment = document.querySelector(".createComment").value;
    var cas = document.querySelector(".createCas").value;
    var cat = document.querySelector(".createCat").value;

    var formData = new FormData();
    formData.append("create", "1");
    formData.append("lib", lib);
    formData.append("comment", comment);
    formData.append("cas", cas);
    formData.append("cat", cat);

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
            var lib = result.content.lib;
            var comment = result.content.comment;
            var cas = result.content.cas;
            var cat = result.content.cat;

            document.querySelector(".updateId").value = id;
            document.querySelector(".updateLib").value = lib;
            document.querySelector(".updateComment").value = comment;
            document.querySelector(".updateCas").value = cas;
            document.querySelector(".updateCat").value = cat;
        }
    }).catch(err => console.log(err));
}

const commandBtn = document.querySelector(".commandBtn");

commandBtn.addEventListener("click", (e) => {

    let articles = [], quantitys = [];

    articleEls = document.querySelectorAll(".commandArticle");

    articleEls.forEach(article => {
        let index = article.dataset.index;
        articles[index] = article.value;
        quantitys[index] = document.querySelector(".commandQuantite[data-index='"+index+"']").value;
    })

    // console.log(articles, quantitys);

    let formData = new FormData;
    formData.append("command", "1");
    formData.append("article", JSON.stringify(articles));
    formData.append("quantity", JSON.stringify(quantitys));

    fetch(url, {
        method: "POST",
        body: formData
    })
        .then(result => result.json())
        .then(data => {
            if(data.error === false){
                drawTable();
                document.querySelector(".commandCloseBtn").click();
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
                        '<input placeholder="Nom de l\'article" class="form-control name_input commandArticle"' +
                                'style="margin: 0 auto" type="text" list="articles" data-index="'+index+'" required>' +
                    '</div>' +
                    '<div class="form-group col-4">' +
                        '<label for="article">Quantité souhaitée : </label>' +
                        '<input placeholder="Quantité" class="form-control name_input commandQuantite"' +
                                'style="margin: 0 auto" type="number" data-index="'+index+'" required>' +
                    '</div>'+
                    '<div class="form-group col-3" >'+
                            '<label> Retirer </label>'+
                            '<button class="form-control btn btn-danger commandDeleteArticle" onClick="commandDeleteArticle('+index+')">X</button>'+
                    '</div>';

    let row = document.createElement("div");
    row.classList.add("row");
    row.dataset.index = index;
    row.innerHTML = textToAdd;

    document.querySelector(".commandListArticles").appendChild(row);
}

function commandDeleteArticle(index){
    document.querySelector(".commandListArticles").removeChild(document.querySelector(".row[data-index='"+index+"']"));
}