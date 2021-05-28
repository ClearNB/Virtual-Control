<?php

include_once __DIR__ . '/index_data.php';

/**
 * [CLASS] SNMPData
 * 
 * 【クラス概要】<br>
 * SNMPデータおよびMIBデータを統括するGETTER専用クラスです。<br>
 * SNMPWALKで使用します。
 * 
 * @package VirtualControl_scripts_snmp
 * @author ClearNB<clear.navy.blue.star@gmail.com>
 * @category class
 */
class SNMPData {

    private static $mibdata = [];
    private static $otherdata = [];
    private $top_oid;
    private $mib;
    private $oid;

    /**
     * [SET] CONSTRUCTOR
     * 
     * オブジェクトコンストラクタです
     * 
     * @param int $type オブジェクトの追加方法を指定します（0..OIDと値を追加してMIBデータに保存, 1..setValueに遷移して追加）
     * @param string $oid SNMPで取得したOID（もしくはデータ加工されたOID）
     * @param string $value OIDに対する値
     */
    public function __construct($type, $oid, $value) {
	switch ($type) {
	    case 0:
		$this->setValueCon($oid, $value);
		break;
	    case 1:
		$this->setValue($oid, $value);
		break;
	}
    }

    /**
     * [SET] MIBデータ（static）の変更
     * 
     * MIBデータはサブツリー以下のノードデータを参照しているため、サブツリーが変更されるごとに、このファンクションをご利用ください
     * 
     * @param array $mibdata MIBDataで取得したMIBデータのうち、NODE -> (サブツリーID) を指定します
     */
    public static function setMIBData($mibdata) {
	self::$mibdata = $mibdata;
    }

    /**
     * [SET] SNMPデータ設定
     * 
     * SNMPのデータについて加工します
     * 
     * @param string $oid OIDを指定します
     * @param string $data OIDに対する値を指定します
     */
    private function setValue($oid, $data): void {
	$res = self::dataSearch($oid);
	if ($res) {
	    $this->setValueCon($res, $data);

	    //テーブルデータ確認
	    $type = $this->mib['NODE_TYPE'];
	    if ($type == 2) {
		//テーブルデータ（MIBデータ）は追加処理を行う
		$this->top_oid = preg_replace('/([.][0-9]{1,}){2}$/', '', $res);

		//MIBデータの親部（テーブル）に、インデックスデータを追加する
		if (!isset(self::$mibdata[$this->top_oid]['INDEX'])) {
		    self::$mibdata[$this->top_oid]['INDEX'] = [];
		}
		//インデックスは一意である必要があるため、ない場合のみ追加を許可する
		$index = str_replace($res . '.', '', $oid);
		if (!in_array($index, self::$mibdata[$this->top_oid]['INDEX'])) {
		    array_push(self::$mibdata[$this->top_oid]['INDEX'], $index);
		    //このテーブルにはインデックスがあり、かつオプションがあるとき
		    if (isset(self::$mibdata[$this->top_oid]['NODE_INDEX_OID'])) {
			$top_d = self::$mibdata[$this->top_oid]['NODE_INDEX_OID'];
			$this->setValueFromIndex($this->top_oid, $top_d);
		    }
		}
	    }
	} else {
	    //その他データに格納（配列出力用に使用）
	    array_push(self::$otherdata, '「' . $oid . ' : ' . $data . '」が有効なMIBデータがありませんでした。');
	}
    }

    /**
     * [SET] SNMPデータ挿入
     * 
     * @param string $oid OIDを指定します（MIBデータ準拠）
     * @param string $value 値を指定します
     * @return void 
     */
    private function setValueCon($oid, $value): void {
	//データの挿入
	$this->oid = $oid;
	$v = str_replace('iso', '1', preg_replace('/(["]|[\r\n|\n|\r]|^.*[:]\s)/', '', $value));
	$this->mib = self::$mibdata[$this->oid];

	//MIBデータにこのオブジェクトを置いておく（出力時にMIBデータを確認用として置くため）
	if (!isset(self::$mibdata[$this->oid]['DATA'])) {
	    self::$mibdata[$this->oid]['DATA'] = [];
	}
	array_push(self::$mibdata[$this->oid]['DATA'], $v);
    }

    /**
     * [SET] インデックスからOIDインデックス設定に基づき値を設定
     * 
     * テーブルデータでインデックスからデータを参照させる必要がある場合、そのインデックスオプションに基づき、テーブルデータとして格納します。
     * 
     * @param string $top_oid そのテーブルデータの親テーブルOIDを指定します
     * @param array $top_d
     */
    private function setValueFromIndex($top_oid, $top_d) {
	//追加部分のインデックスを取得
	$index = self::$mibdata[$top_oid]['INDEX'][sizeof(self::$mibdata[$top_oid]['INDEX']) - 1];
	//インデックスを . によって分割
	$sub_cat = explode('.', $index);
	$sub_i = 0;

	foreach ($top_d as $i_oid) {
	    $option = self::$mibdata[$i_oid]['NODE_OPTION'];
	    $index_data = getDataFromIndex($option, $sub_cat, $sub_i);
	    if ($index_data['CODE'] == 0) {
		new SNMPData(0, $i_oid, $index_data['DATA']);
		$sub_i += $index_data['DEM'];
	    } else if ($index_data['CODE'] == 1) {
		array_push(self::$otherdata, '【' . $i_oid . '（' . $index . '）】' . $index_data['DATA']);
		break;
	    }
	}
    }

