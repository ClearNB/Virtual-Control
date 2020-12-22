<?php

/**
 * AGENT_GET (エージェント取得処理ファンクション)
 * ここでは、エージェント情報の取得およびエージェント選択の作成を行います
 * 処理される内容は以下の通りです
 * 1. アカウントデータの取得（取得できたらデータ、取得できなかったらfalseを返される）
 * 2-1-1. 取得できたら、アカウント選択を作成します（クラス受け渡し）
 * 2-1-2. ['code']を0にします
 * 2-2. 取得できない場合は['code']を1にします
 * 返される値は以下の通りです
 * ['code'] -> 0 (成功) or 1(失敗)
 * ['data'] -> [テーブルHTML] (成功) or [なし] (失敗)
 */

include_once ('../general/sqldata.php');
include_once ('../general/table.php');
include_once ('./agentdata.php');
include_once ('./agentselect.php');

$requestmg = filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH');

$request = isset($requestmg) ? strtolower($requestmg) : '';
if ($request !== 'xmlhttprequest') {
    http_response_code(403);
    header('Location: ../../403.php');
    exit;
}

$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING);
if ($method === 'POST') {
    $data = AGENTData::get_agent_info();
    $res = ["code" => 1];
    if($data) {
	$select = new AgentSelect($data);
	$res = ["code" => 0, "data" => $select->getSelect()];
    }
    ob_get_clean();
    echo json_encode($res);
}