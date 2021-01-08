<?php
include_once __DIR__ . '/../general/loader.php';

/**
 * AgentSelect
 * MIBサブツリーデータのチェックボックスを作成します。
 *
 * @author clearnb
 */
class MIBSubSelect extends loader {
    private $data;
    
    public function __construct($data) {
	$this->data = $data;
    }
    
    public function getSubSelect($checkedSel = '') {
	$i = 0;
	$res = '';
	if(sizeof($this->data) == 0) {
	    $res = '（選択できるMIBサブツリーはありません）';
	}
	if($checkedSel) {
	    $res .= $this->Check('mb_ck_0', 'sl_ab', 'all', '全て選択', false, false, true);
	} else {
	    $res .= $this->Check('mb_ck_0', 'sl_ab', 'all', '全て選択', false, true, true);
	}
	foreach($this->data as $val) {
	    $req_flag = false;
	    if(!$checkedSel) {
		$req_flag = true;
	    }
	    if($checkedSel && in_array($val['SUBID'], $checkedSel)) {
		$res .= $this->Check('mb_ck_' . ($i + 1), 'sl_mb[]', $val['SUBID'], '【' . $val['SUBOBJECTID'] . '】' . $val['SUBNAME'], true, $req_flag, false);
	    } else {
		$res .= $this->Check('mb_ck_' . ($i + 1), 'sl_mb[]', $val['SUBID'], '【' . $val['SUBOBJECTID'] . '】' . $val['SUBNAME'], false, $req_flag, false);
	    }
	    $i += 1;
	}
	$res .= '<div id="err_mb"></div>';
	return $res;
    }
    
    public function getSubSelectClear() {
	return $this->getSubSelect();
    }
    
    public function getSubSelectOnAgent($subids) {
	$res = [];
	foreach($subids as $k => $v) {
	    $res[$k] = $this->getSubSelect($v);
	}
	return $res;
    }
}
