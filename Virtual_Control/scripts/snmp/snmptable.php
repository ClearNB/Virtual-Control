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
	foreach ($this->table_data as $d) {
	    $type = $d['TYPE'];
	    switch ($type) {
		case 0:
		    if (!$flag) {
			$flag = true;
			$sub_table_flag = false;
			$this->tableBack();
			$this->result .= $this->start_table_without_title($this->table_id . '-' . $this->t_id);
		    }
		    $this->tableAdd($type, $d);
		    break;
		case 1:
		case 2:
		    if ($flag) {
			$flag = false;
			$this->tableOnClose();
		    }
		    if ($type == 1) {
			if($sub_table_flag) {
			    $this->tableBack();
			}
			$this->subTableOpen($d);
			$sub_table_flag = true;
		    } else {
			$this->tableAdd($type, $d);
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
	return '<h5>' . $d['OID'] . '</h5><i class="' . $d['ICON'] . ' fa-fw"></i>' . $d['JAPTLANS'] . '<br><small>' . $d['DESCR'] . '</small>';
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

    private function subTableOpen($d) {
	$index_size = sizeof($d['INDEX']);
	$this->result .= $this->openDetails('【' . $d['OID'] . '】' . $d['DESCR'] . ' : ' . $d['JAPTLANS'] . '（' . $index_size . '）');
	$s_id = 1;
	foreach ($d['INDEX'] as $e) {
	    $sub_title = '【' . $e . '】';
	    $this->stack[$s_id] = [];
	    array_push($this->stack[$s_id], $this->openSubDetails($sub_title) . $this->start_table($this->table_id . '-' . $this->t_id, 'table', $d['JAPTLANS'] . '（' . $e . '）', 4));
	    $s_id += 1;
	    $this->t_id += 1;
	}
    }

    private function tableAdd($t, $d) {
	$s_id = 1;
	foreach ($d['DATA'] as $e) {
	    $de = $this->add_table_data($this->getColumn($d), $this->dataNumberFormat($e));
	    if ($t == 2) {
		if (isset($this->stack[$s_id])) {
		    array_push($this->stack[$s_id], $de);
		    $s_id += 1;
		}
	    } else {
		$this->result .= $de;
	    }
	}
    }

    private function dataNumberFormat($data) {
	if (preg_match('/^[0-9]{1,}$/', $data) && !strpos($data, '.')) {
	    $data = number_format(intval($data));
	}
	return $data;
    }

}
