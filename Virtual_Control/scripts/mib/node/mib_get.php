<?php

//GROUP SET

class MIBNodeGet extends MIBGetClass {
    private $pre_groupid;
    private $pre_subid;
    private $node_id;
    private $node_oid;
    private $node_oid_sub;
    private $node_oid_table_id;
    private $node_descr;
    private $node_japtlans;
    private $node_type;
    private $node_option;
    private $node_iconid;
    private $auth_pass;
    
    public function __construct($request_code, $request_data_code) {
	parent::__construct($request_code, $request_data_code);
	if($this->is_set(0, 1)) { //SUB
	    $this->pre_groupid = $this->get_typesdata(0, 1, 'GROUP_ID');
	}
	if($this->is_set(1, 1)) { //SUB
	    $this->pre_subid = $this->get_typesdata(1, 1, 'SUB_ID');
	}
	if($this->is_set(2, 1)) { //SELECT (FOR EDIT)
	    
	}
	if($this->is_set(2, 0)) { //SELECT (FOR INPUT)
	    
	}
	$this->node_oid = post_get_data('in_nd_id');
	$this->node_oid_sub = post_get_data('in_nd_id_sb');
	$this->node_oid_table_id = post_get_data('in_nd_id_tb');
	$this->node_type = post_get_data('id_nd_tp');
	$this->node_descr = post_get_data('in_nd_ds');
	$this->node_japtlans = post_get_data('in_nd_jp');
	$this->node_option = post_get_data('in_nd_op');
	$this->node_iconid = post_get_data('in_nd_ic');
	$this->auth_pass = post_get_data('in_at_ps');
    }
    
    public function run() {
	
    }
}