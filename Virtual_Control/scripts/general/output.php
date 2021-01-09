<?php

/**
 * [CLASS] File
 * 
 * <h5>ファイルの読み込み・書き込み・ダウンロード処理を統括します。</h5><hr>
 * 以下の処理をサポートします。<br>
 * ・ファイルタイプの管理
 * ・パスを指定しての読み込み・書き込み・ダウンロード
 * 
 * @package VirtualControl_scripts_general
 * @author ClearNB<clear.navy.blue.star@gmail.com>
 * @category class
 */
class File {

    /**
     * [CLASS VAR] (0..load, 1..write, 2..download)
     * 
     * @var int $type
     */
    private $type;

    /**
     * [CLASS VAR] (0..CSV, 1..JSON, 2..TEXT)<br>
     * Supported UTF-8 only
     * 
     * @var int $file_type
     */
    private $file_type;

    /**
     * [CLASS VAR] File Path
     * 
     * @var string $file_path
     */
    private $file_path;

    /**
     * [CLASS VAR] File Name
     * 
     * @var string $file_name
     */
    private $file_name;

    /**
     * [CLASS VAR] File Data
     * 
     * String Data Only(\\n Split Supported)
     * 
     * @var string $file_data
     */
    private $file_data;

    /**
     * [CLASS VAR] CSV Format Split
     * 
     * Default: ','
     * 
     * @var string $csv_split
     */
    private $csv_split;

    /**
     * [CLASS] Constructor of File
     * 
     * @param int $type Type of the File Method (0..load, 1..write, 2..download)
     * @param int $file_type Type of the File Type (0..CSV, 1..JSON, 2..TEXT)
     * @param string $file_path File Path to File (download -> '')
     * @param string $file_name File Name and Type (Ex:. file.csv)
     * @param string $file_data File Data (String Only)
     * @param string $csv_split CSV Format Split (Default: ',')
     */
    public function __construct($type, $file_type, $file_path, $file_name, $file_data, $csv_split = ',') {
	$this->type = $type;
	$this->file_type = $file_type;
	$this->file_path = $file_path;
	if (!$this->file_path) {
	    $this->file_path = 'php://output';
	}
	$this->file_name = $file_name;
	$this->file_data = $file_data;
	$this->csv_split = $csv_split;
    }

    /**
     * [GET] Run the File Method
     * 
     * Read, Write, Download Method Runner.<br>
     * Read ... readData(String or Array) or 1(Failed)<br>
     * Write, Download ... 0(Success) or 1(Failed)
     * 
     * @return int|string|array Results for Running Method.
     */
    public function run() {
	$res = '';
	switch ($this->type) {
	    case 0:
		$res = $this->read();
		break;
	    case 1:
		$res = $this->write();
		break;
	    case 2:
		$res = $this->download();
		break;
	}
	return $res;
    }

    /**
     * [GET] Read Method
     * 
     * @return int|string|array 1..Failed, (string)..readData
     */
    private function read() {
	$res = '';
	$fdata = file_get_contents($this->file_path . '/' . $this->file_name);
	if ($fdata) {
	    $res = $this->dataConvert($fdata);
	} else {
	    $res = 1;
	}
	return $res;
    }

    /**
     * [GET] Write Method
     * 
     * @return int 0(Success) or 1(Failed)
     */
    private function write() {
	$res = 0;
	$c_data = str_replace("\\n", "\n", str_replace("\\t", "\t", $this->file_data));
	$arr = explode('\n', $c_data);

	$fp = fopen($this->file_path . '/' . $this->file_name, 'w');

	if ($fp) {
	    foreach ($arr as $row) {
		switch ($this->file_type) {
		    case 0: //CSV
			fputcsv($fp, $row, $this->csv_split);
			break;
		    case 1: //JSON
		    case 2: //TEXT
			fputs($fp, $row);
			break;
		}
	    }
	    fclose($fp);
	} else {
	    $res = 1;
	}
	return $res;
    }

    /**
     * [GET] Download Method
     * 
     * @return string Download Contents
     */
    private function download() {
	$res = 0;
	$fp = fopen('php://temp/maxmemory:' . (5 * 1024 * 1024), 'r+');

	$c_data = str_replace("\\n", "\n", str_replace("\\t", "\t", $this->file_data));
	$arr = explode('\n', $c_data);

	if ($fp) {
	    foreach ($arr as $row) {
		fputs($fp, $row);
	    }

	    header('Content-Type: application/octet-stream');
	    header('Content-Disposition: attachment; filename="' . $this->file_name . '"');
	    header('Content-Transfer-Encoding: binary');

	    rewind($fp);
	    $csv = mb_convert_encoding(stream_get_contents($fp), 'SJIS-win', 'UTF-8');
	    fclose($fp);
	} else {
	    $res = 1;
	}

	print $csv;
	return $res;
    }

    /**
     * [GET] Read Data Convertion
     * 
     * Demilination and Convertion by File Types.
     * 
     * @param string $read_data row readData
     * @return string|array Convert readData
     */
    private function dataConvert($read_data) {
	$res = '';
	switch ($this->file_type) {
	    case 0: //CSV
		$dec = explode('\n', $read_data);
		$res = [];
		foreach ($dec as $d) {
		    $sp = explode($d, $this->csv_split);
		    if (isset($res[$sp[0]])) {
			array_push($res[$sp[0]], array_shift($sp));
		    } else {
			$res[$sp[0]] = array_shift($sp);
		    }
		}
		break;
	    case 1: //JSON
		$res = json_decode($read_data, true);
		break;
	    case 2: //TEXT
		$res = $read_data;
		break;
	}
	return $res;
    }

}

/**
 * [FUNCTION] Webサーバ上の設定画面を表示します
 * 
 * @param type $filename
 * @return array JSONデータの設定情報を返します<br>
 * ・[host] => [データベースのホスト情報]
 * ・[port] => [データベースのポート情報]
 * ・[user] => [データベースのユーザ情報]
 * ・[password] => [データベースのパスワード情報]
 * ・[database] => [データベースのデータベース情報]
 * ・[logdirectory] => [トラップログの保管場所情報 (フルパス)]
 */
function loadSetting() {
    $file_path = __DIR__ . '/../../data';
    $filename = 'setting.json';
    $fi = new File(0, 1, $file_path, $filename, '');
    return $fi->run();
}
