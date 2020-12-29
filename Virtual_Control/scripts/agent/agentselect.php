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
	$code = 0;
	$res = '';
	$subids = $this->data['SUBID'];
	if(sizeof($value) == 0) {
	    $res = '（選択できるエージェントはありません）';
	    $code = 2;
	}
	foreach($value as $val) {
	    $sel = false;
	    if($i == 0) {
		$sel = true;
	    }
	    $subid = implode('_', $subids[$val['AGENTID']]);
	    $res .= $this->Radio('ag_rd_' . ($i + 1), 'sl_ag', $val['AGENTID'] . '+' . $subid, '【' . $val['COMMUNITY'] . '】' . $val['AGENTHOST'], $sel);
	    $i += 1;
	}
	return ["code" => $code, "data" => $res];
    }
}
