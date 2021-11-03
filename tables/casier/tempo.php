<table id="table">
                <thead>
                    <th>Selectionner</th>
                    <th style='text-align :center'>ID</th>
                    <th style='text-align :center'>Lib</th>
                    <th style='text-align :center'>Actions</th>
                </thead>
                <tbody>
                    <?php 
                        foreach ($data as $key) {
                            $id = $key["cas_ID"]; ?>
                            <tr data-rowindex="<?php echo $id ?>">
                                <td>
                                    <input type='checkbox' class='checkbox' data-index="<?php echo $id ?>" checked='false'>
                                </td>
                                <td><center><?php echo $id ?></center></td> 
                                <td><center><?php echo $key["cas_lib"] ?></center></td>
                                <td style='display:flex; justify-content: space-evenly;'>
                                    <button type='button' class='btn btn-primary updateBtn' data-index="<?php echo $id ?>" data-toggle='modal' data-target='#updateModal'>
                                        Modifier
                                    </button>
                                    <button type="button" class="delete-btn btn btn-danger" data-index="<?php echo $id ?>">
                                        Supprimer
                                    </button>
                                </td>
                            </tr>
                            <?php
                        }
                    ?>
                </tbody>
            </table>