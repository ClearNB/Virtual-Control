<?php

class MIBData {

    /**
     * @var integer $oid_type	    (0..Group, 1..Subtree, 2..Node)
     * @var string  $oid	    (Ex: 1.3.6.1.2.1.1) without INDEX OPTION
     * @var string  $parent_oid	    (Ex: 1.3.6.1.2.1.1.1 -> 1.3.6.1.2.1.1)
     * @var string  $parent_name    Name for Parent
     * @var string  $descr	    Description for OID, the Language by RFC 1394
     * @var string  $jap_tlans	    Description for OID, the Language is Japanese. (translated by Project GSC)
     */
    private $oid_type;
    private $oid;
    private $parent_oid;
    private $parent_name;
    private $descr;
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
     * MIBデータをグループから取得します
     * @param integer	$from_id    取得出発地点を設定します（1..グループ, 2..サブツリー, 3..ノード）
     * @param integer	$to_id	    取得到達地点を設定します（0..グループ, 1..サブツリー, 2..ノード）
     */
    public static function getMIB($from_id, $to_id) {
	$query_num = self::setMIBQueryNum($from_id, $to_id);
	$data = self::setMIBParam($query_num);
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
	switch ($query_num) {
	    case 0:
		$query = ['GSC_MIB_GROUP', 'MIBGROUPOBJECTID, MIBGROUPNAME', ''];
		break;
	    case 1:
		$query = ['GSC_MIB_SUB', 'SUBOBJECTID, MIBNAME, MIBGROUPOBJECTID, MIBUPDATE', ''];
		break;
	    case 2:
		$query = ['GSC_MIB_NODE', 'NODEID, OBJECTID, SUBOBJECTID, DESCR, JAPTLANS, ICON', ''];
		break;
	    case 3:
		$query = ['GSC_MIB_GROUP a INNER JOIN GSC_MIB_SUB b ON a.MIBGROUPOBECTID = b.MIBGROUPOBECTID', 'a.MIBGROUPOBJECTID, b.MIBGROUPNAME, a.SUBOBJECTID, a.MIBNAME, a.MIBUPDATE', 'GROUP BY a.MIBGROUPOBJECTID, b.MIBGROUPNAME, a.SUBOBJECTID, a.MIBNAME, a.MIBUPDATE'];
		break;
	    case 4:
		$query = [''];
		break;
	    case 5:
		$query = ['GSC_MIB_GROUP a INNER JOIN GSC_MIB_SUB b ON a.MIBGROUPOBECTID = b.MIBGROUPOBECTID INNER JOIN GSC_MIB_NODE c ON b.SUBOBJECTID = c.SUBOBJECTID'];
		break;
	}
    }

}
