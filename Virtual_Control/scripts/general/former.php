<?php

/**
 * [CLASS] Former
 * 
 * <h4>Former v1.5.0</h4><hr>
 * Formerは、HTMLコードをPHP上で定義したメソッドを用いて、ページ構成をオブジェクト単位で作成するクラスです。<br>
 * 設定機能はもちろんのこと、作成したページを読み込む際にJavaScript方式にエンコードする機能も備わっています。
 * 
 * @package VirtualControl_scripts_general
 * @author ClearNB <clear.navy.blue.star@gmail.com>
 */
class Former {

    /**
     * オブジェクト作成時に自動的にプッシュされる順序配列です
     * 
     * @var array $gen_data */
    private static $gen_data = [];

    /**
     * フォームデータであり、配列で格納されています
     * 
     * @var array $data */
    protected $data;

    /**
     * フォームデータに対する識別用のIDです
     * @var string $id */
    private $id;

    /**
     * [METHOD] コンストラクタ
     * 
     * Formerのコンストラクタです。<br>
     * POST通信をベースとしたフォームを作ります。<br>
     * ここでは\<form\>の内容をを定義します。
     * 
     * @param string $id フォームグループの一意なIDを指定します。
     * @param string $data 【任意】アクション先の外部ファイル。通常空白
     * @param integer $color_flag 【任意】背景のフラグを設定します（0..主背景, 1..黒背景）
     */
    function __construct($id, $data = '', $color_flag = 0) {
	$this->reset($id, $data, $color_flag);
    }

    /**
     * [SET] タイトル作成
     * 
     * タイトルを作成します。<br>
     * タイトルとその左隣にアイコンがあるフォーマットになります。
     * 
     * @param string $title  タイトル名を指定します
     * @param string $icon   タイトルの左隣につけるアイコンの情報を入力します
     * 
     * @return void タイトルがオブジェクト内のデータの1番後ろに追加されます。
     */
    function Title($title, $icon): void {
	array_push($this->data, '<h2 class="vc-title"><i class="fas fa-' . $icon . ' fa-fw"></i>' . $title . '</h2>');
    }

    /**
     * [SET] サブタイトル（キャプションあり）作成
     * 
     * サブタイトルを作成します。<br>
     * サブタイトルは、タイトルより拡張したもので、タイトルの左隣にアイコン、その下を区切り線で区切り、その下にテキストがあります。
     * 
     * @param string $title	    タイトル名を指定します
     * @param string $caption	    タイトルの下部につける説明を入力します
     * @param string $icon	    タイトルの左隣につけるアイコンの情報を入力します
     * @param string $badgetext	    タイトル横にある背景付きのテキストを表示させます（Default: ''）
     * 
     * @return void サブタイトルがオブジェクト内のデータの1番後ろに追加されます。
     */
    function SubTitle($title, $caption, $icon, $badgetext = ''): void {
	$b_text = ($badgetext) ? '<span class = "badge-dark badge-pill">' . $badgetext . '</span>' : '';
	array_push($this->data, '<h3 class="sub-title"><i class="fas fa-' . $icon . ' fa-fw"></i>' . $title . ' ' . $b_text . '</h3><p class="sub-caption">' . $caption . '</p>');
    }

    /**
     * [SET] キャプション作成
     * 
     * キャプションを追加します。<br>
     * タイトルはなく、簡易的なローデータを入れることができます。<br>
     * \<div\>で囲まれるため、テーブルや他のセレクトデータをAjaxから入れ替える際は、これをご利用になると便利です。
     * 
     * @param string $caption 説明欄を追加します
     * @param string $ishr <hr>タグを周囲につけるかどうかを設定します（デフォルト: true）
     * @param int $py 空白の高さ幅を変更します（デフォルト: 0）
     * @return void キャプションがオブジェクト内のデータの1番後ろに追加されます。
     */
    function Caption($caption, $ishr = true, $py = 0): void {
	$hr_text = ($ishr) ? '<hr>' : '';
	$py_num = ($py > 5) ? 5 : $py;
	$py_text = ($py_num > 0) ? 'py-' . $py_num : '';
	array_push($this->data, '<div class="form-group-input pt-2">' . $hr_text . '<div class="' . $py_text . '">' . $caption . '</div>' . $hr_text . '</div>');
    }

