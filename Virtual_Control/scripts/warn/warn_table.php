<?php

include_once __DIR__ . '/../general/table.php';
include_once __DIR__ . '/../general/loader.php';

/**
 * AgentSelect
 * MIBサブツリーデータのチェックボックスを作成します。
 *
 * @author clearnb
 */
class WarnTable {

    static $column = ['SYSTIME' => 'システム稼働時間', 'TIME' => '発生時刻', 'ADDRESS' => 'ホストアドレス', 'COMMUNITY' => 'コミュニティ', 'OID' => '対象OID', 'AGENT' => 'エージェント情報', 'ENTERPRISE' => '情報出力先OID', 'INTERFACE' => 'インタフェースID', 'SOURCE' => 'その他情報', 'MESSAGE' => 'メッセージ'];
    private $data;
    private $u_data;
    private $s_data;
    private $r_data;
    private $table;
    private $loader;

    public function __construct($data) {
	$this->data = $data['VALUE'];
	$this->u_data = $data['UPDATED_LOG'];
	$this->s_data = '';
	$this->r_data = ['LIST' => '', 'SUB' => []];
	$this->table = new Table();
	$this->loader = new loader();
    }

    private function setTable($data, $g_i) {
	$this->s_data .= $this->table->start_table($g_i, 'table', 'トラップ詳細一覧');
	foreach ($data as $i => $v) {
	    if ($v) {
		$this->s_data .= $this->table->add_table_data(self::$column[$i], $v);
	    }
	}
	$this->s_data .= $this->table->table_close();
    }

    private function setSubDetails($groupname) {
	$this->s_data .= '<details class="main"><summary class="summary">' . $groupname . '</summary><div class="details-content">';
    }

    private function closeDetails() {
	$this->s_data .= '</div></details>';
    }

    private function getData($id, $size) {
	return ['ID' => $id, 'TABLE' => $this->s_data, 'COUNT' => $size];
    }

    private function resetData() {
	$this->s_data = '';
    }

    public function getHTML() {
	$this->r_data['COUNT'] = 0;
	$this->r_data['LIST'] .= $this->loader->openListGroup();
	foreach ($this->data as $g => $d) {
	    $updated_data = $this->u_data[$g];
	    $i = 1;
	    foreach ($d as $v) {
		$title = '【#' . $i . '】' . $v['MESSAGE'];
		$this->setSubDetails($title);
		$this->setTable($v, $g . '-' . $i);
		$this->closeDetails();
		$i += 1;
	    }
	    $id = 'sub_i' . $g;
	    $size = sizeof($d);
	    $this->r_data['COUNT'] += $size;
	    $this->r_data['LIST'] .= $this->loader->addListGroup($id, $g . ' - (' . $size . '件)', 'poll-h', '最新: ' . $updated_data, '詳しくはクリック！');
	    
	    $this->r_data['SUB'][$id] = $this->getData($g, $size);
	    $this->resetData();
	}
	$this->r_data['LIST'] .= $this->loader->closeListGroup();
	return $this->r_data;
    }
}
