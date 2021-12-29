<div class="main-container sidenav-open">
    <datalist id="liste_articles">

    </datalist>

    <button type='button' class='my-3 btn btn-success openCreateBtn' data-toggle='modal' data-target='#createmodal'> Créer un article </button>
    <button type='button' class='mx-3 btn btn-success' data-toggle='modal' data-target='#command'> Commande fournisseur </button>

    <div class="table-container" style="margin-right: 0px;">
        <table id="table">
            <thead>
            <th>Selectionner</th>
            <th style='text-align :center'>ID</th>
            <th style='text-align :center'>Nom</th>
            <th style='text-align :center'>Commentaire</th>
            <th style='text-align :center'>Stock</th>
            <th style='text-align :center'>Fournisseur</th>
            <th style='text-align :center'>Catégorie</th>
            <th style='text-align :center'>Casier</th>
            <th style='text-align :center'>Actions</th>
            </thead>
        </table>
        <input type="checkbox" class="select-all" id="select-all">
        <label for="select-all" class="form-check-label">Tout sélectionner</label>


        <div class="operations-div" style="display: flex; justify-content: space-evenly">
            <button class="btn btn-danger delete-all" style="display: none">
                Supprimer les éléments selectionnés.
            </button>
        </div>
    </div>

    <?php require './components/createModal.html'; ?>
    <?php require './components/commandModal.html'; ?>
    <?php require './components/tarifsModal.php' ?>



</div>