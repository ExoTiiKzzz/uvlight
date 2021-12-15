<!-- Modal pour les tarifs-->

<div class="modal fade" id="tarifs" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tarifs de l'article <span class="tarifLibArticle"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="mx-auto modal-body col-10">
                <?php 
                 for($i = 1; $i<=5; $i++){ ?>
                    <div class="form-group">
                        <div class="row">
                            <?php

                            for($j = 1; $j<=4; $j++){?>
                                <div class="col-3">
                                    <label for="tarif<?= ($i-1)*4 + $j ?>">Tarif <?= ($i-1)*4 + $j ?></label>
                                </div>
                                <?php
                            }

                            ?>
                        </div>

                        <div class="row">
                            <?php

                            for($j = 1; $j<=4; $j++){?>
                                <div class="col-3">
                                    <input id="tarif<?= ($i-1)*4 + $j ?>" type="number" step="0.01" class="form-control updateTarifInput" data-index="<?= ($i-1)*4 + $j ?>">
                                </div>
                                <?php
                            }

                            ?>
                        </div>
                    </div>
                <?php
                 }
                ?>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary tarifsCloseBtn" data-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary saveBtn" data-dismiss="modal">Enregistrer</button>
            </div>
        </div>
    </div>
</div>