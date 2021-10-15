
        function checkForm(formid){
            if(0 > document.querySelector(".name_input[data-index='"+formid+"']").value.length > 50){
                document.querySelector(".alert-danger[data-index='"+formid+"']").style.display = "block";
                return false;
            }else{
                return true;
            }
        }


        function deletetablerow(index){
            var rowToDelete = document.querySelector(".tablerow[data-rowindex='"+index+"']");
            console.log(rowToDelete)
            document.querySelector('.tablebody').removeChild(rowToDelete);
        }

        const addArticleBtn = document.querySelector('.add_article');
        const mainForm = document.querySelector('.main-form');
        var index = 2;
        

        addArticleBtn.addEventListener('click',() => {
            index++;
            var formGroup = document.createElement('div');
            formGroup.classList.add('row');
            formGroup.dataset.rowindex = index;
            formGroup.innerHTML = "<div class='form-group col-7'> <select type='text' class='form-control' placeholder='Article' name='article[]' required autocomplete='off' required>"
                + "<?php echo $liste_articles ?></select></div><div class='form-group row col-3'><input placeholder='Quantité' class='col col-8 mr-2 form-control' name='quantite[]'>"
                +"<button class='col btn btn-danger' onclick='deleterow("+index+")'>X</button></div>";
                
            mainForm.appendChild(formGroup);
        })


        const seeArticleBtns = document.querySelectorAll(".seeArticleBtn");
        const articleContainer = document.querySelector(".articles-container")


        seeArticleBtns.forEach(element => {
            element.addEventListener("click", (e) => {
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
                        console.log(error.text)
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
            })
        });

        const updateBtns = document.querySelectorAll(".updateBtn");
        const updateContainer = document.querySelector(".updateContainer");
        const articleListe = "<?php echo $liste_articles;?>";

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
                    if(text.error){
                        console.log(text.errortext);
                    }else{
                        const nomProduit = text.produit.pro_lib;
                        const commentaire = text.produit.pro_commentaire;
                        const produitId = text.produit.pro_ID;
                        document.querySelector(".updateTitle").innerHTML = nomProduit;
                        document.querySelector(".updateProduitName").value = nomProduit;
                        document.querySelector(".updateComment").innerHTML = commentaire;

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

        function deleteRow(produit_id, index){
            var formData = new FormData();
            formData.append("delete_article", "1");
            formData.append("produit_id", produit_id);
            formData.append("index", index);
            fetch(url, 
                {
                    method : "POST",
                    body : formData
                }
            )
            .then(result => result.json())
            .then(text => {
                if(text.error){
                    console.log(text.errortext);
                }else{
                    const row = document.querySelector("div[data-update-row-index='"+index+"']");
                    document.querySelector(".updateContainer").removeChild(row);
                    var newCount = parseInt(document.querySelector(".countArticles[data-index='"+produit_id+"']").innerHTML) - 1;
                    document.querySelector(".countArticles[data-index='"+produit_id+"']").innerHTML = newCount;
                }
            })
        }