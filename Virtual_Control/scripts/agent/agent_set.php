<?php

class AgentSet {

    /**
     * [VAR] リザルトフォーム
     * 
     * [0] = 成功<br>
     * [1] = データベースエラー<br>
     * [2] = 手続きエラー（ファンクションと内容が違う）<br>
     * [3] = チェックエラー（入力チェック）<br>
     * [4] = 認証エラー<br>
     * [5] = ログインエラー（編集・削除）<br>
     * [6] = 認証ハック<br>
     * [7] = 認証エラー<br>
     * [8] = 確認ハック
     * 
     * @var array
     */
    private $result_form = [
	['CODE' => 0],
	['CODE' => 1],
	['CODE' => 2, 'ERR_TEXT' => '<ul class="black-view"><li>手続きに失敗しました。</li><li>手続き上で正しく入力してください。システム上の正しい動作のため、UI上の操作以外での通信は拒否されます。</li></ul>'],
	['CODE' => 2, 'ERR_TEXT' => '<ul class="black-view"><li>入力チェックエラーです。以下の入力したデータをご確認ください。</li>'],
	['CODE' => 1, 'ERR_TEXT' => '認証情報に異常が見つかりました。<br>VCServer権限のみ実行可能な処理のため、認証情報が異なるユーザでは処理することができません。'],
	['CODE' => 3],
	['CODE' => 4],
	['CODE' => 5],
	['CODE' => 6, 'CONFIRM_DATA' => ''],
    ];
    private $agenthost;
    private $pre_agentid;
    private $community;
    private $mibs;
    private $a_pass;
    private $funid;

    /**
     * [CONST] コンストラクタ
     * 
     * 実際の値を格納します（）
     * 
     * @param string $functionid ファンクションID（実際格納用）
     * @param string $a_pass 認証用パスワード
     * @param string $agenthost エージェントホスト
     * @param string $pre_agentid 対象のエージェントID（編集）
     * @param string $community コミュニティ
     * @param string $mibs 対象MIB
     */
    public function __construct($functionid, $a_pass, $agenthost, $pre_agentid, $community, $mibs) {
	$this->agenthost = $agenthost;
	$this->pre_agentid = $pre_agentid;
	$this->community = $community;
	$this->mibs = $mibs;
	$this->a_pass = $a_pass;
	$this->funid = $functionid;
    }

    /**
     * [GET] ファンクションID調査
     * 
     * ファンクションIDの調査を行います。<br>
     * 実際送られたデータと、送付されているファンクションIDが正しいか、元のデータをたどって確認します。<br>
     * [返却値について]<hr>
     * 1. 作成
     * 2. 変更（ユーザID）
     * 3. 変更（ユーザ名）
     * 4. 変更（パスワード）
     * 5. 削除
     * 7. 手続きエラー
     * 
     * @param int $functionid	実際のファンクションIDです
     */
    public function check_functionid() {
	if (!session_auth() && $this->a_pass) {
	    $set_fun = 999;
	} else {
	    $set_fun = $this->check_correct_functionid();
	    if ($set_fun != $this->funid) {
		$set_fun = 18;
	    }
	}
	$this->funid = $set_fun;
    }

