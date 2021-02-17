<?php

/**
 * [CLASS] Group
 * 
 * <h4>MIB GROUP</h4><hr>
 * MIBグループのオブジェクト定義を行います。
 * 
 * @package VirtualControl_scripts_mib
 * @author ClearNB <clear.navy.blue.star@gmail.com>
 */
class Group {

    /**
     * [STATIC] SET
     * 
     * 作成したオブジェクトをスタックさせるクラス変数です
     * 
     * @var array $set
     */
    private static $set = [];

    /**
     * [VAR] GROUPID
     * 
     * グループの識別IDです
     * 
     * @var int $groupid
     */
    private $groupid;

    /**
     * [VAR] OID
     * 
     * グループのOIDです
     * 
     * @var string $oid
     */
    private $oid;

    /**
     * [VAR] NAME
     * 
     * グループの名前です
     * 
     * @var string $name
     */
    private $name;

    /**
     * [SET] CONSTRUCTOR
     * 
     * オブジェクトコンストラクタです
     * 
     * @param int $groupid グループID
     * @param string $oid グループOID
     * @param string $groupname グループ名
     */
    public function __construct($groupid, $oid, $groupname) {
	$this->groupid = $groupid;
	$this->oid = $oid;
	$this->name = $groupname;
	array_push(self::$set, $this);
	usort(self::$set, ['Group', 'cmp_id']);
    }

    /**
     * [GET] 配列データ取得
     * 
     * スタック内にある全てのデータを取得します。
     * 
     * @return array|null スタック内にオブジェクトがある場合は配列データを、そうでない場合はnullを返します。
     */
    public static function getDataArray() {
	$res = [];
	foreach (self::$set as $s) {
	    $key = $s->groupid;
	    $res[$key] = [
		'GROUP_ID' => $s->groupid,
		'GROUP_OID' => $s->oid,
		'GROUP_NAME' => $s->name,
		'GROUP_SUB_COUNT' => Sub::getParentCount($key)
	    ];
	}
	return $res;
    }

    /**
     * [GET] オブジェクト検索ファンクション
     * 
     * 一意の値「グループID」をもとにオブジェクトがあるかどうかを探し、存在していればtrue、そうでない場合はfalseを返します
     * 
     * @param int $groupid グループID
     * @return bool 一意の値「グループID」をもとにオブジェクトがあるかどうかを探し、存在していればtrue、そうでない場合はfalseを返します
     */
    public static function search($groupid) {
	$res = false;
	$size = sizeof(self::$set);
	$min = 0;
	$max = $size - 1;

	while ($min <= $max && $min >= 0 && $max < $size) {
	    $half = intval(($max + $min) / 2);
	    if (self::$set[$half]->groupid == $groupid) {
		$res = true;
		break;
	    } else if (self::$set[$half]->groupid > $groupid) {
		$max = $half - 1;
	    } else {
		$min = $half + 1;
	    }
	}
	return $res;
    }

    /**
     * [SET] スタック状態リセット
     * 
     * 配列内のデータをリセットします
     * 
     * @return void 
     */
    public static function resetArray(): void {
	self::$set = [];
    }

    /**
     * [GET] グループOID取得
     * 
     * サブツリーからのファンクション<br>
     * 親元のOIDを取得します
     * 
     * @param int $groupid グループIDを指定します
     * @return string|null 指定した値がスタック内に存在すればそのOIDを、なければnullが返されます
     */
    public static function getOID($groupid): string {
	$res = '';
	$size = sizeof(self::$set);
	$min = 0;
	$max = $size - 1;

	while ($min <= $max && $min >= 0 && $max < $size) {
	    $half = intval(($max + $min) / 2);
	    if (self::$set[$half]->groupid == $groupid) {
		$res = self::$set[$half]->oid;
		break;
	    } else if (self::$set[$half]->groupid > $groupid) {
		$max = $half - 1;
	    } else {
		$min = $half + 1;
	    }
	}
	return $res;
    }

