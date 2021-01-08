<?php
include_once __DIR__ . '/../general/loader.php';

/**
 * AgentSelect
 * エージェントのデータを<input type="radio">句で表示させます
 *
 * @author clearnb
 */
class AgentSelect extends loader {
    private $data;
    
    public function __construct($data) {
	$this->data = $data;
    }
    
    public function getSelect() {
	$value = $this->data['VALUE'];
	$i = 0;
	$res = '';
	if(sizeof($value) == 0) {
	    $res = '（選択できるエージェントはありません）';
	}
	foreach($value as $val) {
	    $res .= $this->Radio('ag_rd_' . ($i + 1), 'sl_ag', $val['AGENTID'], '【' . $val['COMMUNITY'] . '】（最終更新日時: ' . $val['AGENTUPTIME'] . ') ' . $val['AGENTHOST'], false);
	    $i += 1;
	}
	return $res;
    }
}