    /**
     * [GET] データ配列取得
     * 
     * データ取得をオブジェクト単位で出します
     * 
     * @param string $mibdata 
     * @return type
     */
    private static function getData($mibdata) {
	$value_data = [];
	if (empty($mibdata['DATA'])) {
	    if ($mibdata['NODE_TYPE'] == 0) {
		$value_data = ['<データなし>'];
	    } else if ($mibdata['NODE_TYPE'] == 2) {
		$oid = $mibdata['NODE_OID'];
		$top_oid = preg_replace('/([.][0-9]{1,}){2}$/', '', $oid);
		$index_size = sizeof(self::$mibdata[$top_oid]['INDEX']);
		$value_data = array_fill(0, $index_size, '<データなし>');
	    }
	} else {
	    $value_data = $mibdata['DATA'];
	}
	$res = ['OID' => $mibdata['NODE_OID'],
	    'DESCR' => $mibdata['NODE_DESCR'],
	    'JAPTLANS' => $mibdata['NODE_JAPTLANS'],
	    'ICON' => $mibdata['NODE_ICON_NAME'],
	    'TYPE' => $mibdata['NODE_TYPE'],
	    'DATA' => $value_data
	];
	if (isset($mibdata['INDEX'])) {
	    $res['INDEX'] = $mibdata['INDEX'];
	}
	return $res;
    }

    public static function getDataArray(): array {
	$result = ['DATA' => [], 'CSV' => '', 'ERROR' => []];
	foreach (self::$mibdata as $mib) {
	    $top_oid = preg_replace('/([.][0-9]{1,}){2}$/', '', $mib['NODE_OID']);
	    if (($mib['NODE_TYPE'] == 0) || ($mib['NODE_TYPE'] == 1 && isset($mib['INDEX'])) || ($mib['NODE_TYPE'] == 2 && isset(self::$mibdata[$top_oid]['INDEX']))) {
		$data = self::getData($mib);
		$oid = $data['OID'];
		$result['DATA'][$oid] = $data;
	    }
	}
	$result['CSV'] = self::convertToCSV($result['DATA']);
	$result['ERROR'] = (empty(self::$otherdata) ? ['〈該当データなし〉'] : self::$otherdata);
	return $result;
    }

    private static function convertToCSV($data) {
	$res = '';

	//2: OID別の処理が全て終わるまでループ
	foreach ($data as $d) {
	    //2-1: OIDごとの DESCR, JAPTLANS を取得し、これで項目を作成
	    $oid = $d['OID'];
	    $descr = $d['DESCR'];
	    $japtlans = $d['JAPTLANS'];
	    $column_name = "$oid,$descr,$japtlans";

	    //2-2: OIDで参照される TYPE を確認
	    $type = $d['TYPE'];

	    $sw_data = '';

	    switch ($type) {
		case 0: //2-3-1: [0の場合] -> そのままデータ側の変数に格納
		    $sw_data = str_replace(',', ' ', $d['DATA'][0]);
		    break;
		case 1: //2-3-2: [1の場合] -> INDEXを読み込み、出てきた配列データをそのままデータに落とし込む（implodeでタブ区切りにする） [!INDEXがない場合は、無視されます!]
		    if (isset($d['INDEX'])) {
			$sw_data = str_replace('|', ',', str_replace(',', ' ', implode('|', $d['INDEX'])));
		    }
		    break;
		case 2: //2-3-3: [2の場合] -> VALUEのデータを配列として読み込み、そのままデータに落とし込む（implodeでタブ区切り）
		    $sw_data = str_replace('|', ',', str_replace(',', ' ', implode('|', $d['DATA'])));
		    break;
	    }
	    //2-4: 項目とデータをつなぎ合わせる（コンマ区切り）
	    //2-5: 改行文字を加える
	    $res .= $column_name . ',' . $sw_data . '\n';
	}
	$res .= '【エラーログ】\n' . ((self::$otherdata) ? implode('\n', self::$otherdata) : '〈ログはありません〉') . '\n';
	return $res;
    }

    public static function resetStatic(): void {
	self::$mibdata = [];
	self::$otherdata = [];
    }

    /**
     * [GET] OIDデータ検索
     * 
     * 出力されたデータがデータベース内にあるかどうかを検索します。
     * 
     * @param string $oid 出力先のOIDを指定します
     * @return string ある場合はそのOIDが、ない場合はnullが返されます
     */
    private static function dataSearch($oid): string {
	$res = '';
	$r_flag = false;
	$r_mibdata = array_reverse(self::$mibdata);
	$s_mib = '';
	try {
	    foreach ($r_mibdata as $mib) {
		$s_mib = $mib;
		if (isset($s_mib['NODE_OID']) && strpos($oid, $s_mib['NODE_OID'] . '.') === 0 && $s_mib['NODE_TYPE'] != 1) {
		    $res = $s_mib['NODE_OID'];
		    $r_flag = true;
		} else if ($r_flag) {
		    if (self::$mibdata[$res]['NODE_TYPE'] == 1) {
			$res = '';
		    }
		    break;
		}
	    }
	    return $res;
	} catch (Exception $e) {
	    echo 'Error - 【OIDの検索に失敗しました】<br>検索対象OID: ' . $oid . '<br>エラーが発生したMIB: ';
	    var_dump($s_mib);
	    echo $e;
	    return '';
	}
    }
}