    private static function cmp_id($a, $b) {
	return strcmp($a->groupid, $b->groupid);
    }
}

/**
 * [CLASS] Sub
 * 
 * <h4>MIB SUB</h4><hr>
 * MIBサブのオブジェクト定義を行います。
 * 
 * @package VirtualControl_scripts_mib
 * @author ClearNB <clear.navy.blue.star@gmail.com>
 */
class Sub {

    /**
     * [STATIC] SET
     * 
     * 作成したオブジェクトをスタックさせるクラス変数です
     * 
     * @var array $set
     */
    private static $set = [];

    /**
     * [VAR] PARENT GROUPID
     * 
     * 対象サブツリーのグループID
     * 
     * @var int $parent_groupid
     */
    private $parent_groupid;

    /**
     * [VAR] SUBID
     * 
     * サブツリーID
     * 
     * @var int $subid
     */
    private $subid;

    /**
     * [VAR] OID
     * 
     * サブツリーOID
     * 
     * @var string $oid
     */
    private $oid;

    /**
     * [VAR] SUB NAME
     * 
     * サブツリー名
     * 
     * @var string $name 
     */
    private $name;

    /**
     * [VAR] UPDATE TIME
     * 
     * サブツリー最終更新時間
     * 
     * @var string $updatetime
     */
    private $updatetime;

    /**
     * [SET] CONSTRUCTOR
     * 
     * オブジェクトコンストラクタです
     * 
     * @param int $parent_groupid 親グループID
     * @param int $subid サブツリーID
     * @param string $oid サブツリーOID
     * @param string $subname サブツリー名
     * @param string $updatetime サブツリー更新時間
     */
    public function __construct($parent_groupid, $subid, $oid, $subname, $updatetime) {
	if ($subid) {
	    $this->parent_groupid = $parent_groupid;
	    $this->subid = $subid;
	    $this->setOID($oid);
	    $this->name = $subname;
	    $this->updatetime = $this->setUpdateTime($updatetime);
	    array_push(self::$set, $this);
	    usort(self::$set, ['Sub', 'cmp_id']);
	}
    }

    /**
     * [GET] 更新時間データ設定
     * 
     * 更新時間の格納状況を確認し、ない場合は「\<新規\>」にします
     * 
     * @param string $data 更新時間データを指定します
     * @return string 上記に設定された値が返ります
     */
    private function setUpdateTime($data): string {
	$res = $data;
	if (!$res) {
	    $res = '<新規>';
	}
	return $res;
    }

    /**
     * [GET] 配列データ取得
     * 
     * スタック内にある全てのデータを取得します
     * 
     * @return array|null スタック内にオブジェクトがある場合は配列データを、そうでない場合はnullを返します。
     */
    public static function getDataArray() {
	$res = [];
	foreach (self::$set as $s) {
	    $key = $s->parent_groupid;
	    if (!isset($res[$key])) {
		$res[$key] = [];
	    }
	    $res[$key][$s->subid] = [
		'SUB_ID' => $s->subid,
		'SUB_OID' => $s->oid,
		'SUB_NAME' => $s->name,
		'SUB_UPDATETIME' => $s->updatetime,
		'SUB_NODE_COUNT' => Node::getParentCount($s->subid)
	    ];
	}
	return $res;
    }

    /**
     * [GET] オブジェクト検索ファンクション
     * 
     * 一意の値「サブツリーID」をもとにオブジェクトがあるかどうかを探し、存在していればtrue、そうでない場合はfalseを返します
     * 
     * @param int $subid サブツリーID
     * @return bool 一意の値「サブツリーID」をもとにオブジェクトがあるかどうかを探し、存在していればtrue、そうでない場合はfalseを返します
     */
    public static function search($subid) {
	$res = '';
	$size = sizeof(self::$set);
	$min = 0;
	$max = $size - 1;
	$search = intval($subid);

	while ($min <= $max && $min >= 0 && $max < $size) {
	    $half = intval(($max + $min) / 2);
	    $target = intval(self::$set[$half]->subid);
	    if ($target == $search) {
		$res = self::$set[$half];
		break;
	    } else if ($target > $search) {
		$max = $half - 1;
	    } else {
		$min = $half + 1;
	    }
	}
	return $res;
    }