    /**
     * [SET] キャプション開始（閉じなし）
     * 
     * キャプションを追加します。ただし、Captionとは異なり、div要素を閉じません。<br>
     * 閉じる場合はcloseCaptionを必ず使用してください。<br>
     * タイトルはなく、簡易的なローデータを入れることができます。<br>
     * 
     * @param string $caption 説明欄を追加します（Default: ''）
     * @param int $py 空白の高さ幅を変更します（Default: 0）
     * @return void キャプション（閉じなし）がオブジェクト内のデータの1番後ろに追加されます。
     */
    function OpenCaption($caption = '', $py = 0): void {
	$py_num = ($py > 5) ? 5 : $py;
	$py_text = ($py_num > 0) ? 'py-' . $py_num : '';
	array_push($this->data, '<div class="form-group-input pt-2"><div class="' . $py_text . '">' . $caption);
    }

    /**
     * [SET] キャプション終了
     * 
     * OpenCaptionで開いた要素を閉じます。
     * 
     * @return void divを閉じるデータがオブジェクト内のデータの1番後ろに追加されます。
     */
    function CloseCaption(): void {
	array_push($this->data, '</div></div>');
    }

    /**
     * [SET] 入力フォーム作成
     * 
     * 入力フォーマットを作成します。<br>
     * 必須かどうかを示すテキストに、タイトル（アイコン付き）があり、その下に入力フォーム、さらにその下にその項目に対するヘルプテキストがあります。
     * 
     * @param string $id 入力IDを指定します
     * @param string $desc 説明を加えます
     * @param string $small_desc 下部に小さな説明を加えます
     * @param string $icon アイコン情報です
     * @param bool $required 【任意】入力必要かを入力します（Default: false）
     * @param bool $auto_completed 【任意】補完入力を可能にするか判定します（Default: false）
     * @param string $value 【任意】ユーザが指定した値を代入します
     * 
     * @return void 入力フォームがオブジェクト内のデータの1番後ろに追加されます。
     */
    function Input($id, $desc, $small_desc, $icon, $required = false, $value = ''): void {
	$r_text = ($required) ? '必須' : '任意';
	$r_flag = ($required) ? 'required="required"' : '';
	$v_text = ($value) ? 'value="' . $value . '"' : '';
	array_push($this->data, '<div class="form-group-input pt-2"><label class="importantLabel col-md-3">【' . $r_text . '】</label><label class="formtext col-md-8">' . $desc . '<i class="fas fa-' . $icon . ' fa-2x ml-2"></i></label><input type="text" class="form-control bg-dark my-1 form-control-lg shadow-sm text-monospace" placeholder="Input Here" ' . $r_flag . ' id="' . $id . '" name="' . $id . '" ' . $v_text . '><small class="form-text text-body" id="' . $id . '">' . $small_desc . '</small></div>');
    }

