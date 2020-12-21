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
	foreach($value as $v) {
	    $res .= $table->add_table_data_hor($column_key, $v, true, $i, 'USERID', 'pre_userid');
	    $i += 1;
	}
	$res .= $table->table_close();
	return $res;
    }

}