    /**
     * [SET] スタック状態リセット
     * 
     * 配列内のデータをリセットします
     * 
     * @return void 
     */
    public static function resetArray(): void {
	self::$set = [];
    }

    /**
     * [GET] 親グループ個数取得
     * 
     * @param int $target_groupid 対象となる親グループIDを指定します
     * @return int 指定した対象親グループの個数を返します
     */
    public static function getParentCount($target_groupid): int {
	$res = 0;
	foreach (self::$set as $s) {
	    $res += ($s->parent_groupid == $target_groupid) ? 1 : 0;
	}
	return $res;
    }

    /**
     * [SET] OIDを設定します
     * 
     * @param int $soid サブツリーの一部であるSOIDを指定します
     * @return void 元のグループOIDに対してサブツリーOIDを結合します
     */
    private function setOID($soid): void {
	$res = Group::getOID($this->parent_groupid);
	$this->oid = $res . '.' . $soid;
    }

    /**
     * [GET] サブツリーOID取得
     * 
     * ノードからのファンクション<br>
     * 親元のOIDを取得します
     * 
     * @param int $subid サブツリーIDを指定します
     * @return string|null 指定した値がスタック内に存在すればそのOIDを、なければnullが返されます
     */
    public static function getOID($subid): string {
	$res = self::search($subid);
	return ($res) ? $res->oid : '';
    }

    private static function cmp_id($a, $b) {
	return strcmp($a->subid, $b->subid);
    }

}

/**
 * [CLASS] Node
 * 
 * <h4>MIB NODE</h4><hr>
 * MIBノードのオブジェクト定義を行います。
 * 
 * @package VirtualControl_scripts_mib
 * @author ClearNB <clear.navy.blue.star@gmail.com>
 */
class Node {

    /**
     * [STATIC] SET
     * 
     * 作成したオブジェクトをスタックさせるクラス変数です
     * 
     * @var array $set
     */
    private static $set = [];
    private static $table_set = '';

    /**
     * [VAR] PARENT SUBID
     * 
     * 対象ノードの親サブID
     * 
     * @var int $parent_subid
     */
    private $parent_subid;

    /**
     * [VAR] NODE ID
     * 
     * 対象ノードのノードID
     * 
     * @var int $nodeid
     */
    private $nodeid;

    /**
     * [VAR] NODE TYPE
     * 
     * 対象ノードのノードタイプ<br>
     * 0. 通常データ
     * 1. テーブル（定義）
     * 2. テーブルデータ
     * 3. トラップデータ（通常）
     * 4. トラップデータ（テーブルデータ）
     * 
     * @var int node_type
     */
    private $node_type;

    /**
     * [VAR] OID
     * 
     * ノードのOIDです
     * 
     * @var string $oid
     */
    private $oid;

    /**
     * [VAR] DESCR
     *
     * 項目名（英名）
     * 
     * @var string $descr
     */
    private $descr;

    /**
     * [VAR] INDEX OPTION
     * 
     * インデックスありでテーブルデータを取得する場合のオプションデータ
     * 
     * @var int $index_option
     */
    private $index_option;

    /**
     * [VAR] JAPTLANS
     * 
     * 項目名（日本語名）
     * 
     * @var string $japtlans
     */
    private $japtlans;

    /**
     * [VAR] ICON NAME
     * 
     * アイコン名
     * 
     * @var string $icon_name
     */
    private $icon_name;

    /**
     * [VAR] ICON ID
     * 
     * アイコンID
     * 
     * @var int $icon_id
     */
    private $icon_id;

    /**
     * [VAR] Sub
     * 
     * @var string $sub 
     */
    private $sub;

