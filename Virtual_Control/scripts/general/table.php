<?php

include_once __DIR__ . '/loader.php';

/**
 * [CLASS] Table
 * 
 * 【クラス概要】<br>
 * テーブル形式の構成を行うクラスです。<br>
 * ※このクラスには、コンストラクタや変数がありません。
 * 
 * @package VirtualControl_scripts_general
 * @author ClearNB<clear.navy.blue.star@gmail.com>
 * @category class
 */
class Table extends loader {
    
    /**
     * [GET] テーブル開始
     * 
     * HTMLでテーブルの作成を開始します。
     * 
     * @param string	$table_id		テーブルに与える一意のIDを指定します
     * @param string	$table_title_icon	テーブルのタイトルに付けるタイトルを指定します
     * @param string	$table_title		テーブルのタイトルを指定します
     * @param int	$title_type		タイトルの大きさのタイプを指定します（\<h*\>..\</h*\>の部分）
     * @return string \<div ..\>\<table ..\>\<tbody\> までのHTMLコードを返します
     */
    public function start_table($table_id, $table_title_icon, $table_title, $title_type = 3) {
	return '<div id="' . $table_id . '">'
		. '<h' . $title_type . ' class="vc-title text-left text-body">'
		. '<i class="fas fa-fw fa-' . $table_title_icon . '"></i>'
		. $table_title . '</h' . $title_type . '>'
		. '<table class="table table-hover">'
		. '<tbody>';
    }
    
    /**
     * [GET] 2列テーブルレコード追加
     * 
     * 2列テーブルレコードを追加します。
     * 
     * @param string $column	左列のデータを指定します
     * @param string $value	右列のデータを指定します   
     * @return string \<tr\>..\</tr\>内のHTMLコードを返します
     */
    public function add_table_data($column, $value): string {
	return '<tr>'
		. '<th>' . $column . '</th>'
		. '<td>' . $value  . '</td>'
		. '</tr>';
    }
    
    /**
     * [GET] 水平方向テーブルレコード追加
     * 
     * 水平方向のテーブルレコードを追加します。
     * 
     * @param array $column_array   キー項目のある順序配列です
     * @param array $value_array    キー項目で格納されているデータの連想配列です
     * @param bool  $isradio	    ラジオボタンが必須かどうかを設定します
     * @return string \<tr\>..\</tr\>内のHTMLコードを返します
     */
    public function add_table_data_hor($column_array, $value_array, $isradio = false, $radio_id = '', $radio_value = '', $radio_name = '', $radio_selected = ''): string {
	$res = '<tr>';
	if($isradio) {
	    $radio_text = $this->Radio($radio_id, $radio_name, $radio_value, '', $radio_selected);
	    $res .= '<td>' . $radio_text . '</td>';
	}
	foreach($column_array as $var) {
	    $res .= '<td>' . $value_array[$var] . '</td>';
	}
	$res .= '</tr>';
	return $res;
    }
    
    /**
     * [GET] ヘッダ項目追加
     * 
     * ヘッダ項目を作成します。
     * 
     * @param array $column_array	項目データが順序配列で格納されています
     * @param bool $isradio		ラジオが必須かどうか指定します（default: false）
     * @return string \<tr\>..\</tr\>内のHTMLコードを返します
     */
    public function add_table_columns($column_array, $isradio = false): string {
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
    
    /**
     * [GET] テーブル開始（タイトルなし）
     * 
     * タイトルなしでテーブルを始めます
     * 
     * @param type $table_id		テーブルに与える一意のIDを指定します
     * @return string \<div ..\>\<table ..\>\<tbody\> までのHTMLコードを返します
     */
    public function start_table_without_title($table_id) {
	return '<div id="' . $table_id . '"><table class="table table-hover"><tbody>';
    }
    
    /**
     * [GET] テーブル終了
     * 
     * テーブルを閉じます。
     * 
     * @return string \</tbody\>\</table\>\</div\> までのHTMLコードを返します
     */
    public function table_close() {
	return '</tbody></table></div>';
    }
}