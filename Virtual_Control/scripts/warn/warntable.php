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
    private $s_data;
    private $r_data;
    private $table;
    private $loader;

    public function __construct($data) {
	$this->data = $data;
	$this->s_data = '';
	$this->r_data = ['SELECT' => '', 'SUB' => []];
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

    private function setDetails($groupname, $count) {
	$this->s_data .= '<h3 class="text-left text-body"><i class="fas fa-fw fa-info-circle"></i>取得情報</h3><ul class="black-view"><li>取得日: ' . $groupname . '</li><li>ログ数: ' . $count . '</li></ul><hr>';
    }

    private function closeDetails() {
	$this->s_data .= '</div></details>';
    }

    private function getData() {
	return $this->s_data;
    }

    private function resetData() {
	$this->s_data = '';
    }

    public function getHTML() {
	$this->r_data['SELECT'] .= $this->loader->openListGroup();
	foreach ($this->data as $g => $d) {
	    $this->setDetails($g, sizeof($d));
	    $i = 1;
	    foreach ($d as $v) {
		$title = '【#' . $i . '】' . $v['MESSAGE'];
		$this->setSubDetails($title);
		$this->setTable($v, $g . '-' . $i);
		$this->closeDetails();
		$i += 1;
	    }
	    $id = 'sub_i' . $g;
	    $this->r_data['SELECT'] .= $this->loader->addListGroup($id, $g, 'poll-h', sizeof($d) . '件のトラップデータがあります。', '詳しくはクリック！');
	    $this->r_data['SUB'][$id] = $this->getData();
	    $this->resetData();
	}
	$this->r_data['SELECT'] .= $this->loader->closeListGroup();
	return $this->r_data;
    }

}
