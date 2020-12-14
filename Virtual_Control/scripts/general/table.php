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
    
    public function add_table_data($column, $value) {
	return '<tr>'
		. '<td>' . $column . '</td>'
		. '<td>' . $value  . '</td>'
		. '</tr>';
    } 
    
    public function start_table_without_title($table_id) {
	return '<div id="' . $table_id . '"><table class="table table-hover"><tbody>';
    }
    
    public function table_close() {
	return '</tbody></table></div>';
    }
}