<?php

/**
 * Generate HTML Group "Table" by PHP.
 * General Class    : Table
 * Author	    : Project GSC (2020 Project)
 * Class Methods    : 
 * Static Methods   : startTable, addTableData, closeTable
 */
class Table {
    
    /**
     * startTable
     * HTMLでテーブルの作成を開始します。
     * @param string  $table_id
     * @param string  $t_id
     * @param string  $table_title_icon
     * @param string  $table_title
     * @param integer $title_type (Default: 3)
     * @return string HTML Tags
     */
    public function start_table($table_id, $table_title_icon, $table_title, $title_type = 3) {
	return '<div id="' . $table_id . '">'
		. '<h' . $title_type . ' class="text-left text-body">'
		. '<i class="fas fa-fw fa-' . $table_title_icon . '"></i>'
		. $table_title . '</h' . $title_type . '>'
		. '<table class="table table-hover">'
		. '<tbody>';
    }
    
    /**
     * テーブルデータに1行分のデータを追加します（アカウントデータ）
     * @param type $column
     * @param type $value
     * @return type
     */
    public function add_table_data($column, $value) {
	return '<tr>'
		. '<td>' . $column . '</td>'
		. '<td>' . $value  . '</td>'
		. '</tr>';
    }
    
    /**
     * 
     * @param array $column_array   キー項目のある順序配列です
     * @param array $value_array    キー項目で格納されているデータの連想配列です
     * @param bool  $isradio	    ラジオボタンが必須かどうかを設定します
     */
    public function add_table_data_hor($column_array, $value_array, $isradio = false, $radio_id = '', $radio_key = '', $radio_name = '') {
	$res = '<tr>';
	if($isradio) {
	    $radio_value = $value_array[$radio_key];
	    $res .= '<td><input class="radio02" type="radio" required="required" name="' . $radio_name . '" value="' . $radio_value . '" id="' . $radio_id . '"><label for="' . $radio_id . '" class="radio02"></label></td>';
	}
	foreach($column_array as $var) {
	    $res .= '<td>' . $value_array[$var] . '</td>';
	}
	$res .= '</tr>';
	return $res;
    }
    
    /**
     * ヘッダ項目を作成します
     * @param type $column_array    項目データが順序配列で格納されています
     * @param type $isradio	    ラジオが必須かどうか指定します（default: false）
     */
    public function add_table_columns($column_array, $isradio = false) {
	$res = '<tr>';
	if($isradio) {
	    $res .= '<td></td>';
	}
	foreach($column_array as $var) {
	    $res .= '<td>' . $var . '</td>';
	}
	$res .= '</tr>';
	return $res;
    }
    
    public function start_table_without_title($table_id) {
	return '<div id="' . $table_id . '"><table class="table table-hover"><tbody>';
    }
    
    public function table_close() {
	return '</tbody></table></div>';
    }
}