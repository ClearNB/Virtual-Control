<?php
include_once '../scripts/general/loader.php';
include_once '../scripts/session/session_chk.php';
include_once '../scripts/general/sqldata.php';
include_once '../scripts/general/former.php';

session_action_vcserver();
$getdata = session_get_userdata();

$loader = new loader();

$fm_pg = new form_generator('fm_pg');
$fm_pg->Button('bt_ag_bk', '設定一覧へ', 'button', 'list');//OPTIONの最初へ
$fm_pg->SubTitle('OPTION - AGENT', 'エージェントを選択してください。', 'book');
$fm_pg->Check(1, 'rd_01', 'agt', '1', 'AGENT1', true);
$fm_pg->Check(1, 'rd_02', 'agt', '2', 'AGENT2',false);
$fm_pg->Check(1, 'rd_03', 'agt', '3', 'AGENT3',false);
$fm_pg->Button('bt_ag_cr', '作成', 'button','plus-square');
$fm_pg->Button('bt_ag_ed', '編集', 'button','edit');
$fm_pg->Button('bt_ag_dl', '削除', 'button','trash-alt');

$fm_fl = new form_generator('fm_fl');
$fm_fl->fm_fl('fm_fl','','失敗しました','[原因]');






$fm_ag_cr = new form_generator('fm_ag_cr'); //エージェントIPアドレス
//追加ページ
$fm_ag_cr->SubTitle('エージェント作成', '以下の情報を入力してください', '', false, '1:エージェント情報入力');
$fm_ag_cr->Input('in_ag_ad', 'エージェントIPアドレス',
        'IPアドレスのほか、ホスト名、ドメイン名の入力ができます。',
        'server', true);

$fm_ag_cr->Input('in_ag_co', 'コミュニティ名',
        'SNMPv2cでのエージェントに対応したコミュニティ名を入力します。',
        'american-sign-language-interpreting', true);

$fm_ag_cr->Button('bt_cr_nx', '次へ', 'button', 'arrow-right');
$fm_ag_cr->Button('bt_cr_bk', 'キャンセル', 'button', 'long-arrow-alt-left');

$fm_ag_sl = new form_generator('fm_ag_sl'); //MIBの設定1
$fm_ag_sl->SubTitle('MIBの設定', '', '');
$fm_ag_sl->Check(0, 'rd_04', 'agt', '4', 'MIBサブツリー1', true);
$fm_ag_sl->Check(0, 'rd_05', 'agt', '5', 'MIBサブツリー2', false);
$fm_ag_sl->Check(0, 'rd_06', 'agt', '6', 'MIBサブツリー3', false);

$fm_ag_sl->Button('bt_sl_nx', '次へ', 'button', 'arrow-right');
$fm_ag_sl->Button('bt_sl_bk', '戻る', 'button', 'long-arrow-alt-left');


//①入力チェック



$fm_ag_ed = new form_generator('fm_ag_ed');//エージェント編集
$fm_ag_ed->SubTitle('[エージェントIPアドレス]', '以下から変更したい項目を選択してください。', '',false,'[コミュニティ名]');
//ＩＰアドレスボタン
$fm_ag_ed->Button('bt_ed_ip', 'IPアドレス', 'button', 'arrow-right');//アイコン未
//コミュニティ名ボタン
$fm_ag_ed->Button('bt_ed_cm', 'コミュニティ名', 'button', 'arrow-right');//アイコン未
//ＭＩＢボタン
$fm_ag_ed->Button('bt_ed_mb', 'MIB', 'button', 'arrow-right');//アイコン未

//設定一覧へ戻るボタン
$fm_ag_ed->Button('bt_ed_bk', '設定一覧へ戻る', 'button', 'arrow-right');//アイコン未



$fm_ag_ip = new form_generator('fm_ag_ip');//IPアドレス入力画面
$fm_ag_ip->SubTitle('現在:[エージェントIPアドレス]', '', '');
$fm_ag_ip->Input('in_ag_ip','エージェントIPアドレス','IPアドレスを入力してください。','user-circle',true);//アイコン未
$fm_ag_ip->Button('bt_ip_nx', '次へ', 'button', 'arrow-right');
$fm_ag_ip->Button('bt_ip_bk', '戻る', 'button', 'long-arrow-alt-left');


//①入力チェック


$fm_ag_cm = new form_generator('fm_ag_cm');//コミュニティ名入力画面
$fm_ag_cm->SubTitle('現在:[コミュニティ名]', '', '');
$fm_ag_cm->Input('in_ag_cm','コミュニティ名','コミュニティ名を入力してください。','user-circle',true);//アイコン未
$fm_ag_cm->Button('bt_cm_nx', '次へ', 'button', 'arrow-right');
$fm_ag_cm->Button('bt_cm_bk', '戻る', 'button', 'long-arrow-alt-left');


//①入力チェック


$fm_ag_mb = new form_generator('fm_ag_mb');//MIB設定画面
$fm_ag_mb->SubTitle('MIBの設定', '', '');
$fm_ag_mb->Check(0, 'rd_07', 'in_ag_mb', '7', 'MIBサブツリー1', true);
$fm_ag_mb->Check(0, 'rd_08', 'in_ag_mb', '8', 'MIBサブツリー2', false);
$fm_ag_mb->Check(0, 'rd_09', 'in_ag_mb', '9', 'MIBサブツリー3', false);
$fm_ag_mb->Button('bt_mb_nx', '次へ', 'button', 'arrow-right');
$fm_ag_mb->Button('bt_mb_bk', '戻る', 'button', 'long-arrow-alt-left');


//①入力チェック


