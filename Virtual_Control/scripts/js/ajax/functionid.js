/* global OPTION_BACK, ACCOUNT_CREATE, ACCOUNT_EDIT_USERID, ACCOUNT_EDIT_USERNAME, ACCOUNT_EDIT_PASSWORD ACCOUNT_EDIT_DELETE */

/**
 * [BACK] オプションに戻る
 * 
 * @type Number
 */
let OPTION_BACK = 0;

/**
 * [ACCOUNT] アカウント選択
 * 
 * @type Number
 */
let ACCOUNT_SELECT = 1;

/**
 * [ACCOUNT] 作成
 * 
 * @type Number
 */
let ACCOUNT_CREATE = 2;

/**
 * [ACCOUNT] 編集選択
 * 
 * @type Number
 */
let ACCOUNT_EDIT = 3;

/**
 * [ACCOUNT] 編集（ユーザID）
 * 
 * @type Number
 */
let ACCOUNT_EDIT_USERID = 4;

/**
 * [ACCOUNT] 編集（ユーザ名）
 * 
 * @type Number
 */
let ACCOUNT_EDIT_USERNAME = 5;

/**
 * [ACCOUNT] 編集（パスワード）
 * 
 * @type Number
 */
let ACCOUNT_EDIT_PASSWORD = 6;

/**
 * [ACCOUNT] 削除ファンクション
 * 
 * @type Number
 */
let ACCOUNT_DELETE = 7;

/**
 * [AGENT] 選択ファンクション
 * 
 * @type Number
 */
let AGENT_SELECT = 11;

/**
 * [AGENT] 作成ファンクション
 * 
 * @type Number
 */
let AGENT_CREATE = 12;

/**
 * [AGENT] 編集選択ファンクション
 * 
 * @type Number
 */
let AGENT_EDIT = 13;

/**
 * [AGENT] 編集（エージェントホスト）
 * 
 * @type Number
 */
let AGENT_EDIT_HOST = 14;

/**
 * [AGENT] 編集（コミュニティ名）
 * 
 * @type Number
 */
let AGENT_EDIT_COMMUNITY = 15;

/**
 * [AGENT] 編集（監視対象MIB）
 * 
 * @type Number
 */
let AGENT_EDIT_MIB = 16;

/**
 * [AGENT] 削除ファンクション
 * 
 * @type Number
 */
let AGENT_DELETE = 17;

/**
 * [MIB GROUP] 選択ファンクション（初期状態）
 * 
 * @type Number
 */
let MIB_GROUP_SELECT_INIT = 20;

/**
 * [MIB GROUP] 選択ファンクション
 * 
 * @type Number
 */
let MIB_GROUP_SELECT = 21;

/**
 * [MIB GROUP] 作成ファンクション
 * 
 * @type Number
 */
let MIB_GROUP_CREATE = 22;

/**
 * [MIB GROUP] 編集選択ファンクション
 * 
 * @type Number
 */
let MIB_GROUP_EDIT = 23;

/**
 * [MIB GROUP] 編集（OID）ファンクション
 * 
 * @type Number
 */
let MIB_GROUP_EDIT_OID = 24;

/**
 * [MIB GROUP] 編集（グループ名）ファンクション
 * 
 * @type Number
 */
let MIB_GROUP_EDIT_NAME = 25;

/**
 * [MIB GROUP] 削除ファンクション
 * 
 * @type Number
 */
let MIB_GROUP_DELETE = 26;

/**
 * [MIB SUB] 選択ファンクション（初期状態）
 * 
 * @type Number
 */
let MIB_SUB_SELECT_INIT = 30;

/**
 * [MIB SUB] 選択ファンクション
 * 
 * @type Number
 */
let MIB_SUB_SELECT = 31;

/**
 * [MIB SUB] 作成ファンクション
 * 
 * @type Number
 */
let MIB_SUB_CREATE = 32;

/**
 * [MIB SUB] 編集選択ファンクション
 * 
 * @type Number
 */
let MIB_SUB_EDIT = 33;

/**
 * [MIB SUB] 編集（OID）ファンクション
 * 
 * @type Number
 */
let MIB_SUB_EDIT_OID = 34;

/**
 * [MIB SUB] 編集（サブツリー名）ファンクション
 * 
 * @type Number
 */
let MIB_SUB_EDIT_NAME = 35;

/**
 * [MIB SUB] 削除ファンクション
 * 
 * @type Number
 */
let MIB_SUB_DELETE = 36;

/**
 * [MIB NODE] 編集（一覧表示）ファンクション（初期状態）
 * 
 * @type Number
 */
let MIB_NODE_EDIT_INIT = 40;

