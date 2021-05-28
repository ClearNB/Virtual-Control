<?php

class MIBSubSet {

    /**
     * [VAR] リザルトフォーム
     * 
     * [0] = 成功<br>
     * [1] = データベースエラー<br>
     * [2] = 手続きエラー（ファンクションと内容が違う）<br>
     * [3] = チェックエラー（入力チェック）<br>
     * [4] = 認証ハック<br>
     * [5] = 認証エラー<br>
     * [6] = 確認ハック
     * 
     * @var array
     */
    private $result_form = [
	['CODE' => 0],
	['CODE' => 1],
	['CODE' => 2],
	['CODE' => 3, 'DATA' => '<ul class="black-view"><li>入力チェックエラーです。以下の入力したデータをご確認ください。</li>'],
	['CODE' => 4],
	['CODE' => 5, 'DATA' => '<ul class="black-view"><li>認証エラーです。正しいパスワードを入力してください。</li></ul>'],
	['CODE' => 6],
    ];
    private $mibdata;
    private $a_pass;
    private $funid;
    private $pre_groupid;
    private $pre_subid;
    private $sub_oid;
    private $sub_name;

    /**
     * [SET] CONSTRUCTOR
     * 
     * オブジェクトコンストラクタです
     * 
     * @param int $functionid ファンクションIDを指定します
     * @param string $a_pass 認証用のパスワードを指定します
     * @param int $pre_subid サブツリーID（選択時）を指定します
     * @param int $pre_groupid グループID（選択時）を指定します
     * @param string $sub_oid サブツリーOIDを指定します
     * @param string $sub_name サブツリー名を指定します
     * @param array $mibdata MIBDataで取得したデータ（選択グループなどの選択情報が組み込まれているもの）を指定します
     */
    public function __construct($functionid, $a_pass, $pre_subid, $pre_groupid, $sub_oid, $sub_name, $mibdata) {
	$this->funid = $functionid;
	$this->a_pass = $a_pass;
	$this->pre_subid = $pre_subid;
	$this->pre_groupid = $pre_groupid;
	$this->sub_oid = $sub_oid;
	$this->sub_name = $sub_name;
	$this->mibdata = $mibdata;
	$this->check_functionid();
    }

    /**
     * [GET] ファンクションID調査
     * 
     * ファンクションIDの調査を行います<br>
     * AuthPassが認識された場合、999を指定します
     * 
     * @param int $functionid	実際のファンクションIDを指定します
     */
    public function check_functionid() {
	if (!session_auth() && $this->a_pass) {
	    $this->funid = 999;
	}
    }

    public function check_cfunid() {
	$cfunid = 0;
	if ($this->pre_groupid && !$this->pre_subid && $this->sub_oid && $this->sub_name) {
	    $cfunid = 32;
	} else if ($this->pre_groupid && $this->pre_subid && $this->sub_oid) {
	    $cfunid = 34;
	} else if ($this->pre_groupid && $this->pre_subid && $this->sub_name) {
	    $cfunid = 35;
	} else if ($this->pre_groupid && $this->pre_subid) {
	    $cfunid = 36;
	}
	return $cfunid;
    }

    /**
     * [GET] 実行ファンクション
     * 
     * 実行ファンクションを起こします。<br>
     * CODEについて<hr>
     * 0 = 正常終了<br>
     * 1 = 異常終了（原因あり）<br>
     * 2 = 認証が必要（認証を呼び出す）
     * 
     * @return array ["CODE" => .., "ERR_TEXT" => '..']
     */
    public function run() {
	$result_code = 0;
	switch ($this->funid) {
	    case 32: $result_code = $this->create();
		break;
	    case 34: case 35: $result_code = $this->edit();
		break;
	    case 36: $result_code = $this->delete();
		break;
	    case 999: $result_code = $this->auth();
		break;
	}
	return $this->result_form[$result_code];
    }

    /**
     * [GET] 作成
     * 
     * 作成のためのファンクションを行います
     * 
     * @return int (0..成功, 1..データベース失敗, 3..入力エラー, 4..認証)
     */
    private function create(): int {
	$chk = $this->check();
	$res_code = ($chk != 0) ? $chk : ((!session_auth()) ? 4 : 0);
	if ($res_code == 0) {
	    $res = insert('VC_MIB_SUB', ['GID', 'SOID', 'SNAME'], [$this->pre_groupid, $this->sub_oid, $this->sub_name]);
	    $res_code = ($res) ? 0 : 1;
	}
	return $res_code;
    }