$fm_ag_dl = new form_generator('fm_ag_dl');//削除
$fm_ag_dl->SubTitle('エージェント削除', '[エージェントIPアドレス]<br>のエージェント情報を削除します。', '');
$fm_ag_dl->Button('bt_dl_nx', '次へ', 'button', 'arrow-right');
$fm_ag_dl->Button('bt_dl_bk', 'キャンセル', 'button', 'long-arrow-alt-left');

$fm_ag_ps = new form_generator('fm_ag_ps');//パスワード入力画面（共通で使う）
$fm_ag_ps->SubTitle('パスワード入力', '設定の完了には<br>パスワードが必要です。', '');
$fm_ag_ps->Input('in_ag_ps','パスワード','パスワードを入力してください','user-circle',true);//アイコン未
$fm_ag_ps->Button('bt_ps_nx', '認証', 'button', 'arrow-right');
$fm_ag_ps->Button('bt_ps_bk', 'キャンセル', 'button', 'long-arrow-alt-left');


//①認証



?>

<html>
    <head>
        <?php echo $loader->loadHeader('Virtual Control', 'OPTION - AGENT', true) ?>
        <?php echo form_generator::ExportClass() ?>
    </head>
    <body>
	<?php echo $loader->navigation($getdata['PERMISSION']) ?>
        <?php echo $loader->load_Logo() ?>

        <?php echo $loader->Title('OPTION - AGENT', 'user') ?>
        <div id="data_output"></div>

	<?php echo $loader->footer() ?>
        <?php echo $loader->footerS(true) ?>

        <script type="text/javascript">
            $(document).ready(function () {
                animation('data_output', 0, fm_pg);
            });
            $(document).on('click', '#bt_ag_bk, #bt_ag_cr, #bt_ag_ed, #bt_ag_dl', function () { //OPTION-AGENT
                switch($(this).attr('id')){
                    case "bt_ag_bk":
                        animation_to_sites('data_output', 400, './');//遷移先未設定   
                        break;
                    case "bt_ag_cr":
                        animation('data_output', 400, fm_ag_cr);   
                        break;
                    case "bt_ag_ed":
                    animation('data_output', 400, fm_ag_ed);   
                        break;
                    case "bt_ag_dl":
                    animation('data_output', 400, fm_ag_dl);   
                    break;
                }
            });

            $(document).on('click', '#bt_cr_nx, #bt_cr_bk', function () { //エージェント作成
                switch($(this).attr('id')){
                    case "bt_cr_nx":
                        animation('data_output', 400, fm_ag_sl);   
                        break;
                    case "bt_cr_bk":
                        animation('data_output', 400, fm_pg);  
                        break;
                }
            });
           
            $(document).on('click', '#bt_sl_nx, #bt_sl_bk', function () { //エージェント作成（ＭＩＢの選択）
                switch($(this).attr('id')){
                    case "bt_sl_nx":
                        animation('data_output', 400, fm_ag_sl);//遷移先未設定  
                        break;
                    case "bt_sl_bk":
                        animation('data_output', 400, fm_ag_cr);  
                        break;
                }
            });
            
            $(document).on('click', '#bt_ed_ip, #bt_ed_cm, #bt_ed_mb, #bt_ed_bk', function () { //エージェント編集
                switch($(this).attr('id')){
                    case "bt_ed_ip":
                        animation('data_output', 400, fm_ag_ip);
                        break;
                    case "bt_ed_cm":
                        animation('data_output', 400, fm_ag_cm);  
                        break;
                    case "bt_ed_mb":
                        animation('data_output', 400, fm_ag_mb); 
                        break;
                    case "bt_ed_bk":
                        animation('data_output', 400, fm_pg);
                        break;
                }
            });
            
            $(document).on('click', '#bt_ip_nx, #bt_ip_bk', function () { //エージェント編集（ＩＰアドレス）
                switch($(this).attr('id')){
                    case "bt_ip_nx":
                        animation('data_output', 400, fm_ag_ed);//遷移先未設定
                        break;
                    case "bt_ip_bk":
                        animation('data_output', 400, fm_ag_ed);
                        break;
                }
            });
            
            $(document).on('click', '#bt_cm_nx, #bt_cm_bk', function () { //エージェント編集（コミュニティ名）
                switch($(this).attr('id')){
                    case "bt_cm_nx":
                        animation('data_output', 400, fm_ag_ed);//遷移先未設定
                        break;
                    case "bt_cm_bk":
                        animation('data_output', 400, fm_ag_ed);
                        break;
                }
            });

            $(document).on('click', '#bt_mb_nx, #bt_mb_bk', function () { //エージェント編集（ＭＩＢの設定）
                switch($(this).attr('id')){
                    case "bt_mb_nx":
                        animation('data_output', 400, fm_ag_ed);//遷移先未設定
                        break;
                    case "bt_mb_bk":
                        animation('data_output', 400, fm_ag_ed);
                        break;
                }
            });
            
            $(document).on('click', '#bt_dl_nx, #bt_dl_bk', function () { //エージェント削除
                switch($(this).attr('id')){
                    case "bt_dl_nx":
                        animation('data_output', 400, fm_ag_ps);//遷移先未設定
                        break;
                    case "bt_dl_bk":
                        animation('data_output', 400, fm_pg);
                        break;
                }
            });
                        
            $(document).on('click', '#bt_ps_bk, #bt_ps_nx', function () { //パスワード入力
                switch($(this).attr('id')){
                    case "bt_ps_bk":
                        animation('data_output', 400, fm_pg);   
                        break;
                    case "bt_ps_nx":
                        animation('data_output', 400, fm_pg);   
                        break;
                }
            });
            
            
            
            
            
            
            /*
            $(document).on('click', '#, #', function () { //テンプレ
                switch($(this).attr('id')){
                    case "bt":
                      
                        break;
                    case "bt":
                 
                        break;
                }
            });
            */
            
            
            
            
        </script>
	
    </body>
</html>