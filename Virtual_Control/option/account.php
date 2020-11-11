<!DOCTYPE html>

<!-- PHP HEADER MODULE -->
<?php
include ('../scripts/session_chk.php');
if (!session_chk()) {
    http_response_code(403);
    header("Location: ../403.php");
    exit();
}

//include
include_once ('../scripts/sqldata.php');
include_once ('../scripts/common.php');
include_once ('../scripts/dbconfig.php');
include_once ('../scripts/former.php');
include_once ('../scripts/loader.php');
include_once ('../scripts/table_generator.php');

$loader = new loader();

//1: アカウント表
$form_accounts = new form_generator('form_accounts');
$form_accounts->SubTitle('アカウント一覧', 'アカウントの一覧表を以下に表示します。操作を行ってください。', 'group');
$data = account_table();
if ($data) {
    $form_accounts->Caption($data);
} else {
    $form_accounts->Caption('TABLEDATA');
}
$form_accounts->Caption('【操作方法】<ul class="title-view">'
        . '<li>作成 ... 以下の【作成】ボタンを押してください。</li>'
        . '<li>編集 ... 一覧表隣のチェックボックスに1件だけ選択し、【編集】ボタンを押してください。</li>'
        . '<li>削除 ... 一覧表隣のチェックボックスに該当部分を選択し、【削除】ボタンを押してください。'
        . '<br><strong>警告: VCServerは1人以上存在する必要があります。</strong></li>'
        . '<li>戻る ... 設定一覧へ戻ります。</li>'
        . '</ul>');
$form_accounts->Button('create', '作成', 'button');
$form_accounts->Button('edit', '編集', 'button');
$form_accounts->Button('remove', '削除', 'button');
$form_accounts->Button('back', '戻る', 'button');

//2: アカウントの作成
$form = new form_generator('form_create');
$form->Button('exit_form', 'アカウント一覧に戻る', 'button', 'chevron-circle-left');
$form->Title('ユーザの新規登録', 'user-plus');
$form->Input('userid', 'ユーザID', 'ユーザIDを入力してください。', 'id-badge', true);
$form->Input('username', 'ユーザ名', 'ユーザ名（表示名）を入力してください。', 'user', true);
$form->SubTitle('パスワード', '<ul><li>10文字以上15文字以下</li><li>半角英数字(大/小含む)</li><li>記号(_, $) OK</li></ul>', 'key');
$form->Password('pass', 'パスワード', 'パスワードを入力してください。', 'key', true);
$form->Password('r-pass', 'パスワードの確認', 'パスワードをもう一度入力してください。', 'key', true);
$form->SubTitle('権限選択', 'このユーザに割り当てる権限を指定してください。', 'users');
$form->Check(1, 'per-01', 'per-radio', 'vcs', 'VCServer', false);
$form->Check(1, 'per-02', 'per-radio', 'vch', 'VCHost', true);
$form->button('form_submit', '送信する', 'button');

//3: アカウントの編集
$form_edit = new form_generator('form_edit');
$form_edit->SubTitle('[USERNAME] さんのデータを編集します', '以下から変更したいものを選んでください。', 'user');
$form_edit->button('ed01', 'ユーザIDを変更', false);
$form_edit->button('ed02', 'ユーザ名を変更', false);
$form_edit->button('ed03', 'パスワードを変更', false);
$form_edit->button('ed04', '権限を変更', false);
$form_edit->button('exit', '一覧に戻る', false);

//4: アカウントの削除

$form3 = $loader->Title('ユーザデータの削除', 'pencil');
$form3 = $form3 . $loader->SubTitle('以下のユーザを削除します', '入力をご確認ください。', 'user');

//5: 認証
$form4 = new form_generator('auth_form');
$form4->SubTitle('認証', '変更を保存するには、パスワードが必要です。', 'key');
$form4->Password('a-pass', 'パスワード', 'あなたのパスワードを入力してください。', 'key', true);

$index = $_SESSION['gsc_userindex'];
$getdata = select(true, "GSC_USERS", "USERNAME, PERMISSION", "WHERE USERINDEX = $index");
?>

