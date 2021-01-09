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

let MIB_SUB_CREATE = 21;
let MIB_SUB_EDIT_OIDS = 22;
let MIB_SUB_EDIT_NODE = 23;
let MIB_SUB_DELETE = 24;
let MIB_GROUP_CREATE = 25;
let MIB_GROUP_EDIT = 26;
let MIB_GROUP_DELETE = 27;
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
     * [GET] FUNCTIONID
     * 
     * @type Number
     */
    get getFunctionID() {
	return this.functionid % 10;
    }
}