    public function check_correct_functionid() {
	$set_fun = 0;
	if ($this->agenthost && $this->community && $this->mibs) {
	    $set_fun = 12;
	} else if ($this->pre_agentid && $this->agenthost) {
	    $set_fun = 14;
	} else if ($this->pre_agentid && $this->community) {
	    $set_fun = 15;
	} else if ($this->pre_agentid && $this->mibs) {
	    $set_fun = 16;
	} else if ($this->pre_agentid) {
	    $set_fun = 17;
	}
	return $set_fun;
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
	    case 12:
		$result_code = $this->create();
		break;
	    case 14:
	    case 15:
	    case 16:
		$result_code = $this->edit();
		break;
	    case 17:
		$result_code = $this->delete();
		break;
	    case 18:
		$result_code = 2;
		break;
	    case 999:
		$result_code = $this->auth();
		break;
	}
	return $this->result_form[$result_code];
    }

    /**
     * エージェントを作成します（作成フラグは以下参照）
     * @return int (0..完了, 6..アカウント認証が必要, 2..セッション切れにより更新中止, 1..データベース障害が発生)
     */
    private function create(): int {
	$res_code = 0;
	//1: 全ての値に関してチェックを行う
	$chk = $this->check();
	if ($chk != 0) {
	    $res_code = $chk;
	} else {
	    //2: authidを確認する
	    if (session_auth()) {
		$flag = true;

		//3: INSERT文の実行 (VC_AGENT)
		$res = insert("VC_AGENT", ["AGENTHOST", "COMMUNITY"], [$this->agenthost, $this->community]);
		$sel = select(true, "VC_AGENT", "AGENTID", "WHERE AGENTHOST = '$this->agenthost' AND COMMUNITY = '$this->community'");
		if ($res && $sel) {
		    //AgentIDの取得（発行時）
		    $agentid = $sel['AGENTID'];
		    //4: INSERT文の実行（VC_AGENT_MIB）
		    foreach ($this->mibs as $m) {
			$res3 = insert("VC_AGENT_MIB", ["AGENTID", "SID"], [$agentid, $m]);
			$flag &= $res3;
			if (!$flag) {
			    break;
			}
		    }
		} else {
		    $flag = false;
		}

		if ($flag) {
		    $res_code = 0;
		} else {
		    $res_code = 1;
		}
	    } else {
		$res_code = 6;
	    }
	}
	return $res_code;
    }

    private function edit(): int {
	$res_code = 0;
	$chk = $this->check();
	if ($chk != 0) {
	    $res_code = 3;
	}
	if ($res_code == 0) {
	    if (session_auth()) {
		$query = $this->editQuery();
		if ($query) {
		    $res_code = 0;
		} else {
		    $res_code = 1;
		}
	    } else {
		$res_code = 6;
	    }
	}
	return $res_code;
    }

    private function delete(): int {
	if (session_auth()) {
	    $res1 = delete("VC_AGENT_MIB", "WHERE AGENTID = $this->pre_agentid");
	    $res2 = delete("VC_AGENT", "WHERE AGENTID = $this->pre_agentid");
	    if ($res1 && $res2) {
		$res_code = 0;
	    } else {
		$res_code = 1;
	    }
	} else {
	    $res_code = 6;
	}
	return $res_code;
    }

    private function check() {
	$chk_text = '[ERROR_LOG]</ul>';
	$chk = '';
	switch ($this->funid) {
	    case 12: //作成（ホスト・コミュニティ・MIB・重複確認）
		$chk .= check_host($this->agenthost);
		$chk .= check_community($this->community);
		$chk .= check_mib($this->mibs);
		$chk .= check_duplicate($this->agenthost, $this->community);
		break;
	    case 14: //編集1（ホスト・重複確認）
		$chk .= check_host($this->agenthost);
		$chk .= check_duplicate_onchange(0, $this->pre_agentid, $this->agenthost);
		break;
	    case 15: //編集2（コミュニティ・重複確認）
		$chk .= check_community($this->community);
		$chk .= check_duplicate_onchange(1, $this->pre_agentid, $this->community);
		break;
	    case 16: //編集3（MIB確認）
		$chk .= check_mib($this->mibs);
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
		$res = 8;
		$this->result_form[$res]['CONFIRM_DATA'] = $this->generateList();
		break;
	    case 1:
		$res = 1;
		break;
	    case 2:
		$res = 7;
		break;
	}
	return $res;
    }

    private function editQuery() {
	$flag = true;
	switch ($this->funid) {
	    case 14:
		$flag &= update("VC_AGENT", "AGENTHOST", $this->agenthost, "WHERE AGENTID = $this->pre_agentid");
		break;
	    case 15:
		$flag &= update("VC_AGENT", "COMMUNITY", $this->community, "WHERE AGENTID = $this->pre_agentid");
		break;
	    case 16:
		$flag &= delete("VC_AGENT_MIB", "WHERE AGENTID = $this->pre_agentid");
		foreach ($this->mibs as $m) {
		    $flag &= insert("VC_AGENT_MIB", ["AGENTID", "SID"], [$this->pre_agentid, $m]);
		    if (!$flag) {
			break;
		    }
		}
		break;
	}
	$flag &= update("VC_AGENT", "AGENTUPTIME", date("Y-m-d H:i:s"), "WHERE AGENTID = $this->pre_agentid");
	return $flag;
    }

    private function generateList() {
	$list_text = '<ul class="black-view">';
	$func = "<li>ファンクション: [FUNCTION]</li>";
	$column_list = ["<li>対象のエージェント: [AGENT_INFO]</li>", "<li>エージェントホスト: [HOST]</li>", "<li>コミュニティ名: [COMMUNITY]</li>", "<li>監視対象MIB: [MIBS_NAMES]</li>"];
	$columns = [];
	switch ($this->check_correct_functionid()) {
	    case 12: $func = str_replace('[FUNCTION]', 'エージェント作成', $func);
		$columns = [1, 2, 3];
		break;
	    case 14: $func = str_replace('[FUNCTION]', 'エージェント編集（エージェントホスト）', $func);
		$columns = [0, 1];
		break;
	    case 15: $func = str_replace('[FUNCTION]', 'エージェント編集（コミュニティ名）', $func);
		$columns = [0, 2];
		break;
	    case 16: $func = str_replace('[FUNCTION]', 'エージェント編集（監視対象MIB）', $func);
		$columns = [0, 3];
		break;
	    case 17: $func = str_replace('[FUNCTION]', 'エージェント削除', $func);
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

    private function get_mib_text(): string {
	$res = [''];

	$mb = new MIBData();
	$data = $mb->getMIB(0, 0, $this->mibs);
	if ($data) {
	    foreach ($data['SUB'] as $k => $g) {
		array_push($res, '【' . $data['GROUP'][$k]['GROUP_OID'] . '】' . $data['GROUP'][$k]['GROUP_NAME']);
		foreach ($g as $s) {
		    array_push($res, '▶ (' . $s['SUB_OID'] . ') ' . $s['SUB_NAME']);
		}
	    }
	} else {
	    $res = '（取得不可）';
	}
	return implode('<br>', $res);
    }

}

/**
 * [FUNCTION] ホストアドレス確認
 * 
 * ホストアドレスの記述について確認します。<br>
 * 【判定条件】ホストアドレスがドメイン名・IPアドレス（IPv4、IPv6のいずれかで正しい表記になっているか）
 * （※）ホストアドレスは大文字・小文字が区別されます。
 * 
 * @param string $host ホストアドレス（手続き元）
 * @return string|null 何らかのエラーがあれば、その原因のエラーを出し、何もなければnullを返します。
 */
function check_host($host): string {
    if (!preg_match('/(^localhost$|^([a-zA-Z0-9][a-zA-Z0-9-]*[a-zA-Z0-9]*\.)+[a-zA-Z]{2,}$|^((25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])\.){3}(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])$|^(([0-9a-fA-F]{1,4}:){7,7}[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,7}:|([0-9a-fA-F]{1,4}:){1,6}:[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,5}(:[0-9a-fA-F]{1,4}){1,2}|([0-9a-fA-F]{1,4}:){1,4}(:[0-9a-fA-F]{1,4}){1,3}|([0-9a-fA-F]{1,4}:){1,3}(:[0-9a-fA-F]{1,4}){1,4}|([0-9a-fA-F]{1,4}:){1,2}(:[0-9a-fA-F]{1,4}){1,5}|[0-9a-fA-F]{1,4}:((:[0-9a-fA-F]{1,4}){1,6})|:((:[0-9a-fA-F]{1,4}){1,7}|:)|fe80:(:[0-9a-fA-F]{0,4}){0,4}%[0-9a-zA-Z]{1,}|::(ffff(:0{1,4}){0,1}:){0,1}((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])|([0-9a-fA-F]{1,4}:){1,4}:((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9]))$)/', $host)) {
	return '<li>ホストアドレス入力ルールに違反しています。</li>';
    } else {
	return '';
    }
}

/**
 * [FUNCTION] データの重複確認（作成時）
 * 
 * エージェントデータ内で、データベース内に同じようなデータが複数作られないように、登録しようとしているデータが他のデータの重複になっていないか調査します。
 * 
 * @param string $host ホストアドレス（手続き元）
 * @param string $com コミュニティ（手続き元）
 * @return string|null 何らかのエラーがあれば、その原因のエラーを出し、何もなければnullを返します。
 */
function check_duplicate($host, $com): string {
    $result = select(true, "VC_AGENT", "COUNT(*) AS AGENTCOUNT", "WHERE AGENTHOST = '$host' AND COMMUNITY = '$com'");
    if ($result['AGENTCOUNT'] == 1) {
	return '<li>データが他の情報と重複しています。エージェントホストとコミュニティは他のと違う情報にしてください。</li>';
    } else {
	return '';
    }
}

/**
 * [FUNCTION] データの重複確認（変更時）
 * 
 * エージェントデータ内で、データベース内に同じようなデータが複数作られないように、変更しようとしているデータが他のデータの重複になっていないか調査します。
 * 
 * @param int $type 変更する項目のタイプを数値で指定します<br>（0..ホストアドレス, 1..コミュニティ名）
 * @param int $pre_agentid エージェントID（手続き元）
 * @param string $changes 変更する値（手続き元）
 * @return string|null 何らかのエラーがあれば、その原因のエラーを出し、何もなければnullを返します。
 */
function check_duplicate_onchange($type, $pre_agentid, $changes): string {
    $res = '';
    $query = '';
    $pare = 0;
    $c = [];
    switch ($type) {
	case 0: $pare = 1;
	    $query = [true, "VC_AGENT", "COMMUNITY", "WHERE AGENTID = $pre_agentid"];
	    $c = [$changes, ''];
	    break;
	case 1: $pare = 0;
	    $query = [true, "VC_AGENT", "AGENTHOST", "WHERE AGENTID = $pre_agentid"];
	    $c = ['', $changes];
	    break;
    }
    if ($query) {
	$q = select($query[0], $query[1], $query[2], $query[3]);
	if ($q) {
	    $c[$pare] = $q[$query[2]];
	    $res .= check_duplicate($c[0], $c[1]);
	} else {
	    $res = '〈データベースエラー〉';
	}
    } else {
	$res = '〈アプリケーションエラー〉';
    }
    return $res;
}

/**
 * [FUNCTION] コミュニティ名確認
 * 
 * コミュニティ名の記述について確認します。<br>
 * 【判定条件】半角英数字（小文字・大文字）・記号（$ _ のみ）を用いて255文字まで
 * 
 * @param string $com コミュニティ名（手続き元）
 * @return string|null 何らかのエラーがあれば、その原因のエラーを出し、何もなければnullを返します。
 */
function check_community($com) {
    if (!preg_match('/^([0-9]|[a-z]|[A-Z]|[$_]){1,255}$/', $com)) {
	return '<li>コミュニティ名指定ルールに違反しています。</li>';
    } else {
	return '';
    }
}

/**
 * [FUNCTION] MIB存在確認
 * 
 * サブツリーが1つ以上でも選択されているかどうかを確認します。<br>
 * また、選択されたサブツリーMIBのSUBIDが実際のデータベース内に存在するかを調べます。
 * 
 * @param string $mib MIB（手続き元）
 * @return string|null 何らかのエラーがあれば、その原因のエラーを出し、何もなければnullを返します。
 */
function check_mib($mib): string {
    if (!isset($mib)) {
	return '<li>MIBが指定されていません。</li>';
    }
    $flag = true;
    foreach ($mib as $m) {
	$flag &= select(true, 'VC_MIB_SUB', 'SID', 'WHERE SID = ' . $m);
	if (!$flag) {
	    break;
	}
    }
    if (!$flag) {
	return '<li>データベース内にないMIBサブツリーIDを検出しました。正しい登録はできません。</li>';
    } else {
	return '';
    }
}
