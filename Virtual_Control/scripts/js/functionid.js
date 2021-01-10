/**
 * [ACCOUNT] 作成
 * 
 * @type Number
 */
let ACCOUNT_CREATE = 1;
/**
 * [ACCOUNT] 編集（ユーザID）
 * 
 * @type Number
 */
let ACCOUNT_EDIT_USERID = 2;
/**
 * [ACCOUNT] 編集（ユーザ名）
 * 
 * @type Number
 */
let ACCOUNT_EDIT_USERNAME = 3;
/**
 * [ACCOUNT] 編集（パスワード）
 * 
 * @type Number
 */
let ACCOUNT_EDIT_PASSWORD = 4;

/**
 * [ACCOUNT] 削除ファンクション
 * 
 * @type Number
 */
let ACCOUNT_DELETE = 5;

/**
 * [AGENT] 作成ファンクション
 * 
 * @type Number
 */
let AGENT_CREATE = 11;

/**
 * [AGENT] 編集（エージェントホスト）
 * 
 * @type Number
 */
let AGENT_EDIT_HOST = 12;

/**
 * [AGENT] 編集（コミュニティ名）
 * 
 * @type Number
 */
let AGENT_EDIT_COMMUNITY = 13;

/**
 * [AGENT] 編集（監視対象MIB）
 * 
 * @type Number
 */
let AGENT_EDIT_MIB = 14;

/**
 * [AGENT] 削除ファンクション
 * 
 * @type Number
 */
let AGENT_DELETE = 15;

/**
 * [MIB GROUP] 選択ファンクション
 * 
 * @type Number
 */
let MIB_GROUP_SELECT = 20;

/**
 * [MIB GROUP] 作成ファンクション
 * 
 * @type Number
 */
let MIB_GROUP_CREATE = 21;

/**
 * [MIB GROUP] 編集選択ファンクション
 * 
 * @type Number
 */
let MIB_GROUP_EDIT = 22;

/**
 * [MIB GROUP] 編集（OID）ファンクション
 * 
 * @type Number
 */
let MIB_GROUP_EDIT_OID = 23;

/**
 * [MIB GROUP] 編集（グループ名）ファンクション
 * 
 * @type Number
 */
let MIB_GROUP_EDIT_NAME = 24;

/**
 * [MIB GROUP] 削除ファンクション
 * 
 * @type Number
 */
let MIB_GROUP_DELETE = 25;

/**
 * [MIB SUB] 選択ファンクション
 * 
 * @type Number
 */
let MIB_SUB_SELECT = 30;

/**
 * [MIB SUB] 作成ファンクション
 * 
 * @type Number
 */
let MIB_SUB_CREATE = 31;

/**
 * [MIB SUB] 編集選択ファンクション
 * 
 * @type Number
 */
let MIB_SUB_EDIT = 32;

/**
 * [MIB SUB] 編集（OID）ファンクション
 * 
 * @type Number
 */
let MIB_SUB_EDIT_OID = 33;

/**
 * [MIB SUB] 編集（サブツリー名）ファンクション
 * 
 * @type Number
 */
let MIB_SUB_EDIT_NAME = 34;

/**
 * [MIB NODE] 編集（一覧表示）ファンクション
 * 
 * @type Number
 */
let MIB_NODE_EDIT = 40;

/**
 * [MIB NODE] 編集（フォーム）ファンクション
 * 
 * @type Number
 */
let MIB_NODE_EDIT_FORM = 41;

/**
 * [MIB NODE] 編集（アイコン選択）ファンクション
 * 
 * @type Number
 */
let MIB_NODE_EDIT_ICON = 42;

/**
 * [MIB SUB] 削除ファンクション
 * 
 * @type Number
 */
let MIB_SUB_DELETE = 35;

/**
 * [USER] 認証ファンクション
 * 
 * @type Number
 */
let USER_AUTH = 999;

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
     * [SET] -> ACCOUNT_CREATE
     * 
     * @returns {void}
     */
    change_account_create() {
	this.functionid = ACCOUNT_CREATE;
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
     * [SET] -> AGENT_CREATE
     * 
     * @returns {void}
     */
    change_agent_create() {
	this.functionid = AGENT_CREATE;
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
     * [SET] -> USER_AUTH
     * @returns {undefined}
     */
    change_user_auth() {
	this.functionid = USER_AUTH;
    }
    
    /**
     * [SET] -> MIB_GROUP_SELECT
     * @returns {undefined}
     */
    change_mib_group_select() {
	this.functionid = MIB_GROUP_SELECT;
    }
    
    /**
     * [SET] -> MIB_GROUP_CREATE
     * @returns {undefined}
     */
    change_mib_group_create() {
	this.functionid = MIB_GROUP_CREATE;
    }
    
    /**
     * [SET] -> MIB_GROUP_EDIT
     * @returns {undefined}
     */
    change_mib_group_edit() {
	this.functionid = MIB_GROUP_EDIT;
    }
    
    /**
     * [SET] -> MIB_GROUP_EDIT_OID
     * @returns {undefined}
     */
    change_mib_group_edit_oid() {
	this.functionid = MIB_GROUP_EDIT_OID;
    }
    
    /**
     * [SET] -> MIB_GROUP_EDIT_NAME
     * @returns {undefined}
     */
    change_mib_group_edit_name() {
	this.functionid = MIB_GROUP_EDIT_NAME;
    }
    
    /**
     * [SET] -> MIB_GROUP_DELETE
     * @returns {undefined}
     */
    change_mib_group_delete() {
	this.functionid = MIB_GROUP_DELETE;
    }
    
    /**
     * [SET] -> MIB_SUB_SELECT 
     * @returns {undefined}
     */
    change_mib_sub_select() {
	this.functionid = MIB_SUB_SELECT;
    }
    
    /**
     * [SET] -> MIB_SUB_CREATE
     * @returns {undefined}
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
     * @returns {undefined}
     */
    change_mib_sub_edit_oid() {
	this.functionid = MIB_SUB_EDIT_OID;
    }
    
    /**
     * [SET] -> MIB_SUB_EDIT_NAME
     * @returns {undefined}
     */
    change_mib_sub_edit_name() {
	this.functionid = MIB_SUB_EDIT_NAME;
    }
    
    /**
     * [SET] -> MIB_NODE_EDIT
     * @returns {undefined}
     */
    change_mib_node_edit() {
	this.functionid = MIB_NODE_EDIT;
    }
    
    /**
     * [SET] -> MIB_NODE_EDIT_FORM
     * @returns {undefined}
     */
    change_mib_node_edit_form() {
	this.functionid = MIB_NODE_EDIT_FORM;
    }
    
    /**
     * [SET] -> MIB_NODE_EDIT_ICON
     * @returns {undefined}
     */
    change_mib_node_edit_icon() {
	this.functionid = MIB_NODE_EDIT_ICON;
    }
    
    /**
     * [SET] -> MIB_SUB_DELETE
     * @returns {undefined}
     */
    change_mib_sub_delete() {
	this.functionid = MIB_SUB_DELETE;
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