    /**
     * [SET] 数字入力フォーム作成
     * 
     * 入力フォーマットを作成します。<br>
     * 必須かどうかを示すテキストに、タイトル（アイコン付き）があり、その下に入力フォーム、さらにその下にその項目に対するヘルプテキストがあります。
     * 
     * @param string $id 入力IDを指定します
     * @param string $desc 説明を指定します
     * @param string $small_desc 下部に付ける説明を指定します
     * @param string $icon アイコン情報を指定します
     * @param int $min 最小値を指定します
     * @param int $max 最大値を指定します
     * @param bool $required 【任意】入力必要かを入力します（Default: false）
     * @param string $value 【任意】ユーザが指定した値を代入します
     * 
     * @return void 入力フォームがオブジェクト内のデータの1番後ろに追加されます。
     */
    function InputNumber($id, $desc, $small_desc, $icon, $min, $max, $required = false, $value = ''): void {
	$r_text = ($required) ? '必須' : '任意';
	$r_flag = ($required) ? 'required="required"' : '';
	$v_text = ($value) ? 'value="' . $value . '"' : '';
	array_push($this->data, '<div class="form-group-input pt-2"><label class="importantLabel col-md-3">【' . $r_text . '】</label><label class="formtext col-md-8">' . $desc . '<i class="fas fa-' . $icon . ' fa-2x ml-2"></i></label><input type="number" class="form-control bg-dark my-1 form-control-lg shadow-sm text-monospace" placeholder="Input Here" ' . $r_flag . ' id="' . $id . '" name="' . $id . '" ' . $v_text . ' min="' . $min . '" max="' . $max . '" ><small class="form-text text-body" id="' . $id . '">' . $small_desc . '</small></div>');
    }

    /**
     * [SET] フォームタイトル設置
     * 
     * フォームタイトルのみが必要な場合は、ここで作成ができます。
     * 
     * @param string $desc 項目名を指定します
     * @param string $icon アイコンを指定します
     * @param boolean $required 【任意】必須入力かどうかを指定します（Default: true）
     * @return void フォームタイトルがオブジェクト内のデータの1番後ろに追加されます。
     */
    function FormTitle($desc, $icon, $required = true): void {
	$r_text = ($required) ? '必須' : '任意';
	array_push($this->data, "<label class=\"importantLabel col-md-3\">【" . $r_text . "】</label><label class=\"formtext col-md-8\">$desc<i class=\"fas fa-$icon fa-2x ml-2\"></i></label>");
    }

    /**
     * [SET] パスワードフォーム作成
     * 
     * パスワードフォームを作成します。<br>
     * 必須かどうかを示すテキストに、タイトル（アイコン付き）があり、その下に入力フォーム、さらにその下にその項目に対するヘルプテキストがあります。
     * 
     * @param string $id 入力IDを指定します
     * @param string $desc 説明を加えます
     * @param string $small_desc 下部に小さな説明を加えます
     * @param string $auto_focus フォーカスの初期位置をパスワードに設定するかどうかを設定します（Default: false）
     * @param string $value パスワードの指定値（ユーザ指定）を指定します（Default: ''）
     * 
     * @return void パスワードフォームがオブジェクト内のデータの1番後ろに追加されます。
     */
    function Password($id, $desc, $small_desc, $auto_focus = false, $value = ''): void {
	$c_text = ($auto_focus) ? 'autofocus' : '';
	$v_text = ($value) ? 'value="' . $value . '"' : '';
	array_push($this->data, '<div class="form-group-input pt-2"><label class="importantLabel col-md-3">【必須】</label><label class="formtext col-md-8">' . $desc . '<i class="fas fa-key fa-2x ml-2"></i></label><input type="password" class="form-control bg-dark my-1 form-control-lg shadow-sm text-monospace" placeholder="Input Here" ' . $v_text . ' required="required" id="' . $id . '" name="' . $id . '" ' . $c_text . '><span class="field-icon"><i toggle="#password-field" class="fas fa-fw fa-eye toggle-password fa-mod-eye"></i></span><small class="form-text text-body">' . $small_desc . '</small></div>');
    }

