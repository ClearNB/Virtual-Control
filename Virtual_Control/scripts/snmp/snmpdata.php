<?php

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

    /** @var int $checksum データを配列として格納する際に、これは格納するのに適した値かどうかを判定します。 */
    static private $checksum = 0;

    /** @var int $pre_check OIDのデータに取り込む際に、テーブルデータの項目、かつ1件でもインデックスから値を取る必要のある項目があるかどうかを判定する値です。<br>必要な項目があった際は3を、必要でない場合は2を格納します。 */
    static private $pre_check = 0;

    /** @var array $oids OID情報をここに格納します。 */
    static private $oids = [];

    /** @var array $set SNMPDataでオブジェクトを作成すると、ここにプッシュされます。 */
    static private $set = [];

    /** @var array $inedx_option OIDに記載されたインデックスオプション情報をここに記録します。 */
    static private $index_option = [];

    /** @var string $general_topoid 生成中のオブジェクトの現在の親OIDを記録します。 */
    static private $general_topoid = '';

    /** @var string $oid OIDを格納します。 */
    private $oid;

    /** @var string $descr OIDに対する英語の説明を格納します。 */
    private $descr;

    /** @var string $japtlans OIDに対する日本語の説明を格納します。 */
    private $japtlans;

    /** @var string $icon OIDの説明に合ったアイコン情報を格納します。 */
    private $icon;

    /** @var array|int $value OID別に値を格納します。テーブルデータの場合を考え、格納配列型になります。 */
    private $value;

    /** @var string $index テーブルのインデックスデータを格納します（テーブルのみ有効）。 */
    private $index;

    /** @var int $check (0..通常データ, 1..テーブル, 2..テーブルデータ, 3..テーブルデータ【インデックスがデータ】) */
    private $check;

    /**
     * オブジェクトを生成するコンストラクタです。
     * @param string $oid OIDを格納します。
     * @param string $descr OIDに対する英語の説明を格納します。
     * @param string $japtlans OIDに対する日本語の説明を格納します。
     * @param string $icon アイコン情報を格納します。
     */
    public function __construct($oid, $descr, $japtlans, $icon) {
	$this->oid = $oid;
	$this->descr = $descr;
	$this->japtlans = $japtlans;
	$this->icon = $icon;
	$this->check = 0;
	$this->index = 0;
	$this->value = [];
	$this->setOID();
    }

    /**
     * [SET] OIDについて処理します（コンストラクタ内実行）。
     * 
     * 生成されるたびに、現在のOID情報を解析し、OIDを値を入れやすい形にコンパイルします。<br>
     * 同時に、テーブル・インデックス情報があれば、それについても処理されます。
     * @return void セッターメソッドです。
     */
    public function setOID(): void {
	//OID部分を取り除く -> $r_oid
	$r_oid = preg_replace('/^.*[.]/', '', $this->oid);

	/*
	 * 【インデックス構成について】
	 * ^* ... グループを切断し、新たにテーブルを作成する
	 * * ... 取り出されるものはINDEXなので、それを表示する
	 * *~[設定値,桁] ... テーブル内のインデックスで、使われるものには桁数または設定値を入力する
	 */
	if (preg_match('/(\*|\^\*|\*\~\[.*\]){1,2}$/', $r_oid)) {
	    //^, *, ~[.*] 部分を取り除く
	    $this->oid = preg_replace('/[.](\*|\^\*|\*\~\[.*\]){1,}$/', '', $this->oid);
	    if ($r_oid == '^*') {
		$this->index = [];
		//値はないものとして扱われるため、0を入れる
		$this->value = 0;
		//チェック値を1とする
		$this->check = 1;
		//現在の親OIDとして一時格納する
		self::$general_topoid = $this->oid;
		if (!isset(self::$index_option[self::$general_topoid])) {
		    self::$index_option[self::$general_topoid] = [];
		}
	    } else if (preg_match('/(\*\~\[.*\])$/', $r_oid)) {
		$this->check = 3;
		$type = str_replace(']', '', str_replace('*~[', '', $r_oid));
		array_push(self::$index_option[self::$general_topoid], [$this->oid, $type]);
	    } else if ($r_oid == '*') {
		if (self::$pre_check == 3) {
		    $this->check = 3;
		} else {
		    $this->check = 2;
		}
	    }
	}
	self::$pre_check = $this->check;
	self::$set[$this->oid] = $this;
	array_push(self::$oids, $this->oid);
    }

    public static function setValue($oid, $data): bool {
	$res = self::data_search($oid);
	if ($res) {
	    $data = str_replace('iso', '1', preg_replace('/(["]|[\r\n|\n|\r]|^.*[:]\s)/', '', $data));
	    array_push(self::$set[$res]->value, $data);
	    $chk = self::$set[$res]->check;
	    if ($chk != 0) {
		$sub_v = preg_replace("/(" . $res . ")[.]/", '', $oid);
		$top_oid = preg_replace('/([.][0-9]{1,}){2}$/', '', $res);
		if (!in_array($sub_v, self::$set[$top_oid]->index)) {
		    if ($chk == 3) {
			//echo $top_oid . " : " . $sub_v . '<br>';
			self::setValueFromIndex($top_oid, $sub_v);
		    }
		    array_push(self::$set[$top_oid]->index, $sub_v);
		}
	    }
	    return true;
	} else {
	    return false;
	}
    }

    public static function setValueFromIndex($top_oid, $sub_v) {
	//echo "$top_oid : $sub_v <br />";
	$sub_cat = explode('.', $sub_v);
	$arr = self::$index_option[$top_oid];
	$val_host = [];
	$sub_i = 0;
	for ($i = 0; $i < sizeof($arr); $i++) {
	    $var_i = $arr[$i][0];
	    $var_v = $arr[$i][1];
	    $dem = 1;
	    $data = '';
	    if (preg_match('/^[0-9]{1,}/', $var_v)) {
		$data = implode(' ', array_slice($sub_cat, $sub_i, intval($var_v)));
		$dem = intval($var_v);
	    } else if ($var_v == 'ipnt' || $var_v == 'ip') {
		$add_i = 0;
		$isport = false;
		if ($var_v == 'ipnt') {
		    $add_i = 1;
		    $isport = true;
		}
		switch ($sub_cat[$sub_i]) {
		    case 0:
			$data = 'IPアドレス特定不可';
			$dem = 1 + $add_i;
			break;
		    case 4:
			$data = getIPv4(array_slice($sub_cat, $sub_i + 1, 4 + $add_i), $isport);
			$dem = 5 + $add_i;
			break;
		}
		if ($sub_cat[$sub_i] >= 16) {
		    $size = $sub_cat[$sub_i] + $add_i;
		    $data = getIPv6(array_slice($sub_cat, $sub_i + 1, $size), $size, $isport);
		    $dem = $size + 1;
		}
	    } else if ($var_v == 'iptype') {
		$data = getIPType($sub_cat[$sub_i]);
		if ($sub_cat[$sub_i] != 0) {
		    $dem = 1;
		} else {
		    $dem = 0;
		}
	    } else if ($var_v == 'rtpolicy') {
		$dem = intval($val_host['1.3.6.1.2.1.4.24.7.1.3']);
		$data = implode(' ', array_slice($sub_cat, $sub_i, $dem));
	    }
	    array_push(self::$set[$var_i]->value, $data);
	    $val_host[$var_i] = $data;
	    $sub_i += $dem;
	}
    }

    public function getValue() {
	if ($this->check != 0) {
	    if ($this->check == 1 && !$this->index) {
		self::$checksum = 1;
		return false;
	    } else if ($this->check == 1) {
		self::$checksum = 0;
	    }
	    if (self::$checksum == 1) {
		return false;
	    }
	}
	return ['OID' => $this->oid, 'DESCR' => $this->descr, 'JAPTLANS' => $this->japtlans, 'ICON' => $this->icon, 'CHECK' => $this->check, 'VALUE' => $this->value, 'INDEX' => $this->index];
    }

    public static function getDataArray(): array {
	//var_dump(self::$set);
	//var_dump(self::$index_option);
	$result = ['OID' => [], 'DESCR' => [], 'JAPTLANS' => [], 'ICON' => [], 'VALUE' => [], 'CHECK' => [], 'INDEX' => [], 'CSV' => ''];
	foreach (self::$set as $snmp) {
	    $data = $snmp->getValue();
	    if ($data) {
		array_push($result['OID'], $data['OID']);
		$oid = $data['OID'];
		$result['DESCR'][$oid] = $data['DESCR'];
		$result['JAPTLANS'][$oid] = $data['JAPTLANS'];
		$result['ICON'][$oid] = $data['ICON'];
		if (empty($data['VALUE'])) {
		    $result['VALUE'][$oid] = ['<データなし>'];
		} else {
		    $result['VALUE'][$oid] = $data['VALUE'];
		}
		$result['CHECK'][$oid] = $data['CHECK'];
		$result['INDEX'][$oid] = $data['INDEX'];
	    }
	}
	$csv = self::convertToCSV($result);
	return ['csv' => $csv, 'res' => $result];
    }

    private static function convertToCSV($data) {
	$res = '';

	//1: OIDの取得
	$oid_data = $data['OID'];

	//2: OID別の処理が全て終わるまでループ
	foreach ($oid_data as $oid) {
	    //2-1: OIDごとの DESCR, JAPTLANS を取得し、これで項目を作成
	    $descr = $data['DESCR'][$oid];
	    $japtlans = $data['JAPTLANS'][$oid];
	    $column_name = "$oid,$descr,$japtlans";
	    
	    //2-2: OIDで参照される CHECK を確認
	    $check = $data['CHECK'][$oid];

	    $sw_data = '';
	    
	    switch($check) {
		case 0: //2-3-1: [0の場合] -> そのままデータ側の変数に格納
		    $sw_data = str_replace(',', ' ', $data['VALUE'][$oid][0]);
		    break;
		case 1: //2-3-2: [1の場合] -> INDEXを読み込み、出てきた配列データをそのままデータに落とし込む（implodeでタブ区切りにする）
		    $sw_data = str_replace('|', ',', str_replace(',', ' ', implode('|', $data['INDEX'][$oid])));
		    break;
		case 2: //2-3-3: [2または3の場合] -> VALUEのデータを配列として読み込み、そのままデータに落とし込む（implodeでタブ区切り）
		case 3:
		    $sw_data = str_replace('|', ',', str_replace(',', ' ', implode('|', $data['VALUE'][$oid])));
		    break;
	    }
	    //2-4: 項目とデータをつなぎ合わせる（タブ区切り）
	    //2-5: 改行文字を加える
	    $res .= $column_name . ',' . $sw_data . '\n';
	}
	
	return $res;
    }

    /**
     * OIDデータから指定されたOIDがあるかどうかを探索します。
     * @param type $data
     * @return string|bool 
     */
    public function data_search($data): string {
	$r = false;
	$oids_r = array_reverse(self::$oids);
	foreach ($oids_r as $o) {
	    $s_o = preg_replace('/[.]$/', '', $o) . '.';
	    if (strpos($data, $s_o) !== false) {
		$r = $o;
		break;
	    }
	}
	if ($r) {
	    if (self::$set[$r]->value == 0) {
		$r = false;
	    }
	}
	return $r;
    }

    public static function resetStatic(): void {
	self::$checksum = 0;
	self::$pre_check = 0;
	self::$oids = [];
	self::$set = [];
	self::$index_option = [];
	self::$general_topoid = '';
    }

}
