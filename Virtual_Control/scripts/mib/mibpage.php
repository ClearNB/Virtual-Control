<?php

include_once __DIR__ . '/../general/former.php';

class MIBPage extends form_generator {

    private $sel_data;

    public function __construct($sel_data, $id = "fm_pg") {
	parent::__construct($id);
	$this->sel_data = $sel_data;
    }

    public function getGroupSelect() {
	$this->Button('bt_hm', '設定一覧に戻る', 'button', 'list');
	$this->SubTitle('MIBグループ選択', '以下からMIBグループを選択し、管理を行ってください。', 'mouse');
	$i = 1;
	if (sizeof($this->sel_data) == 0) {
	    $this->Caption('【MIBグループはありません】');
	} else {
	    foreach ($this->sel_data as $k => $s) {
		$this->Check(1, 'gsel-' . $i, 'request_data_id', $k, '【' . $s['GROUP_OID'] . '】' . $s['GROUP_NAME'], false);
		$i += 1;
	    }
	}
	$this->Button('bt_go_sb', 'サブツリー選択', 'button', 'mouse', 'dark', 'disabled');
	$this->Button('bt_gr_cr', '作成', 'button', 'plus-square');
	$this->Button('bt_gr_ed', '編集', 'button', 'edit', 'dark', 'disabled');
	$this->Button('bt_gr_dl', '削除', 'button', 'trash-alt', 'dark', 'disabled');
	return $this->Export();
    }
    
    public function getGroupCreate() {
	
    }
    
    public function getGroupEditSelect() {
	
    }
    
    public function getGroupEditOID() {
	
    }
    
    public function getGroupEditName() {
	
    }
    
    public function getGroupDelete() {
	
    }
    
    
    
    public function getSubSelect() {
	
    }

    public function getConfirm() {
	
    }
}
