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
	if (sizeof($this->data) == 0) {
	    $res = '（選択できるMIBサブツリーはありません）';
	}
	$res .= $this->Check('mb_ck_0', 'sl_ab', 'all', '全て選択', !$checkedSel, false, true);
	foreach ($this->data as $v => $g) {
	    $req_flag = (!$checkedSel);
	    $res .= $this->Check('mb_ck_' . ($i + 1), 'sl_mb[]', $v, '【' . $g['GROUPOID'] . '】' . $g['GROUPNAME'], ($checkedSel && in_array($v, $checkedSel)), $req_flag, false);
	    $i += 1;
	}
	$res .= '<div id="err_mb"></div>';
	return $res;
    }

    public function getSubSelectClear() {
	return $this->getSubSelect();
    }

    public function getSubSelectOnAgents($subids) {
	$res = [];
	foreach ($subids as $k => $v) {
	    $res[$k] = $this->getSubSelect($v);
	}
	return $res;
    }

    public function getSubSelectOnAgent($subids) {
	$res = $this->getSubSelect($subids);
	return $res;
    }

}
