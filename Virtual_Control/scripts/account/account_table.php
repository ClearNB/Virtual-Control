<?php

include_once __DIR__ . '/../general/table.php';

class AccountTable extends Table {

    private $data;

    public function __construct($data) {
	$this->data = $data;
    }
    
    public function generate_table() {
	$column_out = $this->data['COLUMN'][0];
	$column_key = $this->data['COLUMN'][1];
	$value = $this->data['VALUE'];
	$res = $this->start_table('tb_ac', 'user-friends', 'ユーザテーブル');
	$res .= $this->add_table_columns($column_out, true);
	$i = 1;
	foreach($value as $k => $v) {
	    $res .= $this->add_table_data_hor($column_key, $v, true, $i, $k, 'p_id', false);
	    $i += 1;
	}
	$res .= $this->table_close();
	return $res;
    }

}
