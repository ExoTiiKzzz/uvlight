
            <table id="table">
                <thead>
                    <th>Selectionner</th>
                    <th style='text-align :center'>ID</th>
                    <th style='text-align :center'>Lib</th>
                    <th style='text-align :center'>Description</th>
                    <th style='text-align :center'>Actions</th>
                </thead>
                <tbody>
                    <?php 
                        foreach ($data as $key) {
                            $id = $key["cat_ID"]; ?>
                            <tr data-value="<?php echo $id ?>" data-rowindex="<?php echo $id ?>">
                                <td style='width: 5%'>
                                    <input type='checkbox' class='checkbox' data-index="<?php echo $id ?>" checked='false'>
                                </td>
                                <td>
                                    <center><?php echo $id ?></center>
                                </td>
                                <td>
                                    <center><?php echo $key["cat_nom"] ?></center>
                                </td>
                                <td>
                                    <center><?php echo $key["cat_description"] ?></center>
                                </td>
                                <td style='display:flex; justify-content: space-evenly;'>
                                    <button type='button' data-index="<?php echo $id ?>" class='btn btn-primary updateBtn' data-toggle='modal' data-target='#updateModal'>
                                        Modifier
                                    </button>
                                    <button type="button" data-index="<?php echo $id ?>" name="delete" class="delete-btn btn btn-danger">
                                        Supprimer
                                    </button>
                                </td>
                            </tr>
                            <?php
                        }
                    ?>
                </tbody>
            </table>