<html>
    <head>
        <?php echo $loader->loadHeader('Virtual Control', 'OPTION - ACCOUNT', true) ?>
        <script type="text/javascript">
            var fdata_list = '<?php echo $form_accounts->Export() ?>';
        </script>
    </head>

    <body class="text-monospace">
        <!-- HEADER NAVIGATION -->
        <?php echo $loader->navigation($getdata['PERMISSION']) ?>

        <!-- HEADER SECTION -->
        <div class="bg-primary pt-5">
            <div class="container">
                <?php echo $loader->load_Logo(); ?>
            </div>
        </div>

        <!-- TITLE SECTION -->
        <div class="my-2">
            <div class="container">
                <?php echo $loader->SubTitle('アカウント管理', 'ここでは、アカウントの作成・編集・削除が行えます。', 'group') ?>
            </div>
        </div>

        <!-- CONTENT SECTION -->
        <div class="py-2 bg-primary">
            <div class="container" id="data_output">

            </div>
        </div>

        <!-- FOOTER -->
        <?php echo $loader->footer() ?>

        <!-- FOOTER SCRIPTS -->
        <script src="../js/now_loading.js"></script>
        <script src="../js/jquery.js"></script>
        <script src="../jquery/jquery-ui.js"></script>
        <script src="../js/popper.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script type="text/javascript">
            //Javascript, jQuery Code Structure Here            
            $(document).ready(function() {
                animation('data_output', 0, fdata_list);
            });

            $(function () {

                //チェックマークによる挙動の操作
                $('input[name="index-s"]').change(function () {
                    //現在変更したオブジェクト名の確認
                    var con = $(this).val();
                    //allチェックマークの確認用
                    var s = 0;
                    if ($('#index-0').prop('checked') === true) {
                        //all部分がチェックマークついていたら1を加える
                        s = 1;
                    }
                    if (con === 'all') {
                        //変更したオブジェクトがallの場合は、すべてのオブジェクトのチェックマークに対して同じフラグを立てる
                        var changeFlag = $(this).prop("checked");
                        $("#account-data input:checkbox").prop("checked", changeFlag);
                    } else {
                        //チェックマークをつけていないオブジェクトを確認する
                        var c_none_checked = $("#account-data input:checkbox").length - $("#account-data input:checkbox:checked").length - s - 1;
                        /* なかった場合はallをcheckedに、あった場合はcheckedを無効化する
                         * その際にsの値を変更する
                         */
                        if (c_none_checked === 0) {
                            $('#index-00').prop('checked', true);
                            s = 1;
                        } else {
                            $('#index-00').prop('checked', false);
                            s = 0;
                        }
                    }
                    //チェックされた数を数える
                    var c_checked = $("#account-data input:checkbox:checked").length - s;
                    if (c_checked > 0) {
                        //1つ以上選択されたら、編集・削除を選択できる（条件下）
                        $('#content_output').text('選択数: ' + c_checked);
                        //編集する数は1つに限られている
                        if (c_checked === 1) {
                            $('#edit').prop("disabled", false);
                        } else {
                            $('#edit').prop("disabled", true);
                        }
                        //削除は複数行えるが、VCServerは1人以上いなければならない。
                        //[判定追加]
                        $('#delete').prop("disabled", false);
                    } else {
                        //0コの場合は作成以外のボタンを無効化・ラベルを外す
                        $('#content_output').text('');
                        $('#edit').prop("disabled", true);
                        $('#delete').prop("disabled", true);
                    }
                });
            });

            $('#create').click(function (event) {
                event.preventDefault();
                $arc = $('#data_output').html();
                //作成ページ遷移
                $('#data_output').fadeOut(400).queue(function () {
                    var data = '<?php echo $form->Export(); ?>';
                    $('#data_output').html(data).show();
                });
            });


            $('#edit').click(function (event) {
                console.log('aaa');
                event.preventDefault();
                var s = 0;
                if ($('#index-00').prop('checked') === true) {
                    //all部分がチェックマークついていたら1を加える
                    s = 1;
                }
                var cnt = $("#account-data input:checkbox:checked").length - s;
                if (cnt === 1) {
                    //編集ページ遷移
                    $('#data_output').fadeOut(400).queue(function () {
                        var area = $('#account-data input:checkbox:checked').map(function () {
                            return $(this).val();
                        }).get();
                        var data = '<?php echo $form_edit; ?>';
                        $('#data_output').html(data).show();
                    });
                }
            });

            $('#exit_form').click(function () {
                $('#data_output').fadeOut(400).queue(function () {
                    $('#data_output').html($arc).show();
                });
            });

            $('#delete').click(function (event) {
                event.preventDefault();
                var cnt = $("#account-data input:checkbox:checked").length;
                if (cnt > 0) {
                    generate_yesorno_dialog("dialog-3", "delete-dialog", "<i class=\"fa fa-fw fa-window-close fa-lx\"></i>削除");
                }
            });
        </script>
    </body>

</html>
