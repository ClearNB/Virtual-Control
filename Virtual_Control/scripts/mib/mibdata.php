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
		'GROUP_OID' => $s->oid,
		'GROUP_NAME' => $s->name
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

	while ($min < $max) {
	    $half = ($max + $min) / 2;
	    if (self::$set[$half]->groupid == $groupid) {
		$res = true;
		break;
	    } else if (self::$set[$half]->groupid > $groupid) {
		$min = $half + 1;
	    } else {
		$max = $half - 1;
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
	$this->parent_groupid = $parent_groupid;
	$this->subid = $subid;
	$this->oid = $oid;
	$this->name = $subname;
	$this->updatetime = $this->setUpdateTime($updatetime);
	array_push(self::$set, $this);
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
	    $key = $s->subid;
	    $res[$key] = [
		'PARENT' => $s->parent_groupid,
		'SUB_OID' => $s->oid,
		'SUB_NAME' => $s->name,
		'SUB_UPDATETIME' => $s->updatetime
	    ];
	}
	return $res;
    }

    /**
     * [GET] オブジェクト検索ファンクション
     * 
     * 一意の値「サブツリーID」をもとにオブジェクトがあるかどうかを探し、存在していればtrue、そうでない場合はfalseを返します
     * 
     * @param int $subid グループID
     * @return bool 一意の値「サブツリーID」をもとにオブジェクトがあるかどうかを探し、存在していればtrue、そうでない場合はfalseを返します
     */
    public static function search($subid) {
	$res = false;
	$size = sizeof(self::$set);
	$min = 0;
	$max = $size - 1;

	while ($min < $max) {
	    $half = ($max + $min) / 2;
	    if (self::$set[$half]->subid == $subid) {
		$res = true;
		break;
	    } else if (self::$set[$half]->subid > $subid) {
		$min = $half + 1;
	    } else {
		$max = $half - 1;
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
     * [VAR] ICONID
     * 
     * アイコンID
     * 
     * @var int $iconid
     */
    private $iconid;

    /**
     * [SET] CONSTRUCTOR
     * 
     * オブジェクトコンストラクタです
     * 
     * @param int $parent_subid 親サブID
     * @param int $nodeid ノードID
     * @param int $node_type ノードタイプ(0. 通常データ, 1. テーブル, 2. テーブルデータ, 3. トラップデータ（通常）, 4. トラップデータ（テーブルデータ））
     * @param string $oid ノードOID
     * @param string $descr 項目名（英名）
     * @param string $japtlans 項目名（日本語名）
     * @param int $iconid アイコンID
     */
    public function __construct($parent_subid, $nodeid, $node_type, $oid, $descr, $japtlans, $iconid) {
	$this->parent_subid = $parent_subid;
	$this->nodeid = $nodeid;
	$this->node_type = $node_type;
	$this->oid = $oid;
	$this->descr = $descr;
	$this->japtlans = $japtlans;
	$this->iconid = $iconid;
	$this->setIndexOption();
	array_push(self::$set, $this);
    }

    /**
     * [SET] インデックスオプション設定
     * 
     * 受け取ったデータから、OIDをもとに解析を行い、インデックスオプションがあるかどうかを確認します<br>
     * ある場合は、インデックスオプションに以下の番号が入ります<br>
     * また、インデックスオプションはOIDから消えます<br>
     * 1 .. ipnet（IPアドレス/ポート番号）<br>
     * 2 .. ip（IPアドレス）<br>
     * 3 .. rtpolicy（ルーティングポリシー）<br>
     * 4 .. iptype（IPアドレスバージョン）<br>
     * 5{num} .. {num}文字分データ抜き取り<br>
     * ない場合は、0が入ります
     */
    private function setIndexOption() {
	$this->index_option = 0;
	$r_oid = preg_replace('/^.*[.]/', '', $this->oid);

	if (preg_match('/^(\*|\^\*|\*\~\[.*\])$/', $r_oid)) {
	    $this->oid = preg_replace('/[.](\*|\^\*|\*\~\[.*\]){1,}$/', '', $this->oid);
	    if ($r_oid == '^*') {
		$this->node_type = 1;
	    } else if (preg_match('/(\*\~\[.*\])$/', $r_oid)) {
		$this->node_type = 2;
		$type = str_replace(']', '', str_replace('*~[', '', $r_oid));
		if ($type == 'ipnet') {
		    $this->index_option = 1;
		} else if ($type == 'ip') {
		    $this->index_option = 2;
		} else if ($type == 'rtpolicy') {
		    $this->index_option = 3;
		} else if ($type == 'iptype') {
		    $this->index_option = 4;
		} else if (preg_match('/^([0-9]{1,})$/', $type)) {
		    $this->index_option = 50 + intval($type);
		}
	    } else if ($r_oid == '*') {
		if ($this->node_type == 3) {
		    $this->node_type = 4;
		} else {
		    $this->node_type = 2;
		}
	    } else {
		$this->node_type = 0;
	    }
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
	    $key = $s->nodeid;
	    $res[$key] = [
		'PARENT' => $s->parent_subid,
		'NODE_TYPE' => $s->node_type,
		'NODE_OID' => $s->oid,
		'NODE_DESCR' => $s->descr,
		'NODE_OPTION' => $s->index_option,
		'NODE_JAPTLANS' => $s->japtlans,
		'NODE_ICONID' => $s->iconid
	    ];
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
     * @param integer	$from_id    取得出発地点を設定します（1..グループ, 2..サブツリー, 3..ノード）
     * @param integer	$to_id	    取得到達地点を設定します（1..グループ, 2..サブツリー, 3..ノード）
     * @return array|null 取得に成功した場合はarray、失敗した場合はnullとして返されます。<br>arrayの場合は、データ項目が[[0]->["COL01" => "VALUE01", ...], [1]->{Table_data}, ...]となります。
     */
    public function getMIB($from_id, $to_id) {
	$res = '';
	$query_num = self::getMIBQueryNum($from_id, $to_id);
	if ($query_num != 999) {
	    $data = self::getMIBParam($query_num);
	    $select = select(false, $data[0], $data[1], $data[2]);
	    if ($select) {
		$res = getArray($select);
	    }
	}
	return $res;
    }

    /**
     * [GET] 出力用MIBデータの取得
     * 
     * グループ - ノード間の全てのデータを出力用に加工し、それを返します
     * 
     * @return array|null 取得に成功すればそのデータが、失敗すればnullが返されます
     */
    public function getMIBData() {
	$res = '';
	$data = self::getMIB(1, 3);
	if ($data) {
	    $res = [];
	    //データをグループ・サブツリー・ノードに分ける
	    foreach ($data as $d) {
		if (!Group::search($d['GROUPID'])) {
		    new Group($d['GROUPID'], $d['GROUPOBJECTID'], $d['GROUPNAME']);
		}
		if (!Sub::search($d['SUBID'])) {
		    new Sub($d['GROUPID'], $d['SUBID'], $d['SUBOBJECTID'], $d['SUBNAME'], $d['UPDATETIME']);
		}
		new Node($d['SUBID'], $d['NODEID'], $d['NODETYPE'], $d['NODEOBJECTID'], $d['DESCR'], $d['JAPTLANS'], $d['ICONID']);
	    }
	    $res['GROUP'] = Group::getDataArray();
	    $res['SUB'] = Sub::getDataArray();
	    $res['NODE'] = Node::getDataArray();
	    $this->resetAllDataArray();
	}
	return $res;
    }
    
    /**
     * [SET] 全スタックデータリセット
     * 
     * スタック内にあった全てのデータをリセットし、初期化させます
     * 
     */
    private function resetAllDataArray() {
	Group::resetArray();
	Sub::resetArray();
	Node::resetArray();
    }

    private function getMIBQueryNum($from_id, $to_id) {
	$query_num = 999;
	if ($to_id - $from_id == 0) {
	    switch ($from_id) {
		case 1: $query_num = 0;
		    break;     //グループのみ
		case 2: $query_num = 1;
		    break;     //サブツリーのみ
		case 3: $query_num = 2;
		    break;     //ノードのみ
	    }
	} else {
	    if ($to_id > $from_id) {
		if ($to_id - $from_id == 1) {
		    switch ($to_id) {
			case 2: $query_num = 3;
			    break;  //グループ -> サブツリー
			case 3: $query_num = 4;
			    break;  //サブツリー -> ノード
		    }
		} else {
		    $query_num = 5; //グループ -> ノード
		}
	    }
	}
	return $query_num;
    }

    /**
     * [GET] MIBデータベースクエリ取得
     * 
     * クエリナンバーを受け取り、セレクタクエリに適切なクエリ内容を取得します
     * 
     * @param type $query_num クエリナンバー（getMIBQueryNumで取得したデータ）
     * @return array|null クエリナンバーが0-5の間であれば、それに合った
     */
    private function getMIBParam($query_num) {
	$query = '';
	switch ($query_num) {
	    case 0: //グループのみ
		$query = [
		    'GSC_MIB_GROUP',
		    'GROUPID, GROUPOBJECTID, GROUPNAME',
		    'ORDER BY GROUPID'
		];
		break;
	    case 1: //サブツリーのみ
		$query = [
		    'GSC_MIB_SUB',
		    'SUBID, SUBOBJECTID, SUBNAME, GROUPID, UPDATETIME',
		    'ORDER BY SUBID'
		];
		break;
	    case 2: //ノードのみ
		$query = [
		    'GSC_MIB_NODE',
		    'NODEID, NODEOBJECTID, SUBID, DESCR, JAPTLANS, ICONID',
		    'ORDER BY NODEID'
		];
		break;
	    case 3: //グループ - サブツリー
		$query = [
		    'GSC_MIB_GROUP a INNER JOIN GSC_MIB_SUB b ON a.GROUPID = b.GROUPID',
		    'a.GROUPID, a.GROUPNAME, b.SUBID, b.SUBOBJECTID, b.SUBNAME, b.UPDATETIME',
		    'GROUP BY a.GROUPID, a.GROUPNAME, b.SUBID, b.SUBOBJECTID, b.SUBNAME, b.UPDATETIME'
		];
		break;
	    case 4: //サブツリー - ノード
		$query = [
		    'GSC_MIB_SUB a INNER JOIN GSC_MIB_NODE b ON a.SUBID = b.SUBID',
		    'a.SUBID, a.SUBOBJECTID, a.SUBNAME, a.UPDATETIME, b.NODEID, b.NODEOBJECTID, b.DESCR, b.JAPTLANS, b.ICONID',
		    'GROUP BY a.SUBID, a.SUBOBJECTID, a.SUBNAME, a.UPDATETIME, b.NODEID, b.NODEOBJECTID, b.DESCR, b.JAPTLANS, b.ICONID'
		];
		break;
	    case 5: //グループ - ノード
		$query = [
		    'GSC_MIB_GROUP a INNER JOIN GSC_MIB_SUB b ON a.GROUPID = b.GROUPID INNER JOIN GSC_MIB_NODE c ON b.SUBID = c.SUBID',
		    'a.GROUPID, a.GROUPOBJECTID, a.GROUPNAME, b.SUBID, b.SUBOBJECTID, b.SUBNAME, b.UPDATETIME, c.NODEID, c.NODETYPE, c.NODEOBJECTID, c.DESCR, c.JAPTLANS, c.ICONID',
		    'GROUP BY a.GROUPID, a.GROUPOBJECTID, a.GROUPNAME, b.SUBID, b.SUBOBJECTID, b.SUBNAME, b.UPDATETIME, c.NODEID, c.NODETYPE, c.NODEOBJECTID, c.DESCR, c.JAPTLANS, c.ICONID'
		];
		break;
	}
	return $query;
    }

}
