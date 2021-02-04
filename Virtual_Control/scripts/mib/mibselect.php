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
	if ($checkedSel) {
	    $res .= $this->Check('mb_ck_0', 'sl_ab', 'all', '全て選択', false, false, true);
	} else {
	    $res .= $this->Check('mb_ck_0', 'sl_ab', 'all', '全て選択', true, false, true);
	}
	foreach ($this->data['SUB'] as $v => $group) {
	    $res .= '【' . $this->data['GROUP'][$v]['GROUP_OID'] . '】' . $this->data['GROUP'][$v]['GROUP_NAME'] . '<br>';
	    foreach ($group as $sub) {
		$req_flag = (!$checkedSel);
		if ($checkedSel && in_array($sub['SUB_ID'], $checkedSel)) {
		    $res .= $this->Check('mb_ck_' . ($i + 1), 'sl_mb[]', $sub['SUB_ID'], '【' . $sub['SUB_OID'] . '】' . $sub['SUB_NAME'], true, $req_flag, false);
		} else {
		    $res .= $this->Check('mb_ck_' . ($i + 1), 'sl_mb[]', $sub['SUB_ID'], '【' . $sub['SUB_OID'] . '】' . $sub['SUB_NAME'], false, $req_flag, false);
		}
		$i += 1;
	    }
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
