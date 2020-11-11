<!DOCTYPE html>

<!-- ログアウトページ(LOGOUT)
概要: Virtual Control 上でユーザとしてのログインセッションを解除するページ
遷移元: 各ページ
遷移方法: 各ページのヘッダからログアウトボタンを押下
遷移先: ゲスト専用ページ（INDEX）
-->

<?php
session_start();
unset($_SESSION['gsc_userindex']);
http_response_code(301);
header("Location: index.php");
