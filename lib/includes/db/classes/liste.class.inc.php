<?php
class Liste{

    public function build_liste($id = "", $data = [], $value_name = ""): string
    {

        $list = "<datalist id='$id'>";
        foreach ($data as $key) {
            $list .= '<option value="'.$key["$value_name"].'">';
        }
        $list .="</datalist>";

        return $list;

    }

}