<?php

/* Query Function
 * Send Query to SQL Database for MySQL.
 * {return: Result for Query}
 */

function query($query) {
    $mysqli = get_db();
    $result = $mysqli -> query($query);

    if (!$result) {
        print "クエリが失敗しました" . "Errormessage: <br>" . $mysqli -> error . "<br>";
	print "原因クエリ: " . $query . "<br>";
        return false;
    }

    return $result;
}

function random($length = 8) {
    return substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'), 0, $length);
}

/* Load File(*.JSON) Function
 * {args: File Name (file pass)}
 * {return: Decoded Json Data}
 */
function loadfile($filename) {
    $ip = filter_input(INPUT_SERVER, 'SERVER_ADDR', FILTER_SANITIZE_STRING);
    if($ip == '::1') {
        $ip = '127.0.0.1';
    }
    $json = file_get_contents("http://" . $ip . "/" . $filename);
    $arr = json_decode($json, true);
    return $arr;
}