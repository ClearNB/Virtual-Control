<?php

class MIBNodeSet {

    private $pre_group;
    private $pre_sub;
    private $pre_node;
    private $pre_icon;
    private $all_node;
    
    private $type;
    private $oid;
    private $sub;
    private $tableid;
    private $descr;
    private $japtlans;
    private $icon;

    private function generateOID() {
	return $this->pre_sub['OID'] . '.' . $this->oid . '.' . $this->sub . (($this->tableid) ? '.1.' . $this->tableid : '');
    }

    //OID, TYPE, SUB, TABLEID
    private function checkOID() {
	$test = '';
	$oid = $this->generateOID();
	foreach ($this->all_node as $k) {
	    if (preg_match('/^(' . $k['NODE_OID'] . '|' . $k['NODE_OID'] . '[.])$/', $oid)) {
		$test = '<li>指定されたOIDはすでに存在します: ' . $oid . '</li>';
		break;
	    }
	}
	return $test;
    }

    //DESCR, JAPTLANS
    private function checkName() {
	$test = '';
	//50, 50
	$desc_len = strlen(mb_convert_encoding($this->descr, 'SJIS', 'UTF-8'));
	$jap_len = strlen(mb_convert_encoding($this->japtlans, 'SJIS', 'UTF-8'));
	if($desc_len < 1 && $desc_len > 50) {
	    $test = '<li>項目名（英名）は半角1〜50文字で入力する必要があります。</li>';
	}
	if($jap_len < 1 && $jap_len > 50) {
	    $test .= '<li>項目名（日本語名）は半角1〜50文字（全角1〜25文字）で入力する必要があります。</li>';
	}
	return $test;
    }
    
    private function checkIcon() {
	$test = '';
	if(!isset($this->pre_icon[$this->icon])) {
	    $test = '<li>指定されたアイコンの情報がありません。</li>';
	}
	return $test;
    }
}
