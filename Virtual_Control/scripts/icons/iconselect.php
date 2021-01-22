<?php

include_once __DIR__ . '/../general/loader.php';

class IconSelect {
    private $data;

    public function __construct($data) {
	$this->data = $data;
    }

    public function getIconSelect() {
	$list = '<ul class="list">';
	foreach ($this->data as $i) {
	    $icondata = $i['ICON'];
	    $iconid = $i['ICONID'];
	    $icontext = preg_replace('/fa. fa-/', '', $icondata);
	    $list .= '<li class="list-elem"><div class="list-column" id="i-' . $iconid . '"><span class="list-icon"><i class="' . $icondata . ' icon-48"></i></span><span class="icon-text">' . $icontext . '</span></div></li>';
	}
	return $list;
    }
}