    /**
     * [VAR] TableID
     * 
     * @var int $tableid
     */
    private $tableid;

    /**
     * [VAR] TableDataOID
     * 
     * @var array $table_data_oid
     */
    private $table_data_oid;

    /**
     * [CONSTRUCTOR] コンストラクタ
     * 
     * オブジェクトコンストラクタです
     * 
     * @param int $parent_subid 親サブID
     * @param int $nodeid ノードID
     * @param int $node_type ノードタイプ
     * @param int $oid ノードOID（差分）
     * @param type $sub ノードサブ
     * @param int $tableid テーブルID
     * @param int $option オプション情報
     * @param string $descr 項目名（英名）
     * @param string $japtlans 項目名（日本語）
     * @param int $icon_id アイコンID
     * @param string $icon_name アイコン名
     */
    public function __construct($parent_subid, $nodeid, $node_type, $oid, $sub, $tableid, $option, $descr, $japtlans, $icon_id, $icon_name = '') {
	if ($nodeid) {
	    $this->parent_subid = $parent_subid;
	    $this->nodeid = $nodeid;
	    $this->node_type = $node_type;
	    $this->sub = $sub;
	    $this->tableid = $tableid;
	    $this->setOID($oid);
	    $this->descr = $descr;
	    $this->japtlans = $japtlans;
	    $this->icon_id = $icon_id;
	    $this->icon_name = $icon_name;
	    $this->index_option = $option;
	    array_push(self::$set, $this);
	    $this->setTable();
	    $this->setTableData();
	}
    }

    /**
     * [GET] 配列データ取得
     * 
     * スタック内にある全てのデータを取得します。
     * 
     * @return array|null スタック内にオブジェクトがある場合は配列データを、そうでない場合はnullを返します。
     */
    public static function getDataArray() {
	$res = [];
	foreach (self::$set as $s) {
	    $key1 = $s->parent_subid;
	    $key2 = $s->oid;
	    if (!isset($res[$key1])) {
		$res[$key1] = [];
	    }
	    $res[$key1][$key2] = [
		'NODE_ID' => $s->nodeid,
		'NODE_TYPE' => $s->node_type,
		'NODE_SUB' => $s->sub,
		'NODE_TABLEID' => $s->tableid,
		'NODE_OID' => $s->oid,
		'NODE_DESCR' => $s->descr,
		'NODE_OPTION' => $s->index_option,
		'NODE_JAPTLANS' => $s->japtlans,
		'NODE_ICON_ID' => $s->icon_id,
		'NODE_ICON_NAME' => $s->icon_name
	    ];
	    if (is_array($s->table_data_oid)) {
		$res[$key1][$key2]['NODE_INDEX_OID'] = $s->table_data_oid;
	    }
	}
	return $res;
    }

    /**
     * [GET] 親サブツリー個数取得
     * 
     * @param int $target_subid 対象となる親サブツリーIDを指定します
     * @return int 指定した対象サブツリーの個数を返します
     */
    public static function getParentCount($target_subid): int {
	$res = 0;
	foreach (self::$set as $s) {
	    $res += ($s->parent_subid == $target_subid) ? 1 : 0;
	}
	return $res;
    }

    /**
     * [SET] スタック状態リセット
     * 
     * 配列内のデータをリセットします
     * 
     * @return void 
     */
    public static function resetArray(): void {
	self::$set = [];
	self::$table_set = '';
    }

    /**
     * [SET] OID設定
     * 
     * ノードのOIDを設定します
     * 
     * @param int $noid ノードOID差分を指定します
     * @return void サブツリーのOIDを取得し、データの状態を確認しながらOIDを設定します
     */
    private function setOID($noid): void {
	$res = Sub::getOID($this->parent_subid);

	$res .= '.' . $noid;
	if ($this->sub) {
	    $res .= '.' . $this->sub;
	}
	if ($this->node_type == 2 || $this->node_type == 4) {
	    $res .= '.1';
	}
	if ($this->tableid != 0 && $this->node_type == 2) {
	    $res .= '.' . $this->tableid;
	}
	$this->oid = $res;
    }

