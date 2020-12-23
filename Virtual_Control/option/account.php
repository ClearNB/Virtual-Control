<?php
include_once '../scripts/general/loader.php';
include_once '../scripts/session/session_chk.php';
include_once '../scripts/general/sqldata.php';
include_once '../scripts/general/former.php';

session_action_vcserver();
$getdata = session_get_userdata();

$loader = new loader();
//トップページ
$fm_pg = new form_generator('fm_pg');
$fm_pg->Button('bt_bk', '設定一覧へ', 'button', 'list');//OPTIONの最初へ
$fm_pg->SubTitle('OPTION - AGENT', 'エージェントを選択してください。', 'book');

$fm_pg->Caption('[data]');

$fm_pg->Button('bt_ac_cr', '作成', 'button','plus-square');
$fm_pg->Button('bt_ac_ed', '編集', 'button','edit');
$fm_pg->Button('bt_ac_dl', '削除', 'button','trash-alt');

$fm_pg_fl = new form_generator('fm_pg_fl');
$fm_pg_fl->SubTitle('アカウント情報の取得に失敗しました。', 'データベースに接続できません。', 'book');






//アカウント作成
$fm_ac_cr = new form_generator('fm_ac_cr');
$fm_ac_cr->SubTitle('アカウント作成', '以下の情報を入力してください', '', false, '1:アカウント情報入力');

$fm_ac_cr->Input('in_ac_ad', 'ユーザID','ユーザIDの入力ができます。','server', true);
$fm_ac_cr->Input('in_ac_nm', 'ユーザ名','ユーザ名の入力ができます。','server', true);//アイコン
$fm_ac_cr->Input('in_ac_ps', 'パスワード','パスワードの入力ができます。','server', true);//アイコン
$fm_ac_cr->Input('in_ac_ps_02', 'パスワードの確認','確認の為、もう一度パスワードを入力してください。','server', true);//アイコン

$fm_ac_cr->Check(0, 'rd_01', 'in_ac_vc', '1', 'VCServer', true);
$fm_ac_cr->Check(0, 'rd_02', 'in_ac_vc', '2', 'VCHost', false);

$fm_ac_cr->Button('bt_cr_nx', '次へ', 'button', 'arrow-right');
$fm_ac_cr->Button('bt_cr_bk', 'キャンセル', 'button', 'long-arrow-alt-left');

//①入力チェック

//パスワード認証　or  入力ミス

//②認証

//確認画面　or　認証失敗

//③入力チェック・更新

//更新成功　or  更新失敗





//アカウント編集
$fm_ac_ed = new form_generator('fm_ac_ed');
$fm_ac_ed->SubTitle('[アカウント名]', '以下から変更したい項目を選択してください。', '',false,'[コミュニティ名]');
//ユーザIDボタン
$fm_ac_ed->Button('bt_ed_id', 'ユーザID', 'button', 'arrow-right');//アイコン未
//コミュニティ名ボタン
$fm_ac_ed->Button('bt_ed_nm', 'ユーザ名', 'button', 'arrow-right');//アイコン未
//ＭＩＢボタン
$fm_ac_ed->Button('bt_ed_ps', 'パスワード', 'button', 'arrow-right');//アイコン未
//設定一覧へ戻るボタン
$fm_ac_ed->Button('bt_ed_bk', '設定一覧へ戻る', 'button', 'arrow-right');//アイコン未

//ユーザID画面
$fm_ac_id = new form_generator('fm_ac_id');//ユーザID入力画面
$fm_ac_id->SubTitle('現在:[ユーザID]', '', '');
$fm_ac_id->Input('in_ac_id','ユーザID','ユーザIDを入力してください。','user-circle',true);//アイコン未
$fm_ac_id->Button('bt_id_nx', '次へ', 'button', 'arrow-right');
$fm_ac_id->Button('bt_id_bk', '戻る', 'button', 'long-arrow-alt-left');

//①入力チェック

//ユーザ名画面
$fm_ac_nm = new form_generator('fm_ac_nm');//ユーザ名入力画面
$fm_ac_nm->SubTitle('現在:[ユーザ名]', '', '');
$fm_ac_nm->Input('in_ac_nm','ユーザ名','ユーザ名を入力してください。','user-circle',true);//アイコン未
$fm_ac_nm->Button('bt_nm_nx', '次へ', 'button', 'arrow-right');
$fm_ac_nm->Button('bt_nm_bk', '戻る', 'button', 'long-arrow-alt-left');

//①入力チェック

