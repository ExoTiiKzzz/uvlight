
        function checkForm(formid){
            if(0 > document.querySelector(".name_input[data-index='"+formid+"']").value.length > 50){
                document.querySelector(".alert-danger[data-index='"+formid+"']").style.display = "block";
                return false;
            }else{
                return true;
            }
        }

        const addArticleBtn = document.querySelector('.add_article');
        const mainForm = document.querySelector('.main-form');
        var index = 2;
        

        addArticleBtn.addEventListener('click',() => {
            index++;
            var formGroup = document.createElement('div');
            formGroup.classList.add('row');
            formGroup.dataset.rowindex = index;
            formGroup.innerHTML = "<div class='form-group col-7'> <input class='form-control createArtLib' list='liste_articles' placeholder='Article' name='article[]' required autocomplete='off' required>"+
                "</div><div class='form-group row col-3'><input placeholder='Quantité' class='col col-8 mr-2 form-control createArtQte' name='quantite[]' required>"
                +"<button class='col btn btn-danger' onclick='deleterow("+index+")'>X</button></div>";
                
            mainForm.appendChild(formGroup);
        })


        const seeArticleBtns = document.querySelectorAll(".seeArticleBtn");
        const articleContainer = document.querySelector(".articles-container")


        seeArticleBtns.forEach(element => {
            element.addEventListener("click", (e) => seeArticleListener(e));
        });

        const updateBtns = document.querySelectorAll(".updateBtn");
        const updateContainer = document.querySelector(".updateContainer");
        const createBtn = document.querySelector(".createBtn");

        createBtn.addEventListener("click", (e) => {
            e.preventDefault();

            var lib = document.querySelector(".createLib").value;
            var comment = document.querySelector(".createComment").value;
            var cas = document.querySelector(".createCas").value;
            var articlesArray = [];
            var quantitesArray = [];

            var articles = document.querySelectorAll(".createArtLib");
            var quantites = document.querySelectorAll(".createArtQte");

            articles.forEach(element => {
                var article = element.value;
                articlesArray.push(article);
            });

            quantites.forEach(element => {
                var quantite = element.value;
                quantitesArray.push(quantite);
            });

            console.log(quantitesArray, articlesArray)

            var formData = new FormData();
            formData.append("create", "1");
            formData.append("lib", lib);
            formData.append("comment", comment);
            formData.append("cas", cas);
            formData.append("articles", articlesArray);
            formData.append("quantites", quantitesArray);

            fetch(url,
                {
                    method: "POST",
                    body: formData
                }    
            )
            .then(response => response.json())
            .then(result => {
                console.log(result);
                if(result.error === true){
                    console.log(result.errortext);
                }else{
                    table.api().order( [ 1, 'asc' ] ).draw();
                    
                    
                    var id = result.existingid;

                    var checkbox = document.createElement("td");
                    checkbox.appendChild(document.querySelector(".checkbox[data-index='"+id+"']").cloneNode(true));
                    checkbox.dataset.index = result.createdid;

                    var tdid = document.createElement("td");
                    tdid.innerHTML = "<center>"+result.createdid+"</center>";

                    var tdlib = document.createElement("td");
                    tdlib.innerHTML = "<center>"+lib+"</center>";

                    var tdcomment = document.createElement("td");
                    tdcomment.innerHTML = "<center>"+comment+"</center>";

                    var seeArticle = document.createElement("td");
                    seeArticle.appendChild(document.querySelector(".seeArticleBtn[data-index='"+id+"']").cloneNode(true));
                    seeArticle.dataset.index = result.createdid;
                    seeArticle.addEventListener( "click", seeArticleListener);
                    // seeArticle.innerText = "Voir les articles (<span class='countArticles' data-index='"+result.createdid+"'>" + articlesArray.length + "</span>)";

                    var update = document.querySelector(".updateBtn[data-index='"+id+"']").cloneNode(true);
                    update.dataset.index = result.createdid;
                    
                    var deleteEl = document.querySelector(".delete-btn[data-index='"+id+"']").cloneNode(true);
                    deleteEl.dataset.index = result.createdid;

                    var td = document.createElement("td");
                    td.style.display = "flex";
                    td.style.justifyContent = "space-evenly";
                    td.appendChild(update);
                    td.appendChild(deleteEl);

                    

                    var newRow = document.createElement("tr");
                    newRow.classList.add("tablerow");
                    newRow.dataset.rowindex = result.createdid;
                    newRow.dataset.value = result.createdid;
                    newRow.appendChild(checkbox);
                    newRow.appendChild(tdid);
                    newRow.appendChild(tdlib);
                    newRow.appendChild(tdcomment);
                    newRow.appendChild(seeArticle);
                    newRow.appendChild(td);

                    table.api().row.add(newRow);
                    table.api().order( [ 1, 'asc' ] ).draw();

                    document.querySelector(".createCloseBtn").click();
                }
            })
            .catch(err => console.log(err));

        });



        updateBtns.forEach(element => {
            element.addEventListener("click", (e) => {
                const index = e.target.dataset.index;
                updateContainer.innerHTML = "";
                var formData = new FormData();
                formData.append("getarticles", "1");
                formData.append("index", index);
                fetch(url, 
                    {
                        method : "POST",
                        body: formData
                    }
                )
                .then(result => result.json())
                .then(text => {
                    if(text.error && text.errortext != "article"){
                        console.log(text.errortext);
                    }else{
                        const id = index;
                        const nomProduit = text.produit.pro_lib;
                        const commentaire = text.produit.pro_commentaire;
                        const produitId = text.produit.pro_ID;
                        document.querySelector(".updateId").value = id;
                        document.querySelector(".updateLib").value = nomProduit;
                        document.querySelector(".updateComment").innerHTML = commentaire;

                        if(!text.errortext){
                            text.content.forEach(subel => {
                                const nomArticle = subel.art_nom;
                                const quantite = subel.compo_quantite;
                                const articleId = subel.art_ID;
    
                                const el = document.createElement("div");
                                el.dataset.updateRowIndex=articleId;
                                el.innerHTML = '<div class="form-group row">'+
                                                    '<div class="col-4">'+
                                                        '<input type="text" class="form-control articleName"  data-articleid="'+articleId+'" readonly value="'+nomArticle+'">'+
                                                    '</div>'+
                                                    '<div class="col-4">'+
                                                        '<select class="form-control articleSelect" data-index="'+articleId+'" data-oldindex="'+articleId+'" onfocusout=changearticle('+produitId+','+articleId+')>'+
                                                            articleListe + 
                                                        '</select>'+
                                                    '</div>'+
                                                    '<div class="form-group col-3">'+
                                                        '<input placeholder="Quantité" class="form-control articleQuantite" data-index="'+articleId+'" data-oldindex="'+articleId+'" onfocusout=changearticle('+produitId+','+articleId+') value="'+quantite+'">'+
                                                    '</div>'+
                                                    '<div class="col-1">'+
                                                        '<button type="button" class="btn btn-danger" onclick="deleteRow('+produitId+','+articleId+')">X</button>'+
                                                    '</div>'+
                                                '</div>';
                                
                                document.querySelector(".updateAddBtn").dataset.index = produitId;
                                updateContainer.appendChild(el);
                            });
                        }
                        
                    }
                });
            })
        });

        const updateAddBtn = document.querySelector(".updateAddBtn");
        updateAddBtn.addEventListener("click", () => {
            const produitId = updateAddBtn.dataset.index;
            var formData = new FormData();
            formData.append("addNewArt", "1");
            formData.append("produit_id", produitId);
            fetch(url,
                {
                    method: "POST",
                    body: formData
                }
            )
            .then(result => result.json())
            .then(text => {
                if(text.error){
                    console.log(text.errortext);
                }else{
                    const articleId = 0;
                    const el = document.createElement("div");
                    el.dataset.updateRowIndex=articleId;
                    el.innerHTML = '<div class="form-group row">'+
                                        '<div class="col-4">'+
                                            '<input type="text" class="form-control articleName" readonly data-articleid="'+articleId+'">'+
                                        '</div>'+
                                        '<div class="col-4">'+
                                            '<select class="form-control articleSelect" data-index="'+articleId+'" data-oldindex="'+articleId+'" onfocusout=changearticle('+produitId+','+articleId+')>'+
                                                articleListe + 
                                            '</select>'+
                                        '</div>'+
                                        '<div class="form-group col-3">'+
                                            '<input placeholder="Quantité" class="form-control articleQuantite" data-index="'+articleId+'" data-oldindex="'+articleId+'" onfocusout=changearticle('+produitId+','+articleId+')>'+
                                        '</div>'+
                                        '<div class="col-1">'+
                                            '<button type="button" class="btn btn-danger" onclick="deleteRow('+produitId+','+articleId+')">X</button>'+
                                        '</div>'+
                                    '</div>';
                    updateContainer.appendChild(el);
                    var newCount = parseInt(document.querySelector(".countArticles[data-index='"+produitId+"']").innerHTML) + 1;
                    document.querySelector(".countArticles[data-index='"+produitId+"']").innerHTML = newCount
                    updateAddBtn.style.display = "none";
                }
            })
            
        })

        function seeArticleListener(e){
            const index = e.target.dataset.index;
            var proFormData = new FormData();
            document.querySelector(".seearticletitle").innerHTML = "";
            proFormData.append("get_produit", "1");
            proFormData.append("produit_id", index);
            fetch(url, {
                method: "POST",
                body: proFormData
            })
            .then(response => response.json())
            .then(produit => {
                if(produit.error){
                    console.log(produit.texterror);
                } else{
                    document.querySelector(".seearticletitle").innerHTML = produit.content.pro_lib;
                }
            });
            articleContainer.innerHTML = "";
            var formData = new FormData();
            formData.append("getarticles", "1");
            formData.append("index", index);
            fetch(url, 
                {
                    method : "POST",
                    body: formData
                }
            )
            .then(result => result.json())
            .then(text => {
                if(text.error){
                    console.log(text.errortext);
                }else{
                    const allArticles = document.createElement("div");
                    text.content.forEach(subel => {
                        const nomArticle = subel.art_nom;
                        const casier = subel.cas_lib;
                        const quantite = subel.compo_quantite;

                        const el = document.createElement("div");
                        el.innerHTML = '<div class="form-group row">'+
                                            '<div class="col-7">'+
                                                '<input type="text" class="form-control" readonly value="'+nomArticle+'">'+
                                            '</div>'+
                                            '<div class="col-3">'+
                                                '<input type="text" class="form-control" readonly value="'+casier+'">'+
                                            '</div>'+
                                            '<div class="col-2">'+
                                                '<input type="text" class="form-control" readonly value="'+quantite+'">'+
                                            '</div>'+
                                        ' </div>';
                        
                        allArticles.appendChild(el);
                    });
                    articleContainer.appendChild(allArticles);
                }
            });
        }

        function changearticle(produit_id, article_id){
            const new_article_id = document.querySelector(".articleSelect[data-oldindex='"+article_id+"']").value;
            const oldarticle = document.querySelector(".articleSelect[data-oldindex='"+article_id+"']").dataset.index;
            var quantite = document.querySelector(".articleQuantite[data-oldindex='"+article_id+"']").value;
            if(!quantite) quantite = 0;
            var formData = new FormData();
            formData.append("updatearticle","1");
            formData.append("produit_id", produit_id);
            formData.append("old_article_id", oldarticle);
            formData.append("new_article_id", new_article_id);
            formData.append("quantite", quantite);

            fetch(url,
                {
                    method: "POST",
                    body: formData
                }
            )
            .then(response => response.json())
            .then(text => {
                if(text.error){
                    console.log(text.errortext);
                }else{
                    document.querySelector(".articleSelect[data-oldindex='"+article_id+"']").dataset.index = new_article_id;

                    if(oldarticle === 0){
                        addArticleBtn.style.display = "inline-block";
                    }
                }
            })

        }