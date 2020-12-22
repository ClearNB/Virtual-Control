<?php
include_once './scripts/general/loader.php';
include_once './scripts/session/session_chk.php';
include_once './scripts/general/sqldata.php';
include_once './scripts/general/former.php';

session_action_guest();

$loader = new loader();
$fm_dt01 = new form_generator('fm_dt01', '', 2);
$fm_dt01->SubTitle("アクセス監視は、新たな挑戦へ", "Virtual Control は、SNMPを利用したネットワークアクセス監視を実現できる監視ツールです。<br><br>アクセス監視は運用・保守の専門職を問わず、誰でも監視できる環境を整えなければならない時代に差し掛かっています。その状況の中で、私たちは「標準化」を目的に、Webアプリケーションで監視が可能なアプリケーションを開発しました。", 'fas fa-server', true);

$fm_dt02 = new form_generator('fm_dt02');
$fm_dt02->SubTitle('使いやすく、そしてわかりやすく', 'Virtual Control は、できるだけ利用しやすい環境として HTML5 (+ CSS, JavaScript), PHP の2言語を使用しております。', 'fas fa-users');
$fm_dt02->Button('bt_02_gh', 'GitHubを開く', 'button', 'fab fa-github-square');

$fm_dt03 = new form_generator('fm_dt03', '', 2);
$fm_dt03->SubTitle('もっと気軽に', '', 'fas fa-laptop-code', true);
$fm_dt03->Card('標準MIBに準拠した監視を', 'server', 'OID識別を日本語メッセージに変換！', '参照しなければ何の項目かわからないOIDを、標準MIBに準拠した日本語メッセージを搭載！安心して気になった項目を監視できます。');

$fm_dt04 = new form_generator('fm_dt04');
$fm_dt04->CardDark('WARNING!', 'fas fa-user', 'ログインが必要です', '本サーバはユーザ登録制です。<br>管理者権限により作成されたアカウントでログインしてください。');
$fm_dt04->Button('bt_04_lg', 'ログインする', 'button', 'sign-in-alt');

form_generator::resetData();
$fm_dt = new form_generator('fm_dt', $fm_dt01->Export() . $fm_dt02->Export() . $fm_dt03->Export() . $fm_dt04->Export());

?>

<html>
    <head>
        <?php echo $loader->loadHeader('Virtual Control', 'INDEX') ?>
	<?php echo form_generator::ExportClass() ?>
    </head>

    <body class="text-monospace">
        <!-- Navbar -->
        <?php echo $loader->navigation(2) ?>
	<?php echo $loader->load_Logo() ?>
	
        <div id="data_output"></div>

        <!-- Footer -->
        <?php echo $loader->footer() ?>

        <!-- JavaScript dependencies -->
        <?php echo $loader->footerS() ?>
	
	<script type="text/javascript">
	    $(document).ready(function() {
		animation('data_output', 0, fm_dt);
	    });
	    
	    $(document).on('click', '#bt_02_gh, #bt_04_lg', function() {
		switch($(this).attr('id')) {
		    case "bt_02_gh":
			animation_to_sites('data_output', 400, 'https://github.com/ClearNB/Virtual-Control');
			break;
		    case "bt_04_lg":
			animation_to_sites('data_output', 400, './login.php');
			break;
		}
	    });
	</script>
    </body>
</html>