    private function edit(): int {
	$chk = $this->check();
	$res_code = ($chk != 0) ? $chk : ((!session_auth()) ? 4 : 0);
	if ($res_code == 0) {
	    $query = $this->editQuery();
	    $res_code = ($query) ? 0 : 1;
	}
	return $res_code;
    }

    private function delete(): int {
	$res_code = (!session_auth()) ? 4 : 0;
	if ($res_code == 0) {
	    $flag = delete('VC_MIB_SUB', 'WHERE SID = ' . $this->pre_subid);
	    $flag &= delete('VC_AGENT_MIB', 'WHERE SID = ' . $this->pre_subid);
	    $res_code = ($flag) ? 0 : 1;
	}
	return $res_code;
    }

    private function check() {
	$chk_text = '[ERROR_LOG]</ul>';
	$chk = '';
	switch ($this->funid) {
	    case 32: //作成（サブツリーOID・サブツリー名）
		$chk .= $this->check_suboid() . $this->check_subname();
		break;
	    case 34: //編集（サブツリーOID）
		$chk .= $this->check_suboid();
		break;
	    case 35: //編集（サブツリー名）
		$chk .= $this->check_subname();
		break;
	}
	if ($chk) {
	    $chk_text = str_replace('[ERROR_LOG]', $chk, $chk_text);
	    $this->result_form[3]['DATA'] .= $chk_text;
	    return 3;
	} else {
	    return 0;
	}
    }

    private function auth() {
	$res = 0;
	$s_userid = session_get_userid();
	switch (session_auth_check($s_userid, $this->a_pass, true)) {
	    case 0:
		$res = 6;
		$this->result_form[$res]['DATA'] = $this->generateList();
		break;
	    case 1:
		$res = 1;
		break;
	    case 2:
		$res = 5;
		break;
	}
	return $res;
    }

    /**
     * [GET] 編集クエリ実行
     * 
     * 
     * @return bool 変更が反映された（クエリが正式に実行された）場合はtrue、そうでない場合はfalseを返します
     */
    private function editQuery() {
	$flag = true;
	switch ($this->funid) {
	    case 34:
		$flag &= update('VC_MIB_SUB', 'SOID', intval($this->sub_oid), 'WHERE SID = ' . $this->pre_subid);
		break;
	    case 35:
		$flag &= update('VC_MIB_SUB', 'SNAME', $this->sub_name, 'WHERE SID = ' . $this->pre_subid);
		break;
	}
	return $flag;
    }

    /**
     * [GET] 確認画面リスト作成
     * 
     * 作成・編集・削除時の確認画面リストを作成します
     * 
     * @return string 確認用HTMLを文字列で返します
     */
    private function generateList() {
	$list_text = '<ul class="black-view">';
	$func = "<li>ファンクション: [FUNCTION]</li>";
	$column_list = ['<li>対象サブツリー: [SUB]</li>', '<li>サブツリーOID: [OID]</li>', '<li>サブツリー名: [NAME]</li>', '<li>配下サブツリー数: [COUNT]</li>', '<li>[AGENT_INFO]</li>'];
	$columns = [];
	switch ($this->check_cfunid()) {
	    case 32: $func = str_replace('[FUNCTION]', 'MIBサブツリー作成', $func);
		$columns = [1, 2];
		break;
	    case 34: $func = str_replace('[FUNCTION]', 'MIBサブツリー編集（サブツリーOID）', $func);
		$columns = [0, 1];
		break;
	    case 35: $func = str_replace('[FUNCTION]', 'MIBサブツリー編集（サブツリー名）', $func);
		$columns = [0, 2];
		break;
	    case 36: $func = str_replace('[FUNCTION]', 'MIBサブツリー削除', $func);
		$columns = [0, 3, 4];
		break;
	}
	$list_text .= $func;
	foreach ($columns as $col) {
	    if (preg_match('/\[COUNT\]/', $column_list[$col])) {
		$list_text .= str_replace('[COUNT]', $this->get_nodecount(), $column_list[$col]);
	    } else if (preg_match('/\[AGENT_INFO\]/', $column_list[$col])) {
		$list_text .= str_replace('[AGENT_INFO]', $this->get_selectedagent(), $column_list[$col]);
	    } else if (preg_match('/\[SUB\]/', $column_list[$col])) {
		$list_text .= str_replace('[SUB]', $this->getPreSubInfo(), $column_list[$col]);
	    } else {
		$list_text .= str_replace('[NAME]', $this->sub_name, str_replace('[OID]', $this->getOID(), $column_list[$col]));
	    }
	}
	$list_text .= '</ul>';
	return $list_text;
    }

