<?php

/**
 * レコード型テーブルを作成します。
 * @param string テーブルに与えられる固有のIDです。
 * @param string テーブルのタイトル名（先頭に載せられます）
 * @param string タイトルアイコン
 * @param array  データ連想配列のキー項目表示名を格納した配列です（順序配列）
 * @param array  データ連想配列のキーを格納した配列です（順序配列）
 * @param array  データ（連想配列）
 * @param bool   チェックボックスが左端につくように設定するかどうか
 * @param string 表示チェック名
 * @param string チェックID (雛形)
 * @return string
 */
function horizonal_table($table_id, $table_title, $table_title_icon, $table_headers, $table_index, $table_values, $ischeckTable = false, $check_name = '', $check_id = '') {
    //初期設定
    $result = '<div id="' . $table_id . '"><h3 class="text-left text-body"><i class="' . $table_title_icon . ' fa-fw"></i>' . $table_title . '</h3><table class="table table-hover table-responsive"><tbod\y>';
    
    //ヘッダー部の作成
    $result .= '<tr>';
    if ($ischeckTable) { $result .= '<th><input id="' . $check_id . '-0" type="checkbox" name="' . $check_name . '" value="all" /><label for="' . $check_id . '-0" class="checkbox02">#</label></th>'; }
    foreach ($table_headers as $var) { $result .= '<th>' . $var . '</th>'; }
    $result .= '</tr>';
    
    //データ行の挿入
    $i = 1;
    $result .= '<tr>';
    //データの開示
    while ($row = $table_values->fetch_assoc()) {
	//チェックボックステーブルの場合、この処理を加える
        if ($ischeckTable) {
            $result .= '<td><input id="' . $check_id . '-' . $i . '" type="checkbox" name="' . $check_name . '" value="all" /><label for="' . $check_id . '-' . $i . '" class="checkbox02">' . $i . '</label></td>';
        }
	//データを順序配列化
        $c_d = [];
        foreach ($table_index as $var) { array_push($c_d, $row[$var]); }
	//順序配列をもとにデータをテーブルに格納
        foreach ($c_d as $var) { $result = '<td>' . $var . '</td>'; }
        $i += 1;
    }
    
    //Exit : 4
    $result .= '</tr></tbody></table></div>';

    return $result;
}

/**
 * 
 * @param type $table_id	    
 * @param type $table_title	    
 * @param type $table_title_icon    
 * @param type $table_datas	    
 * @return string
 */
function table_vertical($table_id, $table_title, $table_title_icon, $table_datas) {
    $result = '<h3 class="text-left text-body"><i class="fas fa-fw fa-' . $table_title_icon . '"></i>'
	    . $table_title . '</h3><table class="table table-hover" id="' . $table_id . '"><tbody>';
    foreach($table_datas as $var) {
	$result .= '<tr>';
        $result .= '<td>' . $var[0] . '</td>';
	$result .= '<td>' . $var[1] . '</td>';
	$result .= '</tr>';
    }
    $result .= '</tbody></table></div>';
    return $result;
}

/**
 * アカウント一覧表を作成します
 * @return string (no: boolean)
 */
function account_table() {
    $table_id = 'account_table';
    $table_title = 'アカウント一覧';
    $table_title_icon = '';
    $table_headers = [
        ["権限", "ユーザ名", "ユーザID", "メールアドレス", "ログイン日時"],
        ["PER", "USERNAME", "USERID", "MAILADDRESS", "LOGINUPTIME"]
    ];
    $result = select(false, "GSC_USERS", "CASE PERMISSION WHEN 1 THEN 'VCServer' WHEN 2 THEN 'VCHost' END AS PER, USERID, USERNAME, MAILADDRESS, LOGINUPTIME");
    if ($result) {
        return horizonal_table($table_id, $table_title, $table_title_icon, $table_headers[0], $table_headers[1], $result, true, 'index-s', 'index');
    } else {
        return false;
    }
}
