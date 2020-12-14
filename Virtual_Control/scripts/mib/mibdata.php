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
    private $descr;
    private $jap_tlans;
    
    public function __construct($oid_type, $oid, $subtree_oid, $descr, $jap_tlans) {
	
    }
}