    /**
     * [SET] チェックボックス・ラジオボタン作成
     * 
     * チェックボックス・ラジオボタンを作成します。<br>
     * 同じグループにするには、$nameの引数を同じ名前にする必要があります。<br>
     * $typeでは、1の場合のみラジオボックスになります。
     * 
     * @param integer $type チェックタイプを指定します（1...Radio, それ以外...Checkbox）
     * @param string $id IDを指定します
     * @param string $name フォームグループ内の名前を指定します（グループにする場合、名前は統一にする必要があります）
     * @param mixed $value 加える値を指定します
     * @param mixed $outname 表示する名前を指定します
     * @param bool $selected 選択されている状態にするかどうかを指定します
     * @param string $required 【任意】入力必要かを入力します（Default: required）
     */
    function Check($type, $id, $name, $value, $outname, $selected, $required = 'required') {
	$type_text = ($type == 1) ? 'radio' : 'checkbox';
	$class_text = ($type == 1) ? 'radio01' : 'checkbox01';
	$sel_text = ($selected) ? 'checked' : '';
	array_push($this->data, '<input ' . $sel_text . ' required="' . $required . '" id="' . $id . '" type="' . $type_text . '" name="' . $name . '" value="' . $value . '"><label for="' . $id . '" class="' . $class_text . '">' . $outname . '</label><br>');
    }

    /**
     * [SET] ボタン作成
     * 
     * ボタンを作成します。<br>
     * 通常の場合、テキストとアイコンがあるボタンとして作成されます。
     * 
     * @param string $id IDを指定します
     * @param string $desc 表示名を指定します
     * @param string $type ボタンタイプを指定します（Default: submit, button）
     * @param string $icon アイコン情報を指定します（Default: なし）
     * @param string $isdisabled 無効化状態にするか設定します（Default: false）
     * @param int size = 1 サイズを指定します（Default: 1..lg, 0..sm, 2..llg）
     * 
     * @return void ボタンがオブジェクト内のデータの1番後ろに追加されます
     */
    function Button($id, $desc, $type = 'submit', $icon = '', $isdisabled = false, $size = 1): void {
	$fmat = (strpos($icon, 'fab') !== false) ? 'fab' : 'fas';
	$disabled_text = ($isdisabled) ? 'disabled' : '';
	$icon_text = str_replace('fab fa-', '', $icon);
	$size_text = ($size == 1) ? 'lg' : (($size == 2) ? 'llg' : 'sm');
	array_push($this->data, '<button type="' . $type . '" id="' . $id . '" class="btn-vc btn-vc-' . $size_text . '" ' . $disabled_text . '><i class="' . $fmat . ' fa-fw fa-lx fa-' . $icon_text . '"></i>' . $desc . '</button>');
    }

    /**
     * [SET] ボタン作成
     * 
     * アイコンのみのボタンを作成します。<br>
     * この状態では、左下にボタンが追加されます。
     * 
     * @param string $id IDを指定します
     * @param string $text テキストを指定します
     * @param string $type ボタンタイプを指定します（Default: submit, button）
     * @param string $icon アイコン情報を指定します（Default: なし）
     * @param string $isdisabled 無効化状態にするか設定します（Default: false）
     * @param int size = 1 サイズを指定します（Default: 1..lg, 0..sm, 2..llg）
     * 
     * @return void ボタンがオブジェクト内のデータの1番後ろに追加されます
     */
    function ButtonIcon($id, $text, $icon = '', $size = 1): void {
	$fmat = (strpos($icon, 'fab') !== false) ? 'fab' : 'fas';
	$icon_text = str_replace('fab fa-', '', $icon);
	$size_text = ($size == 1) ? 'lg' : (($size == 2) ? 'llg' : 'sm');
	array_push($this->data, '<button type="button" id="' . $id . '" class="left_bottom btn-vc btn-vc-' . $size_text . '"><i class="' . $fmat . ' fa-fw fa-2x fa-' . $icon_text . '"></i><span>' . $text . '</span></button>');
    }

    /**
     * [SET] 中央揃え
     * 
     * \<div class="text-center"\> を作成します。<br>
     * openCenter() 以降の要素を中央揃えにします。<br>
     * ※閉じる場合は必ずcloseDiv()を使用してください。
     * 
     * @return void \<div class="text-center"\>がオブジェクト内のデータの1番後ろに追加されます。
     */
    function openCenter(): void {
	array_push($this->data, '<div class="text-center">');
    }