    private function setTable() {
	if ($this->tableid == 0 && $this->node_type == 1) {
	    self::$table_set = $this;
	    $this->table_data_oid = [];
	}
    }

    private function setTableData() {
	if (($this->node_type == 2 || $this->node_type == 4) && $this->index_option != 0) {
	    if ($this->data_complete($this->oid)) {
		array_push(self::$table_set->table_data_oid, $this->oid);
	    }
	}
    }

    private function data_complete($data): bool {
	return (strpos($data, self::$table_set->oid) !== false);
    }

}

/**
 * [CLASS] MIBData
 * 
 * <h4>MIB Data v1.2.0</h4><hr>
 * MIB Dataは、MIBをデータベースから取得・データの加工を行うクラスです。
 * クラスメソッドとしてMIBの取得をオブジェクト生成を行わなくても可能です。
 * 
 * @package VirtualControl_scripts_mib
 * @author ClearNB <clear.navy.blue.star@gmail.com>
 */
class MIBData {

    /**
     * [GET] MIBデータ取得
     * 
     * MIBデータをグループから取得します
     * 
     * @param int $query_num クエリナンバー（0..グループ-サブツリー, 1..グループ-ノード, 2..グループ-ノード【アイコン情報込】）
     * @param int $type タイプナンバー（0..全て, 1..TYPEが0, 1, 2のみ, 2..TYPEが3, 4のみ）
     * @param array|null $filter_subid フィルタしたいサブツリーOIDを、配列として指定します（Default: null）
     * @return array|null 取得に成功した場合はarray、失敗した場合はnullとして返されます。
     */
    public function getMIB($query_num, $type = 0, $filter_subid = '') {
	$res = '';
	if ($query_num >= 0 && $query_num <= 3) {
	    $data = self::getMIBParam($query_num, $type, $filter_subid);
	    $select = select(false, $data[0], $data[1], $data[2]);
	    if ($select) {
		$res = [];
		$sdata = getArray($select);
		foreach ($sdata as $d) {
		    if (!Group::search($d['GID'])) {
			new Group($d['GID'], $d['GOID'], $d['GNAME']);
		    }
		    if (!Sub::search($d['SID'])) {
			new Sub($d['GID'], $d['SID'], $d['SOID'], $d['SNAME'], $d['UTIME']);
		    }
		    if ($query_num == 1) {
			new Node($d['SID'], $d['NID'], $d['TYPE'], $d['NOID'], $d['SUB'], $d['TABLEID'], $d['OPTIONID'], $d['DESCR'], $d['JAPTLANS'], $d['ICONID']);
		    } else if ($query_num == 2 || $query_num == 3) {
			new Node($d['SID'], $d['NID'], $d['TYPE'], $d['NOID'], $d['SUB'], $d['TABLEID'], $d['OPTIONID'], $d['DESCR'], $d['JAPTLANS'], $d['ICONID'], $d['ICON']);
		    }
		}
		$res['GROUP'] = Group::getDataArray();
		$res['SUB'] = Sub::getDataArray();
		if ($query_num == 1 || $query_num == 2 || $query_num == 3) {
		    $res['NODE'] = Node::getDataArray();
		}
		self::resetAllDataArray();
	    }
	}
	//var_dump($res);
	return $res;
    }

    /**
     * [SET] 全スタックデータリセット
     * 
     * スタック内にあった全てのデータをリセットし、初期化させます
     * 
     */
    private static function resetAllDataArray() {
	Group::resetArray();
	Sub::resetArray();
	Node::resetArray();
    }

