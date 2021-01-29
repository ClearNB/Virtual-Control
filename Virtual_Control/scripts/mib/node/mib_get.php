<?php

//GROUP SET

class MIBNodeGet extends MIBGetClass {
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