    /**
     * [SET] リスト開始
     * 
     * \<ul class="black-view"\> を作成します
     * リストを作成します
     * 
     * @return void \<ul class="black-view"\>がオブジェクト内のデータの1番後ろに追加されます。
     */
    function openList(): void {
	array_push($this->data, '<ul class="black-view">');
    }

    /**
     * [SET] リストデータ追加
     * 
     * openList() で作成したリストに対しリストを追加します。
     * 
     * @param string $text リスト内の文字列
     * @return void リストデータがオブジェクト内のデータの1番後ろに追加されます。
     */
    function addList($text, $id = '', $isclicked = false): void {
	$c_text = ($isclicked) ? 'class="list-click" tabindex="0"' : '';
	$id_text = ($id) ? 'id="' . $id . '"' : '';
	array_push($this->data, '<li ' . $c_text . ' ' . $id_text . '>' . $text . '</li>');
    }

    /**
     * [SET] リスト要素追加
     * 
     * openList() で作成したリストに対しリストの要素のみを追加します<br>
     * ただし、要素を閉じることはないため、closeListElemで閉じることを忘れないでください！
     * 
     * @param string $text リスト内の文字列
     * @return void リスト要素がオブジェクト内のデータの1番後ろに追加されます。
     */
    function openListElem($text, $id = '', $isclicked = false): void {
	$c_text = ($isclicked) ? 'class="list-click all-view" tabindex="0"' : 'class="all-view"';
	$id_text = ($id) ? 'id="' . $id . '"' : '';
	array_push($this->data, '<li ' . $c_text . ' ' . $id_text . '><h5>' . $text . '</h5><ul>');
    }

    /**
     * [SET] リスト要素を閉じる
     * 
     * openList() で作成したリストに対し
     * 
     * @return void
     */
    function closeListElem(): void {
	array_push($this->data, '</ul></li>');
    }

    /**
     * [SET] リスト終了
     * 
     * openList() で作成したリストを閉じます。
     * 
     * @return void \</ul\>がオブジェクト内のデータの1番後ろに追加されます。
     */
    function closeList(): void {
	array_push($this->data, '</ul>');
    }

    /**
     * [SET] カード（主背景）作成
     * 
     * カードを黒背景を前提に作成します。
     * 
     * @param string $caption_title カード外タイトルを設定します
     * @param string $icon タイトル横に設置するアイコンを指定します
     * @param string $title カード内サブタイトルを指定します
     * @param string $caption 内容を指定します
     * @return void カード（主背景）がオブジェクト内のデータの1番後ろに追加されます。
     */
    function Card($caption_title, $icon, $title, $caption): void {
	array_push($this->data, '<div class="card mb-1 rounded"><div class="card-header bg-secondary border-bottom border-dark">' . $caption_title . '</div><div class="card-body bg-primary"><h5 class="card-title"><i class="fas fa-fw fa-' . $icon . '"></i>' . $title . '</h5><p class="text-left">' . $caption . '</p></div></div>');
    }

    /**
     * [SET] カード（ダーク）作成
     * 
     * カードを黒背景を前提に作成します。
     * 
     * @param string $caption_title カード外タイトルを設定します
     * @param string $icon タイトル横に設置するアイコンを指定します
     * @param string $title カード内サブタイトルを指定します
     * @param string $caption 内容を指定します
     * @return void カード（ダーク）がオブジェクト内のデータの1番後ろに追加されます。
     */
    function CardDark($caption_title, $icon, $title, $caption): void {
	array_push($this->data, '<div class="card mb-1 rounded"><div class="card-header bg-dark border-bottom border-primary">' . $caption_title . '</div><div class="card-body bg-dark"><h5 class="card-title-dark"><i class="fas fa-fw fa-' . $icon . '"></i>' . $title . '</h5><p class="text-left">' . $caption . '</p></div></div>');
    }

