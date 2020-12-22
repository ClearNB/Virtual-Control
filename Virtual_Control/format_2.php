<?php

//FormGenerator の使い方
include_once './scripts/general/loader.php';
include_once './scripts/session/session_chk.php';
include_once './scripts/general/sqldata.php';
include_once './scripts/general/former.php';

//ローダオブジェクトの作成
$loader = new loader();

//オブジェクトの作り方
//form_generator('[FORM_ID]', '[ACTION] = ''', '[COLOR_FLAG] = 1')
$fm_pg = new form_generator('fm_pg', '');
//SubTitle('[TITLE]', '[CAPTION]', '[ICON]')
$fm_pg->SubTitle(
	'AGENT',
        ' ',
        ''
);

//openList() → addList('[CAPTION]') → closeList()
$fm_pg->openList();
$fm_pg->addList('あなたは誰ですか？');
$fm_pg->addList('何しに来たのですか？');
$fm_pg->addList('教えないと・・・オシオキ（意味深）よ。');
$fm_pg->closeList();

$fm_pg->CardDark(
	'Virtual Control',
	'server',
	'ターン、ドロー！',
	'ドロー！モンスターカード！ｳﾜｧｧｧｧｧ!!<br>'
	. 'ドロー！モンスターカード！ｳﾜｧｧｧｧｧ!!<br>'
	. 'ドロー！モンスターカード！ｳﾜｧｧｧｧｧ!!<br>'
	. 'もうやめて！ライフはもう0よ！ﾊﾅｾｯ!!!');

//Input('[ID]', '[DESC]', '[SMALL_DESC]', '[ICON]', [REQUIRED] : true or false)
//IDの指定: in_[変数名の下2桁]_[指定名の2桁]
$fm_pg->Input(
	'in_pg_nm',
	'名前',
	'あなたの名前を入力してください。',
	'user-circle',
	true
);
//Button('[ID] : bt_[FormIDの下2桁]_[やること2桁]', '[NAME]', '[TYPE] : button, submit', '[ICON]', '[COLOR] = dark, primary')
$fm_pg->Button(
	'bt_pg_pr',
	'はじめる！',
	'submit',
	'angle-double-right'
);

$fm_st = new form_generator('fm_st', '');
$fm_st->SubTitle(
	'[Name] さん',
	'やったね！表示されたよ！',
	'user-circle'
);
$fm_st->Button('bt_st_lk', 'ホームに行く', 'button', 'angle-double-right');
$fm_st->Button('bt_st_bk', '戻る', 'button', 'angle-double-left');
?>

<html>
    <head>
	<!-- loadHeader('[SITE_TITLE]', '[PAGE_TITLE]', '[ISOPTION: true or false]') -->
	<?php echo $loader->loadHeader('Virtual Control', 'FORMAT') ?>
	<!-- JavaScript部分を自動生成します - ::ExportClass([[Object1], [Object2], ...]) -->
	<?php echo form_generator::ExportClass() ?>
    </head>
    <body>
	<!-- ナビゲーションを表示させる : navigation([PERMISSION] : OTHER(GUEST) , 1(VCSERVER, VCHOST)) -->
	<?php echo $loader->navigation(0) ?>
	<!-- ロゴを表示させる : load_Logo() -->
	<?php echo $loader->load_Logo() ?>
	
	<!-- タイトルを表示する : Title('[TITLE]', '[ICON]') -->
	<?php echo $loader->Title('AGENT', 'align-justify') ?>
	
	<!-- ページデータ表示範囲を作る -->
	<div id="data_output"></div>
	
	<!-- フッタ―を表示させる : footer() -->
	<?php echo $loader->footer() ?>
	
	<!-- フッターJavascriptソースインプット : footerS() -->
	<?php echo $loader->footerS() ?>
	
	<script type="text/javascript">
	    $(document).ready(function() {
		//animation('[RANGE_OUTPUT]', [DURATION], '[PAGE_STRING]')
		animation('data_output', 0, fm_pg);
	    });
	    
	    //フォームを実行したときのイベント: $(document).on('submit', '#[ID1], #[ID2], ...', function() { ... });
	    $(document).on('submit', '#fm_pg', function(event) {
		event.preventDefault();
		var name = $('#in_pg_nm').val();
		var fdata = fm_st.replace('[Name]', name);
		animation('data_output', 400, fdata);
	    });
	    
	    //ボタンを押したときのイベント: $(document).on('click', '#[ID1], #[ID2], ...', function() { ... });
	    $(document).on('click', '#bt_st_lk', function() {
		//animation_to_sites('[RANGE_OUTPUT]', [DURATION], '[PAGE_LINK]')
		animation_to_sites('data_output', 400, './index.php');
	    });
	    
	    $(document).on('click', '#bt_st_bk', function() {
		animation('data_output', 400, fm_pg);
	    });
	</script>
    </body>
</html>