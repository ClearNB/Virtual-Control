<?php

include_once __DIR__ . '/../general/loader.php';

class IconData {

    private static $set = [];
    private $iconid;
    private $icon;

    public function __construct($iconid, $icon) {
	$this->iconid = $iconid;
	$this->icon = $icon;
	array_push(self::$set, $this);
    }
    
    private function getDataArray() {
	return ['ICONID' => $this->iconid, 'ICON' => $this->icon];
    }

    public static function getAllIconData() {
	$res = [];
	$icon = select(false, 'VC_ICONS', 'ICONID, ICON');
	if ($icon) {
	    $icon_d = getArray($icon);
	    foreach ($icon_d as $i) {
		new IconData($i['ICONID'], $i['ICON']);
	    }
	    $i = 0;
	    $g_i = 0;
	    $res['ICON'] = [];
	    array_push($res['ICON'], []);
	    foreach (self::$set as $s) {
		array_push($res['ICON'][$g_i], $s->getDataArray());
		$i += 1;
		if($i == 10) {
		    $i = 0;
		    $g_i += 1;
		    array_push($res['ICON'], []);
		}
	    }
	    self::resetIconData();
	}
	return $res;
    }

    public static function resetIconData() {
	self::$set = [];
    }
}
