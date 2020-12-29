<?php

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
     * (0..Group, 1..Subtree, 2..Node)
     * 
     * @var integer $oid_type */
    private $oid_type;

    /**
     * (Ex: 1.3.6.1.2.1.1) without INDEX OPTION
     * 
     * @var string  $oid */
    private $oid;

    /**
     * (Ex: 1.3.6.1.2.1.1.1 -> 1.3.6.1.2.1.1)
     * 
     * @var string  $parent_oid */
    private $parent_oid;

    /**
     * Name for Parent
     * 
     * @var string  $parent_name */
    private $parent_name;

    /**
     * Description for OID, the Language by RFC 1394
     * 
     * @var string  $descr */
    private $descr;

    /**
     * Description for OID, the Language is Japanese. (translated by Project GSC)
     * 
     * @var string  $jap_tlans
     */
    private $jap_tlans;

    public function __construct($oid_type, $oid, $parent_oid, $parent_name, $descr, $jap_tlans) {
	$this->oid_type = $oid_type;
	$this->oid = $oid;
	$this->parent_oid = $parent_oid;
	$this->parent_name = $parent_name;
	$this->descr = $descr;
	$this->jap_tlans = $jap_tlans;
    }

    /**
     * [GET] MIBデータ取得
     * 
     * MIBデータをグループから取得します
     * @param integer	$from_id    取得出発地点を設定します（1..グループ, 2..サブツリー, 3..ノード）
     * @param integer	$to_id	    取得到達地点を設定します（1..グループ, 2..サブツリー, 3..ノード）
     * @return array|null 取得に成功した場合はarray、失敗した場合はnullとして返されます。<br>arrayの場合は、データ項目が[[0]->["COL01" => "VALUE01", ...], [1]->{Table_data}, ...]となります。
     */
    public static function getMIB($from_id, $to_id): array {
	$query_num = self::setMIBQueryNum($from_id, $to_id);
	if ($query_num != 999) {
	    $data = self::setMIBParam($query_num);
	    $select = select(false, $data[0], $data[1], $data[2]);
	    $st = '';
	    if ($select) {
		$st = getArray($data);
	    }
	    return $st;
	} else {
	    return [];
	}
    }

    private static function setMIBQueryNum($from_id, $to_id) {
	if ($to_id - $from_id == 0) {
	    switch ($from_id) {
		case 0: $query_num = 0;
		    break;     //グループのみ
		case 1: $query_num = 1;
		    break;     //サブツリーのみ
		case 2: $query_num = 2;
		    break;     //ノードのみ
	    }
	} else {
	    if ($to_id > $from_id) {
		if ($to_id - $from_id == 1) {
		    switch ($to_id) {
			case 1: $query_num = 3;
			    break;  //グループ -> サブツリー
			case 2: $query_num = 4;
			    break;  //サブツリー -> ノード
		    }
		} else {
		    $query_num = 5; //グループ -> ノード
		}
	    } else {
		$query_num = 999;
	    }
	}
    }


    private static function setMIBParam($query_num) {
	$query = '';
	switch ($query_num) {
	    case 0: //グループのみ
		$query = ['GSC_MIB_GROUP', 'GROUPID, GROUPOBJECTID, GROUPNAME', 'ORDER BY GROUPID'];
		break;
	    case 1: //サブツリーのみ
		$query = ['GSC_MIB_SUB', 'SUBID, SUBOBJECTID, SUBNAME, GROUPOBJECTID, UPDATETIME', 'ORDER BY SUBID'];
		break;
	    case 2: //ノードのみ
		$query = ['GSC_MIB_NODE', 'NODEID, NODEOBJECTID, SUBID, DESCR, JAPTLANS, ICON', 'ORDER BY NODEID'];
		break;
	    case 3: //グループ - サブツリー
		$query = ['GSC_MIB_GROUP a INNER JOIN GSC_MIB_SUB b ON a.GROUPID = b.GROUPID', 'GROUP BY a.GROUPID, a.GROUPNAME, b.SUBID, b.SUBOBJECTID, b.SUBNAME, b.UPDATETIME', 'a.GROUPID, a.GROUPNAME, b.SUBOBJECTID, b.SUBNAME, b.UPDATETIME'];
		break;
	    case 4: //サブツリー - ノード
		$query = ['GSC_MIB_SUB a INNER JOIN GSC_MIB_NODE b ON a.SUBID = b.SUBID', 'a.SUBID, a.SUBOBJECTID, a.SUBNAME, a.UPDATETIME, b.NODEID, b.NODEOBJECTID, b.DESCR, b.JAPTLANS, b.ICON', 'GROUP BY a.SUBID, a.SUBOBJECTID, a.SUBNAME, a.UPDATETIME, b.NODEID, b.NODEOBJECTID, b.DESCR, b.JAPTLANS, b.ICON'];
		break;
	    case 5: //グループ - ノード
		$query = ['GSC_MIB_GROUP a INNER JOIN GSC_MIB_SUB b ON a.GROUPID = b.GROUPID INNER JOIN GSC_MIB_NODE c ON b.SUBID = c.SUBID', 'a.GROUPID, a.GROUPNAME, b.SUBID, b.SUBOBJECTID, b.SUBNAME, b.UPDATETIME, c.NODEID, c.NODEOBJECTID, c.DESCR, c.JAPTLANS, c.ICON', 'GROUP BY a.GROUPID, a.GROUPNAME, b.SUBID, b.SUBOBJECTID, b.SUBNAME, b.UPDATETIME, c.NODEID, c.NODEOBJECTID, c.DESCR, c.JAPTLANS, c.ICON'];
		break;
	}
    }

}