    /**
     * [GET] MIBデータベースクエリ取得
     * 
     * クエリナンバーを受け取り、セレクタクエリに適切なクエリ内容を取得します
     * 
     * @param int $query_num クエリナンバー（0..グループ-サブツリー, 1..グループ-ノード, 2..グループ-ノード【アイコン情報込】, 3..グループ-ノード【アイコン情報込・NULL込】）
     * @param int $type タイプナンバー（0..全て, 1..TYPEが0, 1, 2のみ, 2..TYPEが3, 4のみ）
     * @param array|null $filter_subid フィルタしたいサブツリーOIDを、配列として指定します（Default: null）
     * @return array|null クエリナンバーが0-1の間であれば、それに合ったデータを取得します
     */
    private function getMIBParam($query_num, $type = 0, $filter_subid = '') {
	$query = '';
	switch ($query_num) {
	    case 0: //グループ - サブツリー
		$query = [
		    'GSC_MIB_GROUP a INNER JOIN GSC_MIB_SUB b ON a.GID = b.GID',
		    'a.GID, a.GOID, a.GNAME, b.SID, b.SOID, b.SNAME, b.UTIME',
		    'GROUP BY a.GID, a.GNAME, b.SID, b.SOID, b.SNAME, b.UTIME'
		];
		break;
	    case 1: //グループ - ノード（アイコンなし）
		$query = [
		    'GSC_MIB_GROUP a INNER JOIN GSC_MIB_SUB b ON a.GID = b.GID INNER JOIN GSC_MIB_NODE c ON b.SID = c.SID',
		    'a.GID, a.GOID, a.GNAME, b.SID, b.SOID, b.SNAME, b.UTIME, c.NID, c.NOID, c.SUB, c.TABLEID, c.TYPE, c.OPTIONID, c.DESCR, c.JAPTLANS',
		    'ORDER BY a.GID, b.SID, c.NID'
		];
		break;
	    case 2: //グループ - ノード（アイコン有り）
		$query = [
		    'GSC_MIB_GROUP a INNER JOIN GSC_MIB_SUB b ON a.GID = b.GID INNER JOIN GSC_MIB_NODE c ON b.SID = c.SID LEFT OUTER JOIN GSC_ICONS d ON c.ICONID = d.ICONID',
		    'a.GID, a.GOID, a.GNAME, b.SID, b.SOID, b.SNAME, b.UTIME, c.NID, c.NOID, c.SUB, c.TABLEID, c.TYPE, c.OPTIONID, c.DESCR, c.JAPTLANS, c.ICONID, d.ICON',
		    'ORDER BY a.GID, b.SID, c.NID'
		];
		break;
	    case 3: //グループ - ノード（NULL状態でも全てのデータを抽出）
		$query = [
		    'GSC_MIB_GROUP a LEFT OUTER JOIN GSC_MIB_SUB b ON a.GID = b.GID LEFT OUTER JOIN GSC_MIB_NODE c ON b.SID = c.SID LEFT OUTER JOIN GSC_ICONS d ON c.ICONID = d.ICONID',
		    'a.GID, a.GOID, a.GNAME, b.SID, b.SOID, b.SNAME, b.UTIME, c.NID, c.NOID, c.SUB, c.TABLEID, c.TYPE, c.OPTIONID, c.DESCR, c.JAPTLANS, c.ICONID, d.ICON',
		    'ORDER BY b.SID, c.NID'
		];
		break;
	}
	if ($query && $type && $filter_subid && is_array($filter_subid)) {
	    $other = array_pop($query);
	    array_push($query, 'WHERE b.SID IN (' . implode(', ', $filter_subid) . ')');
	    switch ($type) {
		case 1:
		    array_push($query, ' AND c.TYPE IN (0, 1, 2)');
		    break;
		case 2:
		    array_push($query, ' AND c.TYPE IN (3, 4, 5)');
		    break;
	    }
	    array_push($query, $other);
	} else if ($query && $type) {
	    $other = array_pop($query);
	    switch ($type) {
		case 1:
		    array_push($query, 'WHERE c.TYPE IN (0, 1, 2)');
		    break;
		case 2:
		    array_push($query, 'WHERE c.TYPE IN (3, 4, 5)');
		    break;
	    }
	    array_push($query, $other);
	}
	return $query;
    }

}
