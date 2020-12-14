<?php

class SNMPData {
    static private $oids = [];
    static private $set = [];
    static private $group = 0;
    private $oid;
    private $descr;
    private $japtlans;
    private $icon;
    private $value;
    private $index;
    private $check;

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

    public function setOID() {
	//OID部分を取り除く -> $r_oid
	$r_oid = preg_replace('/^.*[.]/', '', $this->oid);

	/*
	 * 【インデックス構成について】
	 * ^* ... グループを切断し、新たにテーブルを作成する
	 * * ... 取り出されるものはINDEXなので、それを表示する
	 */
	if (preg_match('/(\^|\*){1,2}$/', $r_oid)) {
	    //^, *部分を取り除く
	    $this->oid = preg_replace('/[.](\^|\*){1,}$/', '', $this->oid);
	    if ($r_oid == '^*') {
		self::$group += 1;
		$this->index = [];
		$this->value = 0;
	    }
	    $this->check = self::$group;
	}
	self::$set[$this->oid] = $this;
	array_push(self::$oids, $this->oid);
    }

    public static function setValue($oid, $data): bool {
	$res = self::data_search($oid);
	if ($res) {
	    $data = str_replace('"', '', str_replace('iso', '1', preg_replace('/^.*[:]\s/', '', $data)));
	    array_push(self::$set[$res]->value, $data);
	    $chk = self::$set[$res]->check;
	    if ($chk != 0) {
		$sub_v = preg_replace("/(" . $res . ")[.]/", '', $oid);
		$top_oid = preg_replace('/([.][0-9]{1,}){2}$/', '', $res);
		if (!in_array($sub_v, self::$set[$top_oid]->index)) {
		    array_push(self::$set[$top_oid]->index, $sub_v);
		}
	    }
	    return true;
	} else {
	    return false;
	}
    }

    public function getValue(): array {
	return ['OID' => $this->oid, 'DESCR' => $this->descr, 'JAPTLANS' => $this->japtlans, 'ICON' => $this->icon, 'CHECK' => $this->check, 'VALUE'=> $this->value, 'INDEX' => $this->index];
    }

    public static function getDataArray(): array {
	$result = ['OID' => self::$oids, 'DESCR' => [], 'JAPTLANS' => [], 'ICON' => [], 'VALUE' => [], 'CHECK' => [], 'INDEX' => []];
	foreach (self::$set as $snmp) {
	    $data = $snmp->getValue();
	    $oid = $data['OID'];
	    $result['DESCR'][$oid] = $data['DESCR'];
	    $result['JAPTLANS'][$oid] = $data['JAPTLANS'];
	    $result['ICON'][$oid] = $data['ICON'];
	    if(empty($data['VALUE'])) {
		$result['VALUE'][$oid] = ['<データなし>'];
	    } else {
		$result['VALUE'][$oid] = $data['VALUE'];
	    }
	    $result['CHECK'][$oid] = $data['CHECK'];
	    $result['INDEX'][$oid] = $data['INDEX'];
	}
	return $result;
    }

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
	if($r) {
	    if(self::$set[$r]->value == 0) {
		$r = false;
	    }
	}
	return $r;
    }

}