    private function get_selectedagent() {
	$res = '<h5 class="vc-title">エージェント選択</h5><ul class="black-view">';
	$sel1 = select(false, 'VC_AGENT_MIB a INNER JOIN VC_AGENT b ON a.AGENTID = b.AGENTID', 'b.AGENTHOST, b.COMMUNITY', 'WHERE a.SID = ' . $this->pre_subid);
	if ($sel1) {
	    $sel1_arr = getArray($sel1);
	    if (sizeof($sel1_arr)) {
		foreach ($sel1_arr as $v) {
		    $res .= '<li>【' . $v['AGENTHOST'] . '】' . $v['COMMUNITY'] . '</li>';
		}
	    } else {
		$res .= '<li>（なし）</li>';
	    }
	    $res .= '</ul>';
	}
	return $res;
    }

    private function getOID() {
	$group = isset($this->mibdata['GROUP']['STORE']) ? $this->mibdata['GROUP']['STORE'] : '';
	$res = ($group) ? $group['GROUP_OID'] . '.' . $this->sub_oid : '';
	return ($res) ? $res : '〈エラー〉';
    }

    private function get_nodecount() {
	$store = isset($this->mibdata['SUB']['STORE']) ? $this->mibdata['SUB']['STORE'] : '';
	return ($store) ? $store['SUB_NODE_COUNT'] : '〈エラー〉';
    }

    public function getPreSubInfo() {
	$sub = isset($this->mibdata['SUB']['STORE']) ? $this->mibdata['SUB']['STORE'] : '';
	$res = ($sub) ? '【' . $sub['SUB_OID'] . '】' . $sub['SUB_NAME'] : '';
	$group = isset($this->mibdata['GROUP']['STORE']) ? $this->mibdata['GROUP']['STORE'] : '';
	$res .= ($group) ? ' > 【' . $group['GROUP_OID'] . '】' . $group['GROUP_NAME'] : '';
	return ($res) ? $res : '〈エラー〉';
    }

    /**
     * [GET] サブツリーOIDチェック
     * 
     * サブツリーOIDについて調べます<br>
     * 【条件】0〜9の数字（桁数無制限）であること, かつ最大値（2147483647）より超えていないこと, すでにサブツリーデータが作成されていないかどうか
     * 
     * @return string 違反していた場合はエラー文、そうでない場合はnullが返されます
     */
    private function check_suboid(): string {
	$test = '';
	if (!preg_match('/^[0-9]{1,}$/', $this->sub_oid) && strcmp($this->sub_oid, '2147483647') > 0) {
	    $test = '<li>サブツリーOIDでは数値入力のみが求められています。</li>';
	} else {
	    $group = isset($this->mibdata['GROUP']['STORE']) ? $this->mibdata['GROUP']['STORE'] : '';
	    $oid = ($group) ? $group['GROUP_OID'] . '.' . $this->sub_oid : '';
	    $sub_list = isset($this->mibdata['SUB']['PARENT']) ? $this->mibdata['SUB']['PARENT'] : '';
	    if ($oid && $sub_list) {
		$flag = false;
		foreach ($sub_list as $s) {
		    if ($oid == $s['SUB_OID']) {
			$flag = true;
			break;
		    }
		}
		if ($flag) {
		    $test = '<li>指定されたサブツリーOIDはすでに存在します: ' . $oid . '</li>';
		}
	    } else {
		if (!is_array($sub_list)) {
		    $test = '<li>グループOIDの取得・サブツリー情報の取得に失敗しました。セッション切れの可能性があります。</li>';
		}
	    }
	}
	return $test;
    }

    /**
     * [GET] サブツリー名チェック
     * 
     * サブツリー名について調べます<br>
     * 【条件】0〜9, a-z, A-Z, _, - のいずれかを利用しており、1〜50文字で指定していること
     * 
     * @return string 違反していた場合はエラー文、そうでない場合はnullが返されます
     */
    private function check_subname(): string {
	$test = '';
	if (!preg_match('/^([0-9]|[a-z]|[A-Z]|[_-]){1,50}$/', $this->sub_name)) {
	    $test = '<li>サブツリー名記述ルールに違反しています。</li>';
	}
	return $test;
    }

}
