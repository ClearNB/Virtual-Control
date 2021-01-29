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
	['CODE' => 3, 'DATA' => '入力チェックエラーです。<br>以下の入力したデータをご確認ください。'],
	['CODE' => 4],
	['CODE' => 5, 'DATA' => '認証エラーです。正しいパスワードを入力してください。'],
	['CODE' => 6],
    ];
    private $a_pass;
    private $funid;
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
     * @param int $pre_subid グループID（選択時）を選択します
     * @param string $sub_oid グループOIDを指定します
     * @param string $sub_name グループ名を指定します
     */
    public function __construct($functionid, $a_pass, $pre_subid, $sub_oid, $sub_name) {
	$this->funid = $functionid;
	$this->a_pass = $a_pass;
	$this->pre_subid = $pre_subid;
	$this->sub_oid = $sub_oid;
	$this->sub_name = $sub_name;
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
     * @return int (0..成功, 1..失敗, 2..)
     */
    private function create(): int {
	$res_code = 0;
	$chk = $this->check();
	if ($chk == 0) {
	    if (session_auth()) {
		$res = insert('GSC_MIB_GROUP', ['GOID', 'GNAME'], [$this->group_oid, $this->group_name]);
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
	    $flag &= delete('GSC_MIB_GROUP', 'WHERE GID = ' . $this->pre_groupid);
	    $sel1 = select(false, 'GSC_MIB_SUB', 'SID', 'WHERE GID = ' . $this->pre_groupid);
	    if($sel1) {
		$sel1_arr = [];
		while($var = $sel1->fetch_assoc()) {
		    array_push($sel1_arr, $var['SID']);
		}
		$flag &= delete('GSC_AGENT_MIB', 'WHERE SID IN (' . implode(', ', $sel1_arr) . ')');
	    }
	    $flag &= delete('GSC_MIB_SUB', 'WHERE GID = ' . $this->pre_groupid);
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
	    $this->result_form[3]['ERR_TEXT'] .= $chk_text;
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
		$flag &= update('GSC_MIB_GROUP', 'GOID', $this->group_oid, 'WHERE GID = ' . $this->pre_groupid);
		break;
	    case 25:
		$flag &= update('GSC_MIB_GROUP', 'GNAME', $this->group_name, 'WHERE GID = ' . $this->pre_groupid);
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
	$column_list = ["<li>対象グループ: [AGENT_INFO]</li>", "<li>グループOID: [HOST]</li>", "<li>コミュニティ名: [COMMUNITY]</li>", "<li>監視対象MIB: [MIBS_NAMES]</li>"];
	$columns = [];
	switch ($this->check_correct_functionid()) {
	    case 22: $func = str_replace('[FUNCTION]', 'MIBグループ作成', $func);
		$columns = [1, 2, 3];
		break;
	    case 24: $func = str_replace('[FUNCTION]', 'MIBグループ編集（グループOID）', $func);
		$columns = [0, 1];
		break;
	    case 25: $func = str_replace('[FUNCTION]', 'MIBグループ編集（グループ名）', $func);
		$columns = [0, 2];
		break;
	    case 26: $func = str_replace('[FUNCTION]', 'MIBグループ削除', $func);
		$columns = [0];
		break;
	}
	$list_text .= $func;
	foreach ($columns as $col) {
	    $text = $column_list[$col];
	    switch ($col) {
		case 0: $text = str_replace('[HOST]', $this->agenthost, str_replace('[COMMUNITY]', $this->community, $text));
		    break;
		case 1: $text = str_replace('[HOST]', $this->agenthost, $text);
		    break;
		case 2: $text = str_replace('[COMMUNITY]', $this->community, $text);
		    break;
		case 3: $text = str_replace('[MIBS_NAMES]', $this->get_mib_text(), $text);
		    break;
	    }
	    $list_text .= $text;
	}
	$list_text .= '</ul>';
	return $list_text;
    }

    /**
     * [GET] グループOIDチェック
     * 
     * グループOIDについて調べます<br>
     * 【条件】0〜9の数字（桁数無制限）であること
     * 
     * @return string 違反していた場合はエラー文、そうでない場合はnullが返されます
     */
    private function check_suboid(): string {
	$test = '';
	if (is_integer($this->sub_oid)) {
	    $test = '<li>グループOID記述ルールに違反しています。</li>';
	}
	return $test;
    }

    /**
     * [GET] グループ名チェック
     * 
     * グループ名について調べます<br>
     * 【条件】0〜9, a-z, A-Zのいずれかを利用しており、1〜50文字で指定していること
     * 
     * @return string 違反していた場合はエラー文、そうでない場合はnullが返されます
     */
    private function check_subname(): string {
	$test = '';
	if (!preg_match('/^([0-9]|[a-z]|[A-Z]){1,50}$/', $this->subname)) {
	    $test = '<li>グループOID記述ルールに違反しています。</li>';
	}
	return $test;
    }

}
