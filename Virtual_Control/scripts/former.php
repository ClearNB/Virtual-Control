<?php

class form_generator {

    private $data;

    function __construct($id, $action = '') {
        $this->data = ["<form id=\"$id\" action=\"$action\" method=\"POST\">"];
    }

    function Title($title, $icon) {
        array_push($this->data, "<div class=\"form-group pt-2\"><div class=\"w-100\"><h2><i class=\"fa fa-$icon fa-fw\"></i>$title</h2></div></div>");
    }

    function SubTitle($title, $caption, $icon) {
        array_push($this->data,
                "<div class=\"form-group pt-2\"><div class=\"w-100\"><h3><i class=\"fa fa-$icon fa-fw\"></i>$title</h3><hr class=\"orange\"><p>$caption</p></div></div>");
    }

    function Caption($caption) {
        array_push($this->data,
                "<div class=\"form-group pt-2\"><hr class=\"orange\"><div>$caption</div><hr class=\"orange\"></div>");
    }

    function Input($id, $desc, $small_desc, $icon, $required, $size = 255) {
        $r_text = "任意";
        $r_flag = "";
        if ($required) {
            $r_text = "必須";
            $r_flag = "required=\"required\"";
        }
        array_push($this->data, "<div class=\"form-group pt-2\"><label class=\"importantLabel col-md-3\">【" . $r_text . "】</label><label class=\"formtext col-md-8\">$desc<i class=\"fa fa-$icon fa-2x ml-2\"></i></label><input type=\"text\" class=\"form-control bg-dark my-1 form-control-lg shadow-sm text-monospace\" placeholder=\"Input Here\" $r_flag id=\"$id\" name=\"$id\"><small class=\"form-text text-body\" id=\"$id-label\" maxlength=\"$size\">$small_desc</small></div>");
    }

    function Password($id, $desc, $small_desc, $icon, $required) {
        $r_text = "任意";
        $r_flag = "";
        if ($required) {
            $r_text = "必須";
            $r_flag = "required=\"required\"";
        }
        array_push($this->data, "<div class=\"form-group pt-2\"><label class=\"importantLabel col-md-3\">【" . $r_text . "】</label><label class=\"formtext col-md-8\">$desc<i class=\"fa fa-$icon fa-2x ml-2\"></i></label><input type=\"password\" class=\"form-control bg-dark my-1 form-control-lg shadow-sm text-monospace\" placeholder=\"Input Here\" $r_flag id=\"$id\" name=\"$id\"><small class=\"form-text text-body\">$small_desc</small></div>");
    }

    function Button($id, $desc, $type = 'submit', $icon = '', $color = 'dark') {
        array_push($this->data, "<button type=\"$type\" id=\"$id\" class=\"btn btn-$color btn-block btn-lg shadow-lg mb-2\"><i class=\"fa fa-fw fa-lx fa-$icon\"></i>$desc</button>");
    }

    function Check($type, $id, $name, $value, $outname, $selected) {
        $type_text = 'checkbox';
        $class_text = 'checkbox02';
        if ($type == 1) {
            $type_text = 'radio';
            $class_text = 'radio02';
        }
        $sel_text = '';
        if ($selected) {
            $sel_text = 'checked';
        }
        array_push($this->data, '<input ' . $sel_text . ' required="required" id="' . $id . '" type="' . $type_text . '" name="' . $name . '" value="' . $value . '"><label for="' . $id . '" class="' . $class_text . '">' . $outname . '</label>');
    }

    function Export() {
        array_push($this->data, "</form>");
        $text = '';
        foreach ($this->data as $var) {
            $text = $text . $var;
        }
        return $text;
    }

}
