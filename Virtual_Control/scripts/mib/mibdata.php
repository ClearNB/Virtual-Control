<?php

class MIBData {
    /**
     * @var integer $oid_type	(0..Group, 1..Subtree, 2..Node)
     * @var string  $oid	
     * @var string  $parent_oid	
     * @var string  $descr	
     * @var string  $jap_tlans	
     */
    private $oid_type;
    private $oid;
    private $parent_oid;
    private $sub_t;
    private $options;
    private $descr;
    private $jap_tlans;
    
    public function __construct($oid_type, $oid, $parent_oid, $descr, $jap_tlans) {
	$this->oid_type = $oid_type;
	$this->oid = $oid;
	$this->parent_oid = $parent_oid;
	$this->descr = $descr;
	$this->jap_tlans = $jap_tlans;
    }
    
    
}