    /**
     * [SET] リストグループ開始
     * 
     * リストグループを作成します。<br>
     * リストグループとは、ボタン群の一種で、クリック時にそのリストのリンク先へ遷移できるようになります。<br>
     * ※閉じる場合は必ずcloseListGroup()を利用してください。
     * 
     * @return void リストグループがオブジェクト内のデータの1番後ろに追加されます。
     */
    function openListGroup(): void {
	array_push($this->data, '<div class="list-group">');
    }

    /**
     * [SET] リストグループデータ追加
     * 
     * リストグループにリストを追加します。
     * 
     * @param string $id このリストに対するIDを指定します
     * @param string $title リストグループのタイトルを指定します
     * @param string $icon リストグループのアイコンを指定します
     * @param string $text リストグループのテキストを追加します
     * @param string $small_text リストグループの小さなテキストを追加します
     * @return void リストグループのリストがオブジェクト内のデータの1番後ろに追加されます。
     */
    function addListGroup($id, $title, $icon, $text, $small_text): void {
	array_push($this->data, '<div tabindex="0" class="list-group-item list-group-item-action flex-column align-items-start active vc-back-card mb-2" id="' . $id . '"><div class="d-flex w-100 justify-content-between"><h5 class="list-group-title"><i class="fas fa-fw fa-' . $icon . ' fa-lg"></i>' . $title . '</h5></div><p class="mb-1">' . $text . '</p> <small>' . $small_text . '</small></div>');
    }

    /**
     * [SET] リストグループ終了
     * 
     * リストグループのデータを閉じます。
     * 
     * @return void \</div\>がオブジェクト内のデータの1番後ろに追加されます。
     */
    function closeListGroup(): void {
	array_push($this->data, "</div>");
    }

    /**
     * [SET] フォームグループ（枠内）作成
     * 
     * form-group-inputでフォームグループとして全体を囲む要素を追加します
     * 
     * @return void div要素がオブジェクト内のデータの1番後ろに追加されます。
     */
    function openFormGroup(): void {
	array_push($this->data, '<div class="form-group-input">');
    }

    /**
     * [SET] フォームグループ（枠内）作成
     * 
     * form-group-inputでフォームグループとして全体を囲む要素を追加します
     * 
     * @return void div要素がオブジェクト内のデータの1番後ろに追加されます。
     */
    function closeFormGroup(): void {
	array_push($this->data, '</div>');
    }

    /**
     * [SET] HTML埋め込み
     * 
     * すでに作成されたHTMLデータをdivタグに閉じ込めた状態で埋め込みます
     * 
     * @param string $html HTMLデータであることがわかる文字列型データです
     * @return void HTMLタグを埋め込めたdivデータがオブジェクト内のデータの1番後ろに追加されます
     */
    function setHTML($html): void {
	array_push($this->data, $html);
    }

    function WarnForm($id): void {
	array_push($this->data, '<div class="form-group-input pt-2"><div id="' . $id . '"></div></div>');
    }

    /**
     * [SET] 境界線
     * 
     * hrタグを用いて表示を区切ります
     * 
     * @param bool $is_primary 主背景色の境界線を使うかどうか（Default: false）
     * @return void hrタグがオブジェクト内のデータの1番後ろに追加されます
     */
    function Horizonal($is_primary = false): void {
	$class_text = ($is_primary) ? 'class="primary"' : '';
	array_push($this->data, '<hr ' . $class_text . '>');
    }

    /**
     * [GET] メイン詳細作成
     * 
     * detailsタグのメイン（第1層）を作成します
     * 
     * @param string $summary_title サマリータイトルを指定します
     * @return void detailsタグがオブジェクト内のデータの1番後ろに追加されます
     */
    function openDetails($summary_title): void {
	array_push($this->data, '<details class="main"><summary class="summary">' . $summary_title . '</summary><div class="details-content">');
    }

