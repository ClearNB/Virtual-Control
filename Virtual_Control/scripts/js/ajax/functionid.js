var functionid = 0;

/**
 * [SET] reset
 * 
 * @returns {void}
 */
function resetID() {
    functionid = 0;
}

/**
 * [SET] -> OPTION_BACK
 * 
 * @returns {void}
 */
function change_option_back() {
    functionid = 0;
}

/**
 * [SET] -> ACCOUNT_SELECT
 * 
 * @returns {void}
 */
function change_account_select() {
    functionid = 1;
}

/**
 * [SET] -> ACCOUNT_CREATE
 * 
 * @returns {void}
 */
function change_account_create() {
    functionid = 2;
}

/**
 * [SET] -> ACCOUNT_EDIT
 * 
 * @returns {void}
 */
function change_account_edit() {
    functionid = 3;
}

/**
 * [SET] -> ACCOUNT_EDIT_USERID
 * 
 * @returns {void}
 */
function change_account_edit_userid() {
    functionid = 4;
}

/**
 * [SET] -> ACCOUNT_EDIT_USERNAME
 * 
 * @returns {void}
 */
function change_account_edit_username() {
    functionid = 5;
}

/**
 * [SET] -> ACCOUNT_EDIT_PASSWORD
 * 
 * @returns {void}
 */
function change_account_edit_password() {
    functionid = 6;
}

/**
 * [SET] -> ACCOUNT_DELETE
 * 
 * @returns {void}
 */
function change_account_delete() {
    functionid = 7;
}

/**
 * [SET] -> AGENT_SELECTs
 * 
 * @returns {void}
 */
function change_agent_select() {
    functionid = 11;
}

/**
 * [SET] -> AGENT_CREATE
 * 
 * @returns {void}
 */
function change_agent_create() {
    functionid = 12;
}

/**
 * [SET] -> AGENT_EDIT
 * 
 * @returns {void}
 */
function change_agent_edit() {
    functionid = 13;
}

/**
 * [SET] -> AGENT_EDIT_HOST
 * 
 * @returns {void}
 */
function change_agent_edit_host() {
    functionid = 14;
}

/**
 * [SET] -> AGENT_EDIT_COMMUNITY
 * 
 * @returns {void}
 */
function change_agent_edit_community() {
    functionid = 15;
}

/**
 * [SET] -> AGENT_EDIT_MIB
 * 
 * @returns {void}
 */
function change_agent_edit_mib() {
    functionid = 16;
}
/**
 * [SET] -> AGENT_DELETE
 * 
 * @returns {void}
 */
function change_agent_delete() {
    functionid = 17;
}

/**
 * [SET] -> MIB_GROUP_SELECT_INIT
 * @returns {void}
 */
function change_mib_group_select_init() {
    functionid = 20;
}

/**
 * [SET] -> MIB_GROUP_SELECT
 * @returns {void}
 */
function change_mib_group_select() {
    functionid = 21;
}

/**
 * [SET] -> MIB_GROUP_CREATE
 * @returns {void}
 */
function change_mib_group_create() {
    functionid = 22;
}

/**
 * [SET] -> MIB_GROUP_EDIT
 * @returns {void}
 */
function change_mib_group_edit() {
    functionid = 23;
}

/**
 * [SET] -> MIB_GROUP_EDIT_OID
 * @returns {void}
 */
function change_mib_group_edit_oid() {
    functionid = 24;
}

/**
 * [SET] -> MIB_GROUP_EDIT_NAME
 * @returns {void}
 */
function change_mib_group_edit_name() {
    functionid = 25;
}

/**
 * [SET] -> MIB_GROUP_DELETE
 * @returns {void}
 */
function change_mib_group_delete() {
    functionid = 26;
}

/**
 * [SET] -> MIB_SUB_SELECT_INIT
 * @returns {void}
 */
function change_mib_sub_select_init() {
    functionid = 30;
}

/**
 * [SET] -> MIB_SUB_SELECT 
 * @returns {void}
 */
function change_mib_sub_select() {
    functionid = 31;
}

/**
 * [SET] -> MIB_SUB_CREATE
 * @returns {void}
 */
function change_mib_sub_create() {
    functionid = 32;
}

/**
 * [SET] -> MIB_SUB_EDIT
 * @returns {undefined}
 */
function change_mib_sub_edit() {
    functionid = 33;
}

/**
 * [SET] -> MIB_SUB_EDIT_OID
 * @returns {void}
 */
function change_mib_sub_edit_oid() {
    functionid = 34;
}

/**
 * [SET] -> MIB_SUB_EDIT_NAME
 * @returns {void}
 */
function change_mib_sub_edit_name() {
    functionid = 35;
}

/**
 * [SET] -> MIB_SUB_DELETE
 * @returns {void}
 */
function change_mib_sub_delete() {
    functionid = 36;
}

/**
 * [SET] -> MIB_NODE_EDIT_INIT
 * @returns {void}
 */
function change_mib_node_edit_init() {
    functionid = 40;
}

/**
 * [SET] -> MIB_NODE_EDIT
 * @returns {void}
 */
function change_mib_node_edit() {
    functionid = 41;
}

/**
 * [SET] -> MIB_NODE_EDIT_FORM
 * @returns {void}
 */
function change_mib_node_edit_form() {
    functionid = 42;
}

/**
 * [SET] -> MIB_NODE_EDIT_ICON
 * @returns {void}
 */
function change_mib_node_edit_icon() {
    functionid = 43;
}

/**
 * [SET] -> ANALY_GET_AGENT
 * 
 * @returns {void}
 */
function change_analy_get_agent() {
    functionid = 51;
}

/**
 * [SET] -> ANALY_WALK
 * 
 * @returns {void}
 */
function change_analy_walk() {
    functionid = 52;
}

/**
 * [SET] -> ANALY_WALK
 * 
 * @returns {void}
 */
function change_analy_get_sub() {
    functionid = 53;
}

/**
 * [SET] -> ANALY_BACK_RESULT
 * 
 * @returns {void}
 */
function change_analy_back_result() {
    functionid = 54;
}

/**
 * [SET] -> ANALY_WALK_REFRESH
 * 
 * @returns {void}
 */
function change_analy_walk_refresh() {
    functionid = 55;
}

/**
 * [SET] -> INDEX
 * 
 * @returns {void}
 */
function change_index() {
    functionid = 61;
}

/**
 * [SET] -> DASH
 * 
 * @returns {void}
 */
function change_dash() {
    functionid = 62;
}

/**
 * [SET] -> INIT
 * 
 * @returns {void}
 */
function change_init() {
    functionid = 63;
}

/**
 * [SET] -> OPTION
 * 
 * @returns {void}
 */
function change_option() {
    functionid = 64;
}

/**
 * [SET] -> LOGIN
 * 
 * @returns {void}
 */
function change_login() {
    functionid = 71;
}

/**
 * [SET] -> LOGIN_SUB
 * 
 * @returns {void}
 */
function change_login_sub() {
    functionid = 72;
}

/**
 * [SET] -> WARN_RESULT
 * 
 * @returns {void}
 */
function change_warn_result() {
    functionid = 81;
}

/**
 * [SET] -> WARN_SUB
 * 
 * @returns {void}
 */
function change_warn_sub() {
    functionid = 82;
}

/**
 * [SET] -> WARN_BACK
 * 
 * @returns {void}
 */
function change_warn_back() {
    functionid = 83;
}

/**
 * [GET] FUNCTIONID
 * 
 * @type Number
 */
function getFunctionID() {
    return functionid;
}