/**
 * [MIB NODE] 編集（一覧表示）ファンクション
 * 
 * @type Number
 */
let MIB_NODE_EDIT = 41;

/**
 * [MIB NODE] 編集（フォーム）ファンクション
 * 
 * @type Number
 */
let MIB_NODE_EDIT_FORM = 42;

/**
 * [MIB NODE] 編集（アイコン選択）ファンクション
 * 
 * @type Number
 */
let MIB_NODE_EDIT_ICON = 43;

/**
 * [ANALY] エージェントセレクト取得
 * 
 * @type Number
 */
let ANALY_GET_AGENT = 51;

/**
 * [ANALY] SNMPWALK
 * 
 * @type Number
 */
let ANALY_WALK = 52;

/**
 * [ANALY] サブツリー表示
 * 
 * @type Number
 */
let ANALY_GET_SUB = 53;

/**
 * [ANALY] 結果画面に戻る
 * @type Number
 */
let ANALY_BACK_RESULT = 54;

/**
 * [ANALY] 更新する
 * @type Number
 */
let ANALY_WALK_REFRESH = 55;

/**
 * [CLASS] 各設定ページファンクションID設定
 * 
 * ここでは、各関数のID設定について定義しております。
 * 
 * @author clearnb <clear.navy.blue.star@gmail.com>
 */
class functionID {

    /**
     * [FunctionID] コンストラクタ
     * 
     * @returns {functionID}
     */
    constructor() {
	this.functionid = 0;
    }

    /**
     * [SET] reset
     * 
     * @returns {void}
     */
    resetID() {
	this.functionid = 0;
    }

    /**
     * [SET] -> OPTION_BACK
     * 
     * @returns {void}
     */
    change_option_back() {
	this.functionid = OPTION_BACK;
    }

    /**
     * [SET] -> ACCOUNT_SELECT
     * 
     * @returns {void}
     */
    change_account_select() {
	this.functionid = ACCOUNT_SELECT;
    }

    /**
     * [SET] -> ACCOUNT_CREATE
     * 
     * @returns {void}
     */
    change_account_create() {
	this.functionid = ACCOUNT_CREATE;
    }

    /**
     * [SET] -> ACCOUNT_EDIT
     * 
     * @returns {void}
     */
    change_account_edit() {
	this.functionid = ACCOUNT_EDIT;
    }

    /**
     * [SET] -> ACCOUNT_EDIT_USERID
     * 
     * @returns {void}
     */
    change_account_edit_userid() {
	this.functionid = ACCOUNT_EDIT_USERID;
    }

    /**
     * [SET] -> ACCOUNT_EDIT_USERNAME
     * 
     * @returns {void}
     */
    change_account_edit_username() {
	this.functionid = ACCOUNT_EDIT_USERNAME;
    }

    /**
     * [SET] -> ACCOUNT_EDIT_PASSWORD
     * 
     * @returns {void}
     */
    change_account_edit_password() {
	this.functionid = ACCOUNT_EDIT_PASSWORD;
    }

    /**
     * [SET] -> ACCOUNT_DELETE
     * 
     * @returns {void}
     */
    change_account_delete() {
	this.functionid = ACCOUNT_DELETE;
    }

    /**
     * [SET] -> AGENT_SELECTs
     * 
     * @returns {void}
     */
    change_agent_select() {
	this.functionid = AGENT_SELECT;
    }

    /**
     * [SET] -> AGENT_CREATE
     * 
     * @returns {void}
     */
    change_agent_create() {
	this.functionid = AGENT_CREATE;
    }

    /**
     * [SET] -> AGENT_EDIT
     * 
     * @returns {void}
     */
    change_agent_edit() {
	this.functionid = AGENT_EDIT;
    }

    /**
     * [SET] -> AGENT_EDIT_HOST
     * 
     * @returns {void}
     */
    change_agent_edit_host() {
	this.functionid = AGENT_EDIT_HOST;
    }

    /**
     * [SET] -> AGENT_EDIT_COMMUNITY
     * 
     * @returns {void}
     */
    change_agent_edit_community() {
	this.functionid = AGENT_EDIT_COMMUNITY;
    }

    /**
     * [SET] -> AGENT_EDIT_MIB
     * 
     * @returns {void}
     */
    change_agent_edit_mib() {
	this.functionid = AGENT_EDIT_MIB;
    }
    /**
     * [SET] -> AGENT_DELETE
     * 
     * @returns {void}
     */
    change_agent_delete() {
	this.functionid = AGENT_DELETE;
    }

    /**
     * [SET] -> MIB_GROUP_SELECT_INIT
     * @returns {void}
     */
    change_mib_group_select_init() {
	this.functionid = MIB_GROUP_SELECT_INIT;
    }