    /**
     * [GET] サブ詳細作成
     * 
     * detailsタグのサブ（第2層）を作成します
     * 
     * @param string $summary_title サマリータイトルを指定します
     * @return void detailsタグがオブジェクト内のデータの1番後ろに追加されます
     */
    function openSubDetails($summary_title): void {
	array_push($this->data, '<details class="sub"><summary class="summary-sub">' . $summary_title . '</summary><div class="details-content-sub">');
    }

    /**
     * [GET] 詳細を閉じる
     * 
     * details タグを閉じます
     * 
     * @return void detailsを閉じるタグがオブジェクト内のデータの1番後ろに追加されます
     */
    function closeDetails(): void {
	array_push($this->data, '</div></details>');
    }

    /**
     * [GET] オブジェクトのJavaScriptへのエンコード
     * 
     * プッシュされたすべてのデータを取り出し、後入先出法により文字列化し出力します。
     * JavaScriptへは指定したフォームIDで変数を作り、かつページ内のヘッダー部に定義します。
     * 
     * @return string フォームクラス内で作成されたデータを文字列として返します。
     */
    public function Export($is_form = true): string {
	array_push($this->data, '</form>');
	array_push($this->data, '</div></div>');
	$text = '';
	foreach ($this->data as $var) {
	    if ($is_form || preg_match('/^.*(formtext).*$/', $var) || !preg_match('/^.*(form|\/form).*$/', $var)) {
		$text .= $var;
	    }
	}
	return $text;
    }

    /**
     * [SET] オブジェクトデータリセット
     * 
     * オブジェクト内のデータを初期化します<br>
     * コンストラクタも同様の処理を行います
     * 
     * @param string $id フォームグループの一意なIDを指定します（Default: fm_pg）
     * @param string $data 埋め込み用HTML
     * @param int $color_flag 背景色のフラグを設定します（Default: 0..主背景, 1..主背景）
     */
    public function reset($id = 'fm_pg', $data = '', $color_flag = 0): void {
	if ($data != '') {
	    $this->data = [$data];
	} else {
	    $this->data = ['<form id="' . $id . '" action="" method="POST">'];
	    $this->BackGround($color_flag);
	}
	$this->id = $id;
	array_push(self::$gen_data, $this);
    }

    /**
     * [SET] 背景色設定・変更
     * 
     * @param type $color_code カラー番号を指定します（0..主背景, 1..黒背景）
     * @param bool $isclose 全背景セクションを閉じるかどうかを指定します（Default: false）<br>変更時は必ずtrueにします。
     */
    public function BackGround($color_code, $isclose = false) {
	$color_text = '';
	switch ($color_code) {
	    case 0:
		$color_text = 'vc-back';
		break;
	    case 1:
		$color_code = 'bg-dark';
		break;
	}
	if ($isclose) {
	    array_push($this->data, '</div></div>');
	}
	array_push($this->data, '<div class="' . $color_text . '"><div class="container py-2">');
    }

    /**
     * [SET] 最後尾のデータを削除
     * 
     * 最後尾データ（最後に追加したデータ）を削除します
     */
    public function removeBack() {
	array_pop($this->data);
    }

    /**
     * [SET] データのリセット（スタック履歴）
     * 
     * 今まで作成したオブジェクトデータの履歴を消去し、スタック内にオブジェクトがないものにします。
     * 
     * @return void スタック内のオブジェクトが消去されます。
     */
    public static function resetData(): void {
	self::$gen_data = [];
    }

    /**
     * [GET] フォームID取得
     * 
     * フォームIDを取得します。
     * 
     * @return string そのオブジェクトのフォームIDを取得します。
     */
    function getID(): string {
	return $this->id;
    }

