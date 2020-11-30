<?php

function table($table_id, $table_title, $table_title_icon, $table_headers, $table_index, $table_values, $ischeckTable = false, $check_name = '', $check_id = '') {
    $result = '<div id="' . $table_id . '"><h3 class="text-left text-body"><i class="fa fa-fw fa-' . $table_title_icon . '"></i>' . $table_title . '</h3><table class="table table-hover table-responsive"><tbody>';
    $result .= '<tr>';
    if ($ischeckTable) { $result .= '<th><input id="' . $check_id . '-0" type="checkbox" name="' . $check_name . '" value="all" /><label for="' . $check_id . '-0" class="checkbox02">#</label></th>'; }
    foreach ($table_headers as $var) { $result .= '<th>' . $var . '</th>'; }
    $result .= '</tr>';
    $i = 1;
    $result .= '<tr>';
    while ($row = $table_values->fetch_assoc()) {
        if ($ischeckTable) {
            $result .= '<td><input id="' . $check_id . '-' . $i . '" type="checkbox" name="' . $check_name . '" value="all" /><label for="' . $check_id . '-' . $i . '" class="checkbox02">' . $i . '</label></td>';
        }
        $c_d = [];
        foreach ($table_index as $var) { array_push($c_d, $row[$var]); }
        foreach ($c_d as $var) { $result = '<td>' . $var . '</td>'; }
        $i += 1;
    }

    //Exit : 4
    $result .= '</tr></tbody></table></div>';

    return $result;
}

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
        return table($table_id, $table_title, $table_title_icon, $table_headers[0], $table_headers[1], $result, true, 'index-s', 'index');
    } else {
        return false;
    }
}

function result_table($result) {
    
}