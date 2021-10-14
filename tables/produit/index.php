<?php 
require '../../lib/includes/defines.inc.php';
require '../../lib/includes/navbar.php';
require '../../lib/includes/sidenav.php';
require '../../lib/includes/doctype.php';

echo doctype("Produits", "../../../");
echo sidenav("../../../");
echo navbar("../../../");
?>

<body>
    <script> 
        const url = "trait.php";
    </script>
    <style>
        <?php 
            require '../static/css/table.css';
        ?>
    </style>
<?php

        $data = $oProduit->db_get_all();
        $categories = $oCategorie->db_get_all();
        $casiers = $oCasier->db_get_all();

        $articles = $oArticle->db_get_all();

        $liste_articles = "";
        foreach ($articles as $key) {
            $liste_articles .= "<option value=".$key["art_ID"].">".$key["art_nom"]."</option>";
        }
        $liste_casiers = "";
        foreach ($casiers as $key) {
            $liste_casiers .= "<option value=".$key["cas_ID"].">".$key["cas_lib"]."</option>";
        }

?>

    <div class="main-container sidenav-open">
        <button type='button' class='mt-4 ml-3 btn btn-success' data-toggle='modal' data-target='#createmodal'> Créer un produit </button>
        <!-- modal pour créer une catégorie -->
        <div class="modal fade" id="createmodal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Créer un Produit</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="trait.php" method="post" class="update_form">
                        <div class="mx-auto modal-body col-10">
                            <div class="main-form">
                                <div class="form-group">
                                    <div class="alert alert-danger" style="display: none">Le nom du produit doit faire entre 1 et 50 charactères maximum</div>
                                    <input placeholder="Nom du produit" class="form-control name_input"
                                    style="margin: 0 auto" type="text" name="produit_name" required>
                                </div>
                                <div class="form-group">
                                    <textarea class="form-control" placeholder="Commentaire pour le produit" rows="3" name="commentaire"></textarea>
                                </div>
                                <div class="form-group">
                                    <select name="casier" class="form-control">
                                        <?php echo $liste_casiers ?>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="form-group col-7">
                                        <select type="text" class="form-control" placeholder="Article" name="article[]" required autocomplete="off" required>
                                        <?php echo $liste_articles ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-3">
                                        <input placeholder="Quantité" class="form-control" name="quantite[]">
                                    </div>

                                    
                                </div>
                            </div>
                            
                            <div class="btn btn-success add_article">Ajouter un Produit</div>

                        </div>
                    
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                            <button type="submit" name="create" class="btn btn-primary">Créer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="table-container">
            <table id="table">
                <thead>
                    <th>Selectionner</th>
                    <th style='text-align :center'>ID</th>
                    <th style='text-align :center'>Nom</th>
                    <th style='text-align :center'>Commentaire</th>
                    <th style='text-align :center'>Casier</th>
                    <th style='text-align :center'>Nombre d'articles</th>
                    <th style='text-align :center'>Actions</th>
                </thead>
                <tbody>
                    <?php 
                        foreach ($data as $key) {
                            $id = $key["pro_ID"];
                            $articles_by_id = $oArticle->db_get_article_by_produit_id($id);
                            ?>
                            <tr data-value="<?php echo $id ?>">
                                <td style='width: 5%'>
                                <input type='checkbox' class='checkbox' data-index="<?php echo $id ?>" checked='false'></td>
                                <td>
                                    <center><?php echo $id ?></center>
                                </td>
                                <td>
                                    <center><?php echo $key["pro_lib"] ?></center>
                                </td>
                                <td>
                                    <center><?php echo $key["pro_commentaire"] ?></center>
                                </td>
                                <td>
                                    <center><?php echo $key["cas_lib"] ?></center>
                                </td>
                                <td>
                                    <center>
                                    <button type='button' class='btn btn-secondary seeArticleBtn' data-toggle='modal' data-index="<?php echo $id ?>" data-target='#seearticles'>
                                        Voir les articles (<span class="countArticles" data-index="<?php echo $id; ?>"><?php echo count($articles_by_id) ?></span>)
                                    </button></center>
                                </td>
                                <td style='display:flex; justify-content: space-evenly;'>
                                    <button type='button' class='btn btn-primary updateBtn' data-toggle='modal' data-index="<?php echo $id ?>" data-target='#update'>
                                        Modifier
                                    </button>
                                    <form action="trait.php" method="post">
                                        <input type="hidden" name="produit_id" value="<?php echo $id ?>">
                                        <button type="submit" name="delete" class="delete-btn btn btn-danger">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                            <?php
                        }
                    ?>
                </tbody>
            </table>
            <input type="checkbox" class="select-all" id="select-all">
            <label for="select-all" class="form-check-label">Tout sélectionner</label>

        
        <div class="operations-div" style="display: flex; justify-content: space-evenly">
            <button class="btn btn-danger delete-all" style="display: none">
                Supprimer les éléments selectionnés.
            </button>
        </div>
    </div>
    </div>

            

            

    <!-- Modal -->
    <div class="modal fade" id="update" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title updateTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="trait.php" method="post" class="update_form">
                    <div class="mx-auto modal-body col-10">
                        <div class="main-form">
                            <div class="form-group">
                                <div class="alert alert-danger" style="display: none">Le nom du produit doit faire entre 1 et 50 charactères maximum</div>
                                <input placeholder="Nom du produit" class="form-control name_input updateProduitName"
                                style="margin: 0 auto" type="text" name="produit_name" required value="">
                            </div>
                            <div class="form-group">
                                <textarea class="form-control updateComment" placeholder="Commentaire pour le produit" rows="3" name="commentaire"></textarea>
                            </div>
                            <div class="updateContainer">

                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-success updateAddBtn">Ajouter un article</button>
                            </div>
                        </div>

                    </div>
                
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        <button type="submit" name="create" class="btn btn-primary">Créer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="seearticles" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title seearticletitle"><?php echo $key["pro_lib"] ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="mx-auto modal-body col-10">
                    <div class="row">
                        <div class="col-7">
                            Article : 
                        </div>
                        <div class="col-3">
                            Casier : 
                        </div>
                        <div class="col-2">
                            Quantité :
                        </div>
                    </div>
                    <div class="articles-container">

                    </div>
                    
                            
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery Library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Datatable JS -->
    <script src="../script/jquery.dataTables.min.js"></script>

    <script src="../script/checkboxes.js"></script>
    <script src="../script/index.js"></script>
    <script src="../../script/js/sidenav.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    <script> //initialisation datatable
        $(document).ready(function(){
            $('#table').DataTable();
        });

        function checkForm(formid){
            if(0 > document.querySelector(".name_input[data-index='"+formid+"']").value.length > 50){
                document.querySelector(".alert-danger[data-index='"+formid+"']").style.display = "block";
                return false;
            }else{
                return true;
            }
        }

        function deleterow(index){
            var rowToDelete = document.querySelector(".row[data-rowindex='"+index+"']");
            mainForm.removeChild(rowToDelete);
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
    </script>
</body>
</html>