    /**
     * [SET] -> MIB_GROUP_SELECT
     * @returns {void}
     */
    change_mib_group_select() {
	this.functionid = MIB_GROUP_SELECT;
    }

    /**
     * [SET] -> MIB_GROUP_CREATE
     * @returns {void}
     */
    change_mib_group_create() {
	this.functionid = MIB_GROUP_CREATE;
    }

    /**
     * [SET] -> MIB_GROUP_EDIT
     * @returns {void}
     */
    change_mib_group_edit() {
	this.functionid = MIB_GROUP_EDIT;
    }

    /**
     * [SET] -> MIB_GROUP_EDIT_OID
     * @returns {void}
     */
    change_mib_group_edit_oid() {
	this.functionid = MIB_GROUP_EDIT_OID;
    }

    /**
     * [SET] -> MIB_GROUP_EDIT_NAME
     * @returns {void}
     */
    change_mib_group_edit_name() {
	this.functionid = MIB_GROUP_EDIT_NAME;
    }

    /**
     * [SET] -> MIB_GROUP_DELETE
     * @returns {void}
     */
    change_mib_group_delete() {
	this.functionid = MIB_GROUP_DELETE;
    }

    /**
     * [SET] -> MIB_SUB_SELECT_INIT
     * @returns {void}
     */
    change_mib_sub_select_init() {
	this.functionid = MIB_SUB_SELECT_INIT;
    }

    /**
     * [SET] -> MIB_SUB_SELECT 
     * @returns {void}
     */
    change_mib_sub_select() {
	this.functionid = MIB_SUB_SELECT;
    }

    /**
     * [SET] -> MIB_SUB_CREATE
     * @returns {void}
     */
    change_mib_sub_create() {
	this.functionid = MIB_SUB_CREATE;
    }

    /**
     * [SET] -> MIB_SUB_EDIT
     * @returns {undefined}
     */
    change_mib_sub_edit() {
	this.functionid = MIB_SUB_EDIT;
    }

    /**
     * [SET] -> MIB_SUB_EDIT_OID
     * @returns {void}
     */
    change_mib_sub_edit_oid() {
	this.functionid = MIB_SUB_EDIT_OID;
    }

    /**
     * [SET] -> MIB_SUB_EDIT_NAME
     * @returns {void}
     */
    change_mib_sub_edit_name() {
	this.functionid = MIB_SUB_EDIT_NAME;
    }

    /**
     * [SET] -> MIB_NODE_EDIT_INIT
     * @returns {void}
     */
    change_mib_node_edit_init() {
	this.functionid = MIB_NODE_EDIT_INIT;
    }

    /**
     * [SET] -> MIB_NODE_EDIT
     * @returns {void}
     */
    change_mib_node_edit() {
	this.functionid = MIB_NODE_EDIT;
    }

    /**
     * [SET] -> MIB_NODE_EDIT_FORM
     * @returns {void}
     */
    change_mib_node_edit_form() {
	this.functionid = MIB_NODE_EDIT_FORM;
    }

    /**
     * [SET] -> MIB_NODE_EDIT_ICON
     * @returns {void}
     */
    change_mib_node_edit_icon() {
	this.functionid = MIB_NODE_EDIT_ICON;
    }

    /**
     * [SET] -> MIB_SUB_DELETE
     * @returns {void}
     */
    change_mib_sub_delete() {
	this.functionid = MIB_SUB_DELETE;
    }

    /**
     * [SET] -> ANALY_GET_AGENT
     * 
     * @returns {void}
     */
    change_analy_get_agent() {
	this.functionid = ANALY_GET_AGENT;
    }

    /**
     * [SET] -> ANALY_WALK
     * 
     * @returns {void}
     */
    change_analy_walk() {
	this.functionid = ANALY_WALK;
    }

    /**
     * [SET] -> ANALY_WALK
     * 
     * @returns {void}
     */
    change_analy_get_sub() {
	this.functionid = ANALY_GET_SUB;
    }

    /**
     * [SET] -> ANALY_BACK_RESULT
     * 
     * @returns {void}
     */
    change_analy_back_result() {
	this.functionid = ANALY_BACK_RESULT;
    }
    
    /**
     * [SET] -> ANALY_WALK_REFRESH
     * 
     * @returns {void}
     */
    change_analy_walk_refresh() {
	this.functionid = ANALY_WALK_REFRESH;
    }

    /**
     * [GET] FUNCTIONID
     * 
     * @type Number
     */
    get getFunctionID() {
	return this.functionid % 10;
    }

    /**
     * [GET] FUNCTIONID ROW
     * 
     * @type Number
     */
    get getFunctionIDRow() {
	return this.functionid;
    }
}