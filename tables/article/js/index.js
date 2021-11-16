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
            table.api().order( [ 1, 'asc' ] ).draw();

            var id = result.existingid;

            var checkboxEl = document.createElement("td");
            var checkbox = document.querySelector(".checkbox[data-index='"+id+"']").cloneNode(true);
            checkbox.dataset.index = result.createdid;
            checkbox.addEventListener("change", checkBoxListener);

            checkboxEl.appendChild(checkbox);

            var tdid = document.createElement("td");
            tdid.innerHTML = "<center>"+result.createdid+"</center>";

            var tdlib = document.createElement("td");
            tdlib.innerHTML = "<center>"+lib+"</center>";

            var tdcomment = document.createElement("td");
            tdcomment.innerHTML = "<center>"+comment+"</center>";

            var tdcasier = document.createElement("td");
            tdcasier.innerHTML = "<center>"+result.cas+"</center>";

            var tdcat = document.createElement("td");
            tdcat.innerHTML = "<center>"+result.cat+"</center>";

            var update = document.querySelector(".updateBtn[data-index='"+id+"']").cloneNode(true);
            update.dataset.index = result.createdid;
            update.addEventListener("click", openUpdateModalListener);
            
            var deleteEl = document.querySelector(".delete-btn[data-index='"+id+"']").cloneNode(true);
            deleteEl.dataset.index = result.createdid;
            deleteEl.addEventListener("click", deleteEventListener);

            var td = document.createElement("td");
            td.style.display = "flex";
            td.style.justifyContent = "space-evenly";
            td.appendChild(update);
            td.appendChild(deleteEl);

            

            var newRow = document.createElement("tr");
            newRow.classList.add("tablerow");
            newRow.dataset.rowindex = result.createdid;
            newRow.dataset.value = result.createdid;
            newRow.appendChild(checkboxEl);
            newRow.appendChild(tdid);
            newRow.appendChild(tdlib);
            newRow.appendChild(tdcomment);
            newRow.appendChild(tdcasier);
            newRow.appendChild(tdcat);
            newRow.appendChild(td);

            table.api().row.add(newRow);
            table.api().order( [ 1, 'asc' ] ).draw();

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

    console.log(articles, quantitys);

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
    let maxindex = parseInt(articles[articles.length - 1].dataset.index) + 1;


    return maxindex;
}

function commandAddArticle(index){
    let textToAdd = '<div class="form-group col-5">' +
                        '<label for="article">Nom de l\'article : </label>' +
                        '<input placeholder="Nom de l\'article" class="form-control name_input commandArticle"' +
                                'style="margin: 0 auto" type="text" list="articles" data-index="'+index+'" required>' +
                    '</div>' +
                    '<div class="form-group col-5">' +
                        '<label for="article">Quantité souhaitée : </label>' +
                        '<input placeholder="Quantité" class="form-control name_input commandQuantite"' +
                                'style="margin: 0 auto" type="number" data-index="'+index+'" required>' +
                    '</div>';

    let row = document.createElement("div");
    row.classList.add("row");
    row.innerHTML = textToAdd;

    document.querySelector(".commandListArticles").appendChild(row);
}