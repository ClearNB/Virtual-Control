<?php

/**
 * AgentSelect
 * エージェントのデータを<input type="radio">句で表示させます
 *
 * @author clearnb
 */
class AgentSelect {
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
	    $sel = false;
	    if($i == 0) {
		$sel = true;
	    }
	    $res .= $this->Radio('ag_rd_' . ($i + 1), 'sl_ag', $val['AGENTID'], '【' . $val['COMMUNITY'] . '】' . $val['AGENTHOST'], $sel);
	    $i += 1;
	}
	return $res;
    }
    
    private function Radio($id, $name, $value, $outname, $selected) {
	$type_text = 'radio';
	$class_text = 'radio02';
	$sel_text = '';
	if($selected) {
	    $sel_text = 'selected';
	}
	return '<input ' . $sel_text . ' required id="' . $id . '" type="' . $type_text . '" name="' . $name . '" value="' . $value . '"><label for="' . $id . '" class="' . $class_text . '">' . $outname . '</label><br>';
    }
}