    /**
     * [GET] ページ内全体ページオブジェクトエンコード
     * 
     * 今まで作成したページをJavaScript上で操作できやすい形にします。<br>
     * これにより、今まで作成したページの「form_id」をもとにJavaScript上の動的な変数を作成します。<br>
     * 変数をanimation() , animation_to_sites() にバインドすることで、画面遷移を実現します。<br>
     * なお、ここではスタック内にある全てのページオブジェクトに対して行われます。<br>
     * 
     * @return string JavaScriptでエンコードされたものが文字列として返されます。
     */
    static function ExportClass(): string {
	fm_ld('fm_ld');
	$js = '<script type="text/javascript">';
	foreach (self::$gen_data as $f) {
	    $js .= 'var ' . $f->getID() . ' = \'' . $f->Export() . '\';';
	}
	$js .= '</script>';
	return $js;
    }

    /**
     * [FUNCTION] 認証画面作成
     * 
     * 認証が必要となる場合、その画面を作成します。<br>
     * ユーザIDの入力フォームとして、「in_at_ps」を提供します。<br>
     * また、ボタンにてキャンセルボタン「bt_at_bk」、送信ボタン「bt_at_sb」を提供します。
     * 
     * @return string 作成されたデータをそのままページHTMLとして返します
     */
    public function fm_at(): string {
	$userdata = session_get_userdata();
	if (!$userdata) {
	    $userdata['USERNAME'] = '[USER]';
	}
	$this->SubTitle('この操作を行うには認証が必要です', $userdata['USERNAME'] . ' さんのパスワードを入力してください。', 'passport');
	$this->Password('in_at_ps', 'パスワード', 'あなたのパスワードを入力します。', true);
	$this->WarnForm('fm_warn');
	$this->Button('bt_at_sb', '送信する', 'submit', 'upload');
	$this->Button('bt_at_bk', 'キャンセル', 'button', 'caret-square-left');
    }

    /**
     * [SET] リスト作成
     * 
     * ulグループによるリストを順序配列もしくは連想配列を渡すことにより動的に作成します
     * 
     * @param array $a_data データ配列を指定します（string, integerなどの単独系変数は指定不可能）
     * @return string ulグループタグがオブジェクト内のデータの1番後ろに追加されます
     */
    public function ListCreate($a_data) {
	if (is_array($a_data)) {
	    $this->openList();
	    foreach ($a_data as $d) {
		$this->addList($d);
	    }
	    $this->closeList();
	} else {
	    $this->Caption('表示するログはありません');
	}
    }

    /**
     * [FUNCTION] ローディング画面作成
     * 
     * ローディング画面を作成します。
     * 
     * @param string $title ローディング中に出すタイトル部分です
     * @param string $text ローディング中に出すテキスト部分です
     * @return \form_generator 作成したform_generatorオブジェクトとして返します
     */
    function fm_ld($title = '更新反映中です...', $text = '反映されるまで、ページを変えずにしばらくお待ちください...') {
	$this->SubTitle($title, $text, 'circle-notch fa-spin');
    }

    /**
     * [FUNCTION] 失敗画面作成
     * 
     * 失敗画面を作成します。
     * 
     * @param $log ログ詳細を配列で渡します
     * @param $button ボタン（1つ）を追加する
     * @param string $title 失敗画面でのタイトルを指定する
     * @param string $text その原因となるテキスト部分を指定する
     */
    function fm_fl($log, $button, $title = 'エラーが発生しました', $text = '以下のエラーをご確認ください') {
	$this->SubTitle($title, $text, 'exclamation-triangle');
	$this->ListCreate($log);
	$this->Button($button[0], $button[1], $button[2], $button[3]);
    }

}

/**
 * [FUNCTION] ローディング画面作成
 * 
 * ローディング画面を作成する
 * 
 * @param string $id フォームに与えるIDを指定する
 * @param string $title ローディング中に出すタイトル部分である
 * @param string $text ローディング中に出すテキスト部分である
 * @return \Former 作成したFormerオブジェクトとして返す
 */
function fm_ld($id, $title = '更新反映中です...', $text = '反映されるまで、ページを変えずにしばらくお待ちください...') {
    $fm = new Former($id, '');
    $fm->SubTitle($title, $text, 'circle-notch fa-spin');
    return $fm;
}
