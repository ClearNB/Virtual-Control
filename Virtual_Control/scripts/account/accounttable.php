<?php

class AccountTable {

    private $data;

    public function __construct($data) {
	$this->data = $data;
    }
    
    public function generate_table() {
	$column_out = $this->data['COLUMN'][0];
	$column_key = $this->data['COLUMN'][1];
	$value = $this->data['VALUE'];
	$table = new Table();
	$res = $table->start_table('tb_ac', 'user-friends', 'ユーザテーブル');
	$res .= $table->add_table_columns($column_out, true);
	$i = 1;
	foreach($value as $k => $v) {
	    if($i == 1) {
		$res .= $table->add_table_data_hor($column_key, $v, true, $i, $k, 'pre_userid', true);
	    } else {
		$res .= $table->add_table_data_hor($column_key, $v, true, $i, $k, 'pre_userid', false);
	    }
	    $i += 1;
	}
	$res .= $table->table_close();
	return $res;
    }

}