//パスワード画面
$fm_ac_ps = new form_generator('fm_ac_ps');//パスワード入力画面
$fm_ac_ps->SubTitle('', '新しいパスワードを入力してください。', '');
$fm_ac_ps->Input('in_ac_ps','パスワード','パスワードを入力してください。','user-circle',true);//アイコン未
$fm_ac_ps->Input('in_ac_ps_02','パスワードの確認','確認の為、もう一度パスワードを入力してください。','user-circle',true);//アイコン未
$fm_ac_ps->Button('bt_ps_nx', '次へ', 'button', 'arrow-right');
$fm_ac_ps->Button('bt_ps_bk', '戻る', 'button', 'long-arrow-alt-left');

//①入力チェック





//アカウント削除





//共通ページ
?>

<html>
    <head>
	<?php echo $loader->loadHeader('Virtual Control', 'OPTION - ACCOUNT', true) ?>
	<?php echo form_generator::ExportClass() ?>
    </head>
    <body>
	<?php echo $loader->navigation($getdata['PERMISSION']) ?>
	<?php echo $loader->load_Logo() ?>
	
	<?php echo $loader->Title('OPTION - ACCOUNT', 'user') ?>
	<div id="data_output"></div>
	
	<?php echo $loader->footer() ?>
	<?php echo $loader->footerS(true) ?>
	
	<script type="text/javascript">
	    $(document).ready(function() {
		//animation('data_output', 0, fm_pg);
                ajax_dynamic_post_toget('../scripts/account/account_get.php').then(function(data){
                   switch(data['code']){
                       case 0:
                           var fm_w = fm_pg.replace('[data]', data['data']);
                           animation('data_output', 0, fm_w);
                           break;
                       case 1:
                           animation('data_output', 0, fm_pg_fl);
                           break;
                   }
               });
	    });
            
            $(document).on('click', '#bt_bk, #bt_ac_cr, #bt_ac_ed, #bt_ac_dl', function () { //OPTION-account
                switch($(this).attr('id')){
                    case "bt_bk":
                        animation_to_sites('data_output', 400, './');   
                        break;
                    case "bt_ac_cr":
                        animation('data_output', 400, fm_ac_cr);   
                        break;
                    case "bt_ac_ed":
                    animation('data_output', 400, fm_ac_ed);   
                        break;
                    case "bt_ac_dl":
                    animation('data_output', 400, fm_ac_dl);   
                    break;
                }
            });
            
            $(document).on('click', '#bt_cr_nx, #bt_cr_bk', function () { //アカウント作成
                switch($(this).attr('id')){
                    case "bt_cr_nx":
                        animation('data_output', 400, fm_pg);//遷移先   
                        break;
                    case "bt_cr_bk":
                        animation('data_output', 400, fm_pg);  
                        break;
                }
            });
            
            $(document).on('click', '#bt_ed_id, #bt_ed_nm, #bt_ed_ps, #bt_ed_bk', function () { //エージェント編集
                switch($(this).attr('id')){
                    case "bt_ed_id":
                        animation('data_output', 400, fm_ac_id);
                        break;
                    case "bt_ed_nm":
                        animation('data_output', 400, fm_ac_nm);  
                        break;
                    case "bt_ed_ps":
                        animation('data_output', 400, fm_ac_ps); 
                        break;
                    case "bt_ed_bk":
                        animation('data_output', 400, fm_pg);
                        break;
                }
            });
            
            $(document).on('click', '#bt_id_nx, #bt_id_bk', function () { //エージェント編集（ＩＰアドレス）
                switch($(this).attr('id')){
                    case "bt_id_nx":
                        animation('data_output', 400, fm_pg);//遷移先未設定
                        break;
                    case "bt_id_bk":
                        animation('data_output', 400, fm_ac_ed);
                        break;
                }
            });
            
            $(document).on('click', '#bt_ac_nx, #bt_ac_bk', function () { //エージェント編集（コミュニティ名）
                switch($(this).attr('id')){
                    case "bt_ac_nx":
                        animation('data_output', 400, fm_pg);//遷移先未設定
                        break;
                    case "bt_ac_bk":
                        animation('data_output', 400, fm_ac_ed);
                        break;
                }
            });
            
            $(document).on('click', '#bt_ps_nx, #bt_ps_bk', function () { //エージェント編集（ＭＩＢの設定）
                switch($(this).attr('id')){
                    case "bt_ps_nx":
                        animation('data_output', 400, fm_ag_ed);//遷移先未設定
                        break;
                    case "bt_ps_bk":
                        animation('data_output', 400, fm_ac_ed);
                        break;
                }
            });
	</script>
    </body>
</html>