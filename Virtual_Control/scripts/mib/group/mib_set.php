<?php

class MIBGroupSet {

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
	['CODE' => 3, 'DATA' => '入力チェックエラーです。<br>以下の入力したデータをご確認ください。'],
	['CODE' => 4],
	['CODE' => 5, 'DATA' => '認証エラーです。正しいパスワードを入力してください。'],
	['CODE' => 6],
    ];
    private $a_pass;
    private $funid;
    private $pre_groupid;
    private $group_oid;
    private $group_name;

    /**
     * [SET] CONSTRUCTOR
     * 
     * オブジェクトコンストラクタです
     * 
     * @param int $functionid ファンクションIDを指定します
     * @param string $a_pass 認証用のパスワードを指定します
     * @param int $pre_groupid グループID（選択時）を選択します
     * @param string $group_oid グループOIDを指定します
     * @param string $group_name グループ名を指定します
     */
    public function __construct($functionid, $a_pass, $pre_groupid, $group_oid, $group_name) {
	$this->funid = $functionid;
	$this->a_pass = $a_pass;
	$this->pre_groupid = $pre_groupid;
	$this->group_oid = $group_oid;
	$this->group_name = $group_name;
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
	if (!$this->pre_groupid && $this->group_oid && $this->group_name) {
	    $cfunid = 22;
	} else if ($this->pre_groupid && $this->group_oid) {
	    $cfunid = 24;
	} else if ($this->pre_groupid && $this->group_name) {
	    $cfunid = 25;
	} else if ($this->pre_groupid) {
	    $cfunid = 26;
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
	    case 22: $result_code = $this->create();
		break;
	    case 24: case 25: $result_code = $this->edit();
		break;
	    case 26: $result_code = $this->delete();
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
     * @return int (0..成功, 1..失敗, 2..)
     */
    private function create(): int {
	$res_code = 0;
	$chk = $this->check();
	if ($chk == 0) {
	    if (session_auth()) {
		$res = insert('VC_MIB_GROUP', ['GOID', 'GNAME'], [$this->group_oid, $this->group_name]);
		$res_code = ($res) ? 0 : 1;
	    } else {
		$res_code = 4;
	    }
	} else {
	    $res_code = $chk;
	}
	return $res_code;
    }

    private function edit(): int {
	$res_code = 0;
	$chk = $this->check();
	if ($chk == 0) {
	    if (session_auth()) {
		$query = $this->editQuery();
		$res_code = ($query) ? 0 : 1;
	    } else {
		$res_code = 4;
	    }
	} else {
	    $res_code = 3;
	}
	return $res_code;
    }

    private function delete(): int {
	$flag = true;
	if (session_auth()) {
	    $flag &= delete('VC_MIB_GROUP', 'WHERE GID = ' . $this->pre_groupid);
	    $sel1 = select(false, 'VC_MIB_SUB', 'SID', 'WHERE GID = ' . $this->pre_groupid);
	    $flag &= delete('VC_MIB_SUB', 'WHERE GID = ' . $this->pre_groupid);
	    if ($sel1 && $flag) {
		$sel1_arr = [];
		while ($var = $sel1->fetch_assoc()) {
		    array_push($sel1_arr, $var['SID']);
		}
		if (sizeof($sel1_arr) > 0) {
		    $flag &= delete('VC_AGENT_MIB', 'WHERE SID IN (' . implode(', ', $sel1_arr) . ')');
		}
	    }
	    $res_code = ($flag) ? 0 : 1;
	} else {
	    $res_code = 4;
	}
	return $res_code;
    }

    private function check() {
	$chk_text = '<ul class="black-view">[ERROR_LOG]</ul>';
	$chk = '';
	switch ($this->funid) {
	    case 22: //作成（グループOID・グループ名）
		$chk .= $this->check_groupoid();
		$chk .= $this->check_groupname();
		break;
	    case 24: //編集（グループOID）
		$chk .= $this->check_groupoid();
		break;
	    case 25: //編集（グループ名）
		$chk .= $this->check_groupname();
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
	    case 24:
		$flag &= update('VC_MIB_GROUP', 'GOID', $this->group_oid, 'WHERE GID = ' . $this->pre_groupid);
		break;
	    case 25:
		$flag &= update('VC_MIB_GROUP', 'GNAME', $this->group_name, 'WHERE GID = ' . $this->pre_groupid);
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
	$column_list = ['<li>対象グループ: [GROUP]</li>', '<li>グループOID: [OID]</li>', '<li>グループ名: [NAME]</li>', '<li>配下サブツリー数: [COUNT]</li>', '<li>[AGENT_INFO]</li>'];
	$columns = [];
	switch ($this->check_cfunid()) {
	    case 22: $func = str_replace('[FUNCTION]', 'MIBグループ作成', $func);
		$columns = [1, 2];
		break;
	    case 24: $func = str_replace('[FUNCTION]', 'MIBグループ編集（グループOID）', $func);
		$columns = [0, 1];
		break;
	    case 25: $func = str_replace('[FUNCTION]', 'MIBグループ編集（グループ名）', $func);
		$columns = [0, 2];
		break;
	    case 26: $func = str_replace('[FUNCTION]', 'MIBグループ削除', $func);
		$columns = [0, 3, 4];
		break;
	}
	$list_text .= $func;
	foreach ($columns as $col) {
	    if (preg_match('/\[COUNT\]/', $column_list[$col])) {
		$list_text .= str_replace('[COUNT]', $this->get_subcount(), $column_list[$col]);
	    } else if (preg_match('/\[AGENT_INFO\]/', $column_list[$col])) {
		$list_text .= str_replace('[AGENT_INFO]', $this->get_selectedagent(), $column_list[$col]);
	    } else if (preg_match('/\[GROUP\]/', $column_list[$col])) {
		$list_text .= str_replace('[GROUP]', $this->getPreGroupInfo(), $column_list[$col]);
	    } else {
		$list_text .= str_replace('[NAME]', $this->group_name, str_replace('[OID]', $this->group_oid, $column_list[$col]));
	    }
	}
	$list_text .= '</ul>';
	return $list_text;
    }

    private function get_selectedagent() {
	$res = '【エージェント選択】<p>なし</p>';
	$sel1 = select(false, 'VC_MIB_SUB', 'SID', 'WHERE GID = ' . $this->pre_groupid);
	if ($sel1) {
	    $sel1_arr = [];
	    while ($var = $sel1->fetch_assoc()) {
		array_push($sel1_arr, $var['SID']);
	    }
	    if (sizeof($sel1_arr) > 0) {
		$sel2 = select(false, 'VC_AGENT_MIB a INNER JOIN VC_AGENT b ON a.AGENTID = b.AGENTID', 'b.AGENTHOST, b.COMMUNITY', 'WHERE a.SID IN (' . implode(', ', $sel1_arr) . ') GROUP BY b.AGENTHOST, b.COMMUNITY');
		if ($sel2) {
		    $sel2_arr = getArray($sel2);
		    $res = '【エージェント選択】<ul class="black-view">';
		    foreach ($sel2_arr as $v) {
			$res .= '<li>【' . $v['AGENTHOST'] . '】' . $v['COMMUNITY'] . '</li>';
		    }
		    $res .= '</ul>';
		}
	    }
	}
	return $res;
    }

    private function get_subcount() {
	$pre_info = MIBGroupGet::get_session();
	$store = isset($pre_info['GROUP']['STORE']) ? $pre_info['GROUP']['STORE'] : '';
	return ($store) ? $store['GROUP_SUB_COUNT'] : '〈エラー〉';
    }

    public function getPreGroupInfo() {
	$pre_info = MIBGroupGet::get_session();
	$store = isset($pre_info['GROUP']['STORE']) ? $pre_info['GROUP']['STORE'] : '';
	return ($store) ? '【' . $store['GROUP_OID'] . '】' . $store['GROUP_NAME'] : '〈グループ情報取得失敗〉';
    }

    /**
     * [GET] グループOIDチェック
     * 
     * グループOIDについて調べます<br>
     * 【条件】0〜9の数字（桁数無制限）と記号（.）を用いて「1.3.6.1.2.1」のように区切り入力されていること かつ 総字255文字以内であること
     * 
     * @return string 違反していた場合はエラー文、そうでない場合はnullが返されます
     */
    private function check_groupoid(): string {
	$test = '';
	if (!preg_match('/^(([0-9]{1,})[.]){1,}[0-9]{1,}$/', $this->group_oid) || mb_strlen($this->group_oid, 'UTF-8') > 255) {
	    $test = '<li>グループOID記述ルールに違反しています。</li>';
	} else {
	    $sel = select(false, 'VC_MIB_GROUP', 'GOID');
	    if($sel) {
		$sel_arr = getArray($sel);
		$flag = false;
		foreach($sel_arr as $s) {
		    if(strpos($this->group_oid . '.', $s['GOID'] . '.') !== false) {
			$flag = true;
			break;
		    }
		}
		if($flag) {
		    $test = '<li>指定されたOIDはすでに存在するか、既存グループの配下におかれるべきOIDである可能性があります。</li>';
		}
	    }
	}
	return $test;
    }

    /**
     * [GET] グループ名チェック
     * 
     * グループ名について調べます<br>
     * 【条件】0〜9, a-z, A-Z, _, - のいずれかを利用しており、1〜50文字で指定していること
     * 
     * @return string 違反していた場合はエラー文、そうでない場合はnullが返されます
     */
    private function check_groupname(): string {
	$test = '';
	if (!preg_match('/^([0-9]|[a-z]|[A-Z]|[_-]){1,50}$/', $this->group_name)) {
	    $test = '<li>グループ名記述ルールに違反しています。</li>';
	}
	return $test;
    }
}
