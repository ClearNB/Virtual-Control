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
	$this->setData();
    }

    private function setData() {
	if ($this->is_set(0, 1)) { //GROUP
	    $this->pre_groupid = $this->get_typesdata(0, 1, 'GROUP_ID');
	}
	if ($this->is_set(1, 1)) { //SUB
	    $this->pre_subid = $this->get_typesdata(1, 1, 'SUB_ID');
	}
	if ($this->is_set(2, 1)) { //SELECT (EDIT, DELETE)
	    $this->node_id = $this->get_typesdata(2, 1, 'NODE_ID');
	}
	if ($this->is_set(2, 0)) { //SELECT (FOR INPUT)
	    $this->node_oid = $this->get_typesdata(2, 0, 'NODE_OID');
	    $this->node_oid_sub = $this->get_typesdata(2, 0, 'NODE_OID_SUB');
	    $this->node_oid_table_id = $this->get_typesdata(2, 0, 'NODE_OID_TABLEID');
	    $this->node_type = $this->get_typesdata(2, 0, 'NODE_TYPE');
	    $this->node_descr = $this->get_typesdata(2, 0, 'NODE_DESCR');
	    $this->node_japtlans = $this->get_typesdata(2, 0, 'NODE_JAPTLANS');
	    $this->node_iconid = $this->get_typesdata(2, 0, 'NODE_ICONID');
	}
	$this->node_oid = post_get_data_convert('in_nd_id', $this->node_oid);
	$this->node_oid_sub = post_get_data_convert('in_nd_id_sb', $this->node_oid_sub);
	$this->node_oid_table_id = post_get_data_convert('in_nd_id_tb', $this->node_oid_table_id);
	$this->node_type = post_get_data_convert('in_nd_tp', $this->node_oid);
	$this->node_descr = post_get_data_convert('in_nd_ds', $this->node_descr);
	$this->node_japtlans = post_get_data_convert('in_nd_jp', $this->node_japtlans);
	$this->node_option = post_get_data_convert('in_nd_op', $this->node_option);
	$this->node_iconid = post_get_data_convert('in_nd_ic', $this->node_iconid);
	$this->auth_pass = post_get_data_convert('in_at_ps', $this->auth_pass);
    }

    public function run() {
	$data = '';
	$code = 1;
	switch ($this->request_code) {
	    case 40: //NODE_LIST (INIT)
		if (!$this->is_set(1, 1) && !$this->pre_subid && $this->request_data_code) {
		    $this->pre_subid = $this->request_data_code;
		}
	    case 41: //NODE_LIST
		$this->create_session();
		$this->set_data(0, 1, $this->session_data['GROUP'][$this->pre_groupid]);
		$this->set_data(1, 1, $this->session_data['SUB'][$this->pre_groupid][$this->pre_subid]);
		$this->set_data(2, 2, isset($this->session_data['NODE'][$this->pre_subid]) ? $this->session_data['NODE'][$this->pre_subid] : []);
		
		$this->reset_types(2, 0);
		$this->reset_types(2, 1);
		$this->reset_authid();
		$code = 30;
		break;
	    case 42: //NODE_LIST (NOT RESET)
		$this->reset_types(2, 0);
		$this->reset_types(2, 1);
		$this->reset_authid();
		$code = 30;
		break;
	    case 43: //NODE_EDIT_SELECT
		break;
	    case 44:
		break;
	    case 45: //NODE_EDIT_
		break;
	}
	if (!$data) {
	    $data = $this->session_data;
	}
	return ['CODE' => $code, 'DATA' => $data];
    }

}
