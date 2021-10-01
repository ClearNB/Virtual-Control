<?php

include_once __DIR__ . '/../general/table.php';

/**
 * [CLASS] SNMPTable
 * 
 * 【クラス概要】<br>
 * SNMPDataで取得したデータをテーブル形式にHTML変換します。<br>
 * SNMPWALKで使用します。
 * 
 * @package VirtualControl_scripts_snmp
 * @author ClearNB<clear.navy.blue.star@gmail.com>
 * @category class
 * @requires Table
 */
class SNMPTable extends Table {

    private $result;
    private $table_data;
    private $table_id;
    private $t_id;
    private $stack;

    public function __construct($id, $data) {
	$this->table_data = $data;
	$this->table_id = $id;
	$this->t_id = 1;
	$this->d_id = 1;
	$this->d_sid = 1;
	$this->stack = [];

	$this->result = $this->start_table_without_title($this->table_id . '-' . $this->t_id);
    }

    public function generateTable(): string {
	$flag = true;
	$sub_table_flag = false;

	foreach ($this->table_data['MIB']['VALUE'] as $j => $d) {
	    $i = intval($j);
	    $type = $d['TABLETYPE'];
	    switch ($type) {
		case 0:
		    if (!$flag) {
			$flag = true;
			$sub_table_flag = false;
			$this->tableBack();
			$this->result .= $this->start_table_without_title($this->table_id . '-' . $this->t_id);
		    }
		    $this->tableAdd($d, $i);
		    break;
		case 1:
		case 2:
		    if (isset($this->table_data['VALUE'][$i])) {
			if ($flag) {
			    $flag = false;
			    $this->tableOnClose();
			}
			if ($type == 1) {
			    if ($sub_table_flag) {
				$this->tableBack();
			    }
			    $this->subTableOpen($i);
			    $sub_table_flag = true;
			} else {
			    $this->tableAdd($d, $i);
			}
		    }
		    break;
	    }
	}
	if ($flag) {
	    $this->result .= $this->table_close();
	} else {
	    $this->result .= $this->tableBack();
	}
	return $this->result;
    }

    private function getColumn($d) {
	$res = '<h5>' . $d['DATAOID'] . '</h5><i class="' . $d['ICON'] . ' fa-fw"></i>' . $d['JPNAME'] . '<br><small>' . $d['ENNAME'] . '</small>';
	if ($d['DESCR'] != '') {
	    $res .= $this->addExplan($d['DESCR']);
	}
	return $res;
    }

    private function tableBack() {
	for ($i = 1; $i <= sizeof($this->stack); $i++) {
	    array_push($this->stack[$i], $this->table_close() . $this->closeDetails());
	    $this->result .= implode('', $this->stack[$i]);
	}
	$this->result .= $this->closeDetails();
	$this->t_id += 1;
	$this->stack = [];
    }

    private function tableOnclose() {
	$this->t_id += 1;
	$this->result .= $this->table_close();
    }

    private function subTableOpen($i) {
	$index = $this->table_data['VALUE'][$i];
	$mib = $this->table_data['MIB']['VALUE'][$i];
	unset($index[0]);
	$index_size = sizeof($index);

	$this->result .= $this->openDetails('【' . $mib['DATAOID'] . '】' . $mib['ENNAME'] . ' : ' . $mib['JPNAME'] . '（' . $index_size . '）');
	$s_id = 1;
	foreach ($index as $e) {
	    $sub_title = '【' . $e . '】';
	    $this->stack[$s_id] = [];
	    array_push($this->stack[$s_id], $this->openSubDetails($sub_title) . $this->start_table($this->table_id . '-' . $this->t_id, 'table', $mib['JPNAME'] . '（' . $e . '）', 4));
	    $s_id += 1;
	    $this->t_id += 1;
	}
    }

    /**
     * 
     * @param type $d
     * @param int $i MIBIDを指定します
     */
    private function tableAdd($d, $i) {
	$s_id = 1;
	$value = isset($this->table_data['VALUE'][$i]) ? $this->table_data['VALUE'][$i] : '(該当なし)';

	if (is_array($value)) {
	    foreach ($value as $e) {
		$de = $this->add_table_data($this->getColumn($d), $this->dataNumberFormat($e));
		if (isset($this->stack[$s_id])) {
		    array_push($this->stack[$s_id], $de);
		    $s_id += 1;
		}
	    }
	} else {
	    $de = $this->add_table_data($this->getColumn($d), $this->dataNumberFormat($value));
	    $this->result .= $de;
	}
    }

    private function dataNumberFormat($data) {
	if (preg_match('/^[0-9]{1,}$/', $data) && !strpos($data, '.')) {
	    $data = number_format(intval($data));
	}
	return $data;
    }

}
