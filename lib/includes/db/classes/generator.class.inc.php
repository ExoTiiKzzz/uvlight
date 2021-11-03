<?php 

class Datatable_generator{

    public function generate_datatable(
        $data = [],
        $shown_columns_names = [], 
        $data_id_column_name = "",
        $show_checkboxes = false,
        $checkboxes_class = "",
        $show_update_button = false,
        $update_button_class = "",
        $show_delete_button = false,
        $delete_button_class = ""
    )
    {
        if(!is_array($data) || !is_array($shown_columns_names) || !is_string($data_id_column_name)){
            $response["error"] = true;
            $response["errortext"] = "Properties does not match required format";
            return $response;
        }

        if(empty($data) || empty($shown_columns_names) || empty($data_id_column_name)){
            $response["error"] = true;
            $response["errortext"] = "Verify that all properties are not empty";
            return $response;
        }

        $table = "<table id='table'>";

        //Table header
        $thead = "<thead>";
        if($show_checkboxes === true){
            $thead .= "<th>Select</th>";
        }
        foreach ($shown_columns_names as $column_name) {
            $thead .= "<th>".$column_name."</th>";
        }
        if($show_update_button === true || $show_delete_button === true){
            $thead .= "<th>Actions</th>";
        }
        
        $thead .= "</thead>";

        //Table Body
        $tbody = "<tbody>";
        foreach ($data as $key) {
            $id = $key[$data_id_column_name];
            $tbody .= "<tr data-value='".$id."' data-rowindex='".$id."' class='tablerow'> ";
            if($show_checkboxes === true){
                $tbody .= "<td> <input type='checkbox' class='".$checkboxes_class."' data-index='".$id."' checked='false'> </td>";
            }
            foreach ($key as $subkey) {
                $tbody .= "<td class='text-center'>".$subkey."</td>";
            }
            if($show_update_button === true || $show_delete_button === true){
                $tbody .= "<td style='display:flex; justify-content: space-evenly;'>";
                if($show_update_button === true){
                    $tbody .= "<button data-index='".$id."' class='".$update_button_class."' data-toggle='modal' data-target='#updateModal'>Update</button>";
                }
                if($show_delete_button === true){
                    $tbody .= "<button data-index='".$id."' class='".$delete_button_class."'>Delete</button>";
                }
                $tbody .= "</td>";
            }
            
            $tbody .= "</tr>";
        }
        $tbody .= "</tbody>";

        $table .= $thead.$tbody."</table>";

        $response["error"] = false;
        $response["content"] = $table;

        return $response;
    }

}

?>