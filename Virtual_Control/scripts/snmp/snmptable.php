<?php

include_once '../general/table.php';

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

    private $table_data;
    private $table_title;
    private $table_title_icon;
    private $table_id;
    private $t_id;
    private $i;

    public function __construct($id, $data, $title) {
	$this->table_data = $data;
	$this->table_title = $title;
	$this->table_title_icon = 'table';
	$this->table_id = $id;
	$this->t_id = 1;
	$this->i = 0;
    }

    public function generate_table(): string {
	$result = $this->start_table($this->table_id . '-' . $this->t_id, $this->table_title_icon, $this->table_title, 3);
	$flag = true;
	for ($this->i = 0; $this->i < sizeof($this->table_data['OID']); $this->i++) {
	    $oid = $this->table_data['OID'][$this->i];
	    $check = $this->table_data['CHECK'][$oid];
	    if ($check != 0) {
		if ($flag) {
		    $flag = false;
		    $result .= $this->table_close();
		}
		$result .= $this->var_nums();
	    } else {
		if (!$flag) {
		    $flag = true;
		    $this->t_id += 1;
		    $result .= $this->start_table_without_title($this->table_id, $this->t_id);
		}
		$value = "<データなし>";
		if (isset($this->table_data['VALUE'][$oid][0])) {
		    $value = self::data_numberformat($this->table_data['VALUE'][$oid][0]);
		}
		$result .= $this->add_table_data($this->get_column($oid), $value);
	    }
	}
	if ($flag) {
	    $result .= $this->table_close();
	}
	return $result;
    }

    function var_nums() {
	//定義
	$s_i = $this->i + 1;
	$oid = $this->table_data['OID'][$s_i];
	$m_size = count($this->table_data['OID']);
	$cr_check = $this->table_data['CHECK'][$oid];
	$sp_d = [];

	//1: detailの付加 [タイトルの作成]
	$title_oid = $this->table_data['OID'][$s_i - 1];
	$title_jap = $this->table_data['JAPTLANS'][$title_oid];
	$title = '【' . $title_oid . '】' . $this->table_data['DESCR'][$title_oid] . ' : ' . $this->table_data['JAPTLANS'][$title_oid];
	$res = '<details class="main"><summary class="summary">' . $title . '</summary><div class="details-content">';
	//2: データの加工（個別データに振り分け）
	while ($this->table_data['CHECK'][$oid] == $cr_check) {
	    $d_id = 0;
	    if (isset($this->table_data['VALUE'][$oid])) {
		foreach ($this->table_data['VALUE'][$oid] as $v) {
		    $sp_d[$d_id][$oid] = $v;
		    $d_id += 1;
		}
	    }
	    $this->i += 1;
	    if ($this->i < $m_size) {
		$oid = $this->table_data['OID'][$this->i];
	    } else {
		break;
	    }
	}
	//3: エンドポイントをもってくる
	$e_i = $this->i;

	//4: テーブル作成
	$res .= $this->table_vertical_snmp($title_jap, $sp_d, $s_i, $e_i);
	$res .= '</div></details>';

	$this->i -= 1;
	return $res;
    }

    private function get_column($oid) {
	return '<h5>' . $oid . '</h5>'
		. '<i class="' . $this->table_data['ICON'][$oid] . ' fa-fw"></i>' . $this->table_data['JAPTLANS'][$oid] . '<br>'
		. '<small>' . $this->table_data['DESCR'][$oid] . '</small>';
    }

    function table_vertical_snmp($table_title, $table_data, $start_id, $end_id) {
	$result = '';

	for ($i = 0; $i < sizeof($table_data); $i++) {
	    $start_oid = $this->table_data['OID'][$start_id - 1];
	    $index = $i + 1;
	    if (isset($this->table_data['INDEX'][$start_oid])) {
		//echo $this->table_data['OID'][$start_id] . "<br />";
		$index = $this->table_data['INDEX'][$start_oid][$i];
	    }
	    $result .= '<details class="sub"><summary class="summary-sub">【' . $index . '】</summary><div class="details-content-sub">';
	    $result .= $this->start_table($this->table_id . '-' . $this->t_id, $this->table_title_icon, $table_title . "($index)", 4);
	    for ($j = $start_id; $j < $end_id; $j++) {
		$oid = $this->table_data['OID'][$j];
		$value = '<データなし>';
		if (isset($table_data[$i][$oid])) {
		    $value = self::data_numberformat($table_data[$i][$oid]);
		}
		$result .= $this->add_table_data($this->get_column($oid), $value);
	    }
	    $this->t_id += 1;
	    $result .= $this->table_close();
	    $result .= '</div></details>';
	}
	return $result;
    }

    private static function data_numberformat($data) {
	if (preg_match('/^[0-9]{1,}$/', $data) && !strpos($data, '.')) {
	    $data = number_format(intval($data));
	}
	return $data;
    }

}
