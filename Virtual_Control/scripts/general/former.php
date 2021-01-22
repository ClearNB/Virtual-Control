<?php

/**
 * [INTERFACE] FORM_IN
 * 
 * フォーム内容の設定の際に用いる定数を定義します。
 * 
 * @package VirtualControl_scripts_general
 * @author ClearNB<clear.navy.blue.star@gmail.com>
 */
interface form_in {

    /**
     * 幅・高さが無いことを示します。
     * 
     * @var int NONE */
    const NONE = 0;

    /**
     * 幅・高さの倍率が1（0.1rem）程度であることを示します。
     * 
     * @var int LOW */
    const LOW = 1;

    /**
     * 幅・高さの倍率が3（0.5rem）程度であることを示します。
     * 
     * @var int MEDIUM */
    const MEDIUM = 3;

    /**
     * 幅・高さの倍率が5（1.0rem）程度であることを示します。
     * 
     * @var int HIGH
     */
    const HIGH = 5;

}

/**
 * [CLASS] form_generator
 * 
 * <h4>FormGenerator v1.2.0</h4><hr>
 * FormGenerator(Former)は、HTMLコードをPHP上で定義したメソッドを用いて、ページ構成をオブジェクト単位で作成するクラスです。<br>
 * 設定機能はもちろんのこと、作成したページを読み込む際にJavaScript方式にエンコードする機能も備わっています。
 * 
 * @package VirtualControl_scripts_general
 * @author ClearNB <clear.navy.blue.star@gmail.com>
 */
class form_generator implements form_in {

    /**
     * オブジェクト作成時に自動的にプッシュされる順序配列です
     * 
     * @var array $gen_data */
    private static $gen_data = [];

    /**
     * <SELECT>使用時の取り込みデータです
     * 
     * @var string $select_data  */
    private $select_data;

    /**
     * <SELECT>使用時の取り込みデータです
     * 
     * @var string $fdata */
    private $fdata;

    /**
     * フォームデータであり、配列で格納されています
     * 
     * @var array $data */
    private $data;

    /**
     * フォームデータに対する識別用のIDです
     * @var string $id */
    private $id;

    /**
     * [METHOD] コンストラクタ
     * 
     * Form Generatorのコンストラクタです。<br>
     * POST通信をベースとしたフォームを作ります。<br>
     * ここでは\<form\>の内容をを定義します。
     * 
     * @param string $id フォームグループの一意なIDを指定します。
     * @param string $data 【任意】アクション先の外部ファイル。通常空白
     * @param integer $color_flag 【任意】背景のフラグを設定します（1..主背景, 2..黒背景）
     */
    function __construct($id, $data = '', $color_flag = 1) {
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
	array_push($this->data, "<div class=\"form-group pt-2\"><div class=\"w-100\"><h2><i class=\"fas fa-$icon fa-fw\"></i>$title</h2></div></div>");
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
     * @param bool $darkBack	    黒背景を変えるかどうかを指定します（Default: false）
     * @param string $badgetext	    タイトル横にある背景付きのテキストを表示させます（Default: ''）
     * 
     * @return void サブタイトルがオブジェクト内のデータの1番後ろに追加されます。
     */
    function SubTitle($title, $caption, $icon, $darkBack = false, $badgetext = ''): void {
	$back_text = '';
	$b_text = '';
	if ($darkBack) {
	    $back_text = 'class = "orange"';
	}
	if ($badgetext) {
	    if ($darkBack) {
		$b_text = '<span class = "badge-primary badge-pill">' . $badgetext . '</span>';
	    } else {
		$b_text = '<span class = "badge-dark badge-pill">' . $badgetext . '</span>';
	    }
	}
	array_push($this->data, "<div class=\"form-group pt-2\"><div class=\"w-100\"><h3><i class=\"fas fa-$icon fa-fw\"></i>$title $b_text</h3><hr $back_text><p class=\"py-2\">$caption</p></div></div>");
    }

    /**
     * [SET] キャプション作成
     * 
     * キャプションを追加します。<br>
     * タイトルはなく、簡易的なローデータを入れることができます。<br>
     * \<div\>で囲まれるため、テーブルや他のセレクトデータをAjaxから入れ替える際は、これをご利用になると便利です。
     * 
     * @param string $caption 説明欄を追加します
     * @param string $ishr <hr>タグを周囲につけるかどうかを設定します（デフォルト: false）
     * @param int $py 空白の高さ幅を変更します（デフォルト: 0）
     * @return void キャプションがオブジェクト内のデータの1番後ろに追加されます。
     */
    function Caption($caption, $ishr = true, $py = form_in::NONE): void {
	$hr_text = ($ishr) ? '<hr>' : '';
	$py_num = ($py > 5) ? 5 : $py;
	$py_text = ($py_num > 0) ? 'py-' . $py_num : '';
	array_push($this->data, '<div class="form-group-input pt-2">' . $hr_text . '<div class="' . $py_text . '">' . $caption . '</div>' . $hr_text . '</div>');
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
     * @param string $pattern 【任意】パターン入力オプションです（Default: null）
     * 
     * @return void 入力フォームがオブジェクト内のデータの1番後ろに追加されます。
     */
    function Input($id, $desc, $small_desc, $icon, $required = false, $auto_completed = false, $pattern = ''): void {
	$r_text = "任意";
	$r_flag = "";
	$p_text = "";
	if ($required) {
	    $r_text = "必須";
	    $r_flag = "required=\"required\"";
	}

	if ($auto_completed) {
	    $r_flag .= " autocomplete=\"on\"";
	} else {
	    $r_flag .= " autocomplete=\"off\"";
	}
	if ($pattern) {
	    $p_text = " pattern=\"$pattern\"";
	}
	array_push($this->data, "<div class=\"form-group-input pt-2\"><label class=\"importantLabel col-md-3\">【" . $r_text . "】</label><label class=\"formtext col-md-8\">$desc<i class=\"fas fa-$icon fa-2x ml-2\"></i></label><input type=\"text\" class=\"form-control bg-dark my-1 form-control-lg shadow-sm text-monospace\" placeholder=\"Input Here\" $r_flag id=\"$id\" name=\"$id\" $p_text><small class=\"form-text text-body\" id=\"$id-label\">$small_desc</small></div>");
    }

    /**
     * [SET] 数字入力フォーム作成
     * 
     * 入力フォーマットを作成します。<br>
     * 必須かどうかを示すテキストに、タイトル（アイコン付き）があり、その下に入力フォーム、さらにその下にその項目に対するヘルプテキストがあります。
     * 
     * @param string $id 入力IDを指定します
     * @param string $desc 説明を指定します
     * @param string $small_text 下部に付ける説明を指定します
     * @param string $icon アイコン情報を指定します
     * @param int $min 最小値を指定します
     * @param int $max 最大値を指定します
     * @param bool $required 【任意】入力必要かを入力します（Default: false）
     * @param bool $auto_completed 【任意】補完入力を可能にするか判定します（Default: false）
     * 
     * @return void 入力フォームがオブジェクト内のデータの1番後ろに追加されます。
     */
    function InputNumber($id, $desc, $small_text, $icon, $min, $max, $required = false, $auto_completed = false): void {
	$r_text = "任意";
	$r_flag = "";
	$p_text = "";
	if ($required) {
	    $r_text = "必須";
	    $r_flag = "required=\"required\"";
	}
	if ($auto_completed) {
	    $r_flag .= " autocomplete=\"on\"";
	} else {
	    $r_flag .= " autocomplete=\"off\"";
	}
	array_push($this->data,
		"<div class=\"form-group-input pt-2\">"
		. "<label class=\"importantLabel col-md-3\">【" . $r_text . "】</label>"
		. "<label class=\"formtext col-md-8\">$desc<i class=\"fas fa-$icon fa-2x ml-2\"></i></label>"
		. "<input type=\"number\" class=\"form-control bg-dark my-1 form-control-lg shadow-sm text-monospace number-text\" placeholder=\"Input Here\" $r_flag id=\"$id\" name=\"$id\" $p_text min=\"$min\" max=\"$max\">"
		. "<small class=\"form-text text-body\" id=\"$id-label\">$small_text</small>"
		. "</div>");
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
     * @param string $icon アイコン情報です
     * @param bool $required 【任意】入力必要かを入力します（Default: true）
     * @param bool $auto_completed 【任意】補完入力を可能にするか判定します（Default: false）
     * @param bool $eye_modify 【任意】表示用ボタンを表示させます（Default: true）
     * 
     * @return void パスワードフォームがオブジェクト内のデータの1番後ろに追加されます。
     */
    function Password($id, $desc, $small_desc, $icon, $required = true, $auto_completed = false, $eye_modify = true): void {
	$r_text = ($required) ? '必須' : '任意';
	$r_set = ($required) ? 'required="required"' : '';
	$m_text = ($eye_modify) ? '<span class="field-icon"><i toggle="#password-field" class="fas fa-fw fa-eye toggle-password fa-mod-eye"></i></span>' : '';
	$c_text = ($auto_completed) ? 'autocomplete="on"' : 'autocomplete="off"';
	array_push($this->data, "<div class=\"form-group-input pt-2\"><label class=\"importantLabel col-md-3\">【" . $r_text . "】</label><label class=\"formtext col-md-8\">$desc<i class=\"fas fa-$icon fa-2x ml-2\"></i></label><input type=\"password\" class=\"form-control bg-dark my-1 form-control-lg shadow-sm text-monospace\" placeholder=\"Input Here\" $r_set $c_text id=\"$id\" name=\"$id\">$m_text<small class=\"form-text text-body\">$small_desc</small></div>");
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
	$class_text = ($type == 1) ? 'radio02' : 'checkbox02';
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
     * @param string $type ボタンタイプを指定します（Default: submit）<br>【submit】フォーム送信処理を行う場合のみに使用<br>【button】普段の「ボタン」としての役目を持つ際に使用
     * @param string $icon アイコン情報を指定します（Default: なし）
     * @param string $isdisabled 【任意】無効化状態にするか設定します（Default: false）
     * 
     * @return void ボタンがオブジェクト内のデータの1番後ろに追加されます
     */
    function Button($id, $desc, $type = 'submit', $icon = '', $isdisabled = false): void {
	$fmat = (strpos($icon, 'fab') !== false) ? 'fab' : 'fas';
	$d_text = ($isdisabled) ? 'disabled' : '';
	$icon_r = str_replace('fab fa-', '', $icon);
	array_push($this->data, "<div class=\"py-1\"><button type=\"$type\" id=\"$id\" class=\"btn btn-dark btn-block btn-lg shadow-lg mb-1\" $d_text><i class=\"$fmat fa-fw fa-lx fa-$icon_r\"></i>$desc</button></div>");
    }

    /**
     * [SET] 特大ボタン作成
     * 
     * 通常より大きいボタンを作成します。<br>
     * 通常の場合、テキストとアイコンがあるボタンとして作成されます。
     * 
     * @param string $id IDを指定します
     * @param string $desc 表示名を指定します
     * @param string $type ボタンタイプを指定します（Default: submit）<br>【submit】フォーム送信処理を行う場合のみに使用<br>【button】普段の「ボタン」としての役目を持つ際に使用
     * @param string $icon アイコン情報を指定します（Default: なし）
     * @param string $isdisabled 【任意】無効化状態にするか設定します（Default: false）
     * 
     * @return void ボタンがオブジェクト内のデータの1番後ろに追加されます
     */
    function LargeButton($id, $desc, $type = 'submit', $icon = '', $isdisabled = false): void {
	$fmat = (strpos($icon, 'fab') !== false) ? 'fab' : 'fas';
	$d_text = ($isdisabled) ? 'disabled' : '';
	$icon_r = str_replace('fab fa-', '', $icon);
	array_push($this->data, "<div class=\"py-1\"><button type=\"$type\" id=\"$id\" class=\"btn btn-dark btn-block btn-llg shadow-lg mb-1\" $d_text><i class=\"$fmat fa-fw fa-lx fa-$icon_r\"></i>$desc</button></div>");
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
	array_push($this->data, "<div class=\"text-center\">");
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
    function addList($text): void {
	array_push($this->data, '<li>' . $text . '</li>');
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
     * [SET] 選択リスト開始
     * 
     * \<select\> 属性を開きます。<br>
     * ドロップダウン方式となります。
     * 
     * @param string $name フォームグループ名を指定します
     * @param string $place_name プレースホルダの表示名を設定します(Default: '選択する')
     * @return void 選択リストがオブジェクト内のデータの1番後ろに追加されます。
     */
    function openSelect($name, $place_name = '選択する'): void {
	$this->select_data = ["TEXT" => [], "VALUE" => []];
	$this->fdata = '<div class="sel">'
		. '<span class="sel__placeholder sel__placeholder--blackpanther" data-placeholder="' . $place_name . '">' . $place_name . '</span>'
		. '<div class="sel__box sel__box--black-panther">[OUTNAME_DATA]</div>'
		. '<select name="' . $name . '" id="' . $name . '">[VALUE_DATA]</select>'
		. '</div>';
    }

    /**
     * [SET] 選択リストデータ追加
     * 
     * openSelect() で作成したリストに対し要素を追加します。
     * 
     * @param mixed $value 実際に格納する値を指定します
     * @param string $outname 実際に表示される文字を指定します
     * @return void 選択リストデータがオブジェクト内のデータの1番後ろに追加されます。
     */
    function addOption($value, $outname): void {
	array_push($this->select_data['VALUE'], '<option value="' . $value . '">' . $outname . '</option>');
	array_push($this->select_data['TEXT'], '<span class="sel__box__options sel__box__options--black-panther">' . $outname . '</span>');
    }

    /**
     * [SET] 選択リスト終了
     * 
     * openSelect() で作成したリストを閉じます。
     * 
     * @return void 閉じた後のリスト（完成）がオブジェクト内のデータの1番後ろに追加されます。
     */
    function closeSelect(): void {
	$text_data = implode('', $this->select_data['TEXT']);
	$value_data = implode('', $this->select_data['VALUE']);
	$r_data = str_replace('[VALUE_DATA]', $value_data, str_replace('[OUTNAME_DATA]', $text_data, $this->fdata));
	array_push($this->data, $r_data);
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
	array_push($this->data, '<div tabindex="0" class="list-group-item list-group-item-action flex-column align-items-start active list-group-item-dark mb-2" id="' . $id . '"><div class="d-flex w-100 justify-content-between"><h5 class="list-group-title"><i class="fas fa-fw fa-' . $icon . ' fa-lg"></i>' . $title . '</h5></div><p class="mb-1">' . $text . '</p> <small>' . $small_text . '</small></div>');
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
	array_push($this->data, '<div class="form-group-input pt-2"><div id="' . $id . '" ></div></div>');
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
	$class_text = ($is_primary) ? 'class="orange"' : '';
	array_push($this->data, '<hr ' . $class_text . '>');
    }

    /**
     * [GET] オブジェクトのJavaScriptへのエンコード
     * 
     * プッシュされたすべてのデータを取り出し、後入先出法により文字列化し出力します。
     * JavaScriptへは指定したフォームIDで変数を作り、かつページ内のヘッダー部に定義します。
     * 
     * @return string フォームクラス内で作成されたデータを文字列として返します。
     */
    public function Export(): string {
	array_push($this->data, '</form></div></div>');
	$text = '';
	foreach ($this->data as $var) {
	    $text .= $var;
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
     * @param int $color_flag 背景色のフラグを設定します（Default: 1..主背景, 2..黒背景）
     */
    public function reset($id = 'fm_pg', $data = '', $color_flag = 1): void {
	if ($data != '') {
	    $this->data = [$data];
	} else {
	    if ($color_flag == 1) {
		$this->data = ["<div class=\"bg-primary\"><div class=\"container py-2\"><form id=\"$id\" action=\"\" method=\"POST\">"];
	    } else if ($color_flag == 2) {
		$this->data = ["<div class=\"bg-dark\"><div class=\"container py-2\"><form id=\"$id\" action=\"\" method=\"POST\">"];
	    }
	}
	$this->id = $id;
	array_push(self::$gen_data, $this);
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
	if(!$userdata) {
	    $userdata['USERNAME'] = '[USER]';
	}
	$this->SubTitle('この操作を行うには認証が必要です', $userdata['USERNAME'] . ' さんのパスワードを入力してください。', 'passport');
	$this->Password('in_at_ps', 'パスワード', 'あなたのパスワードを入力します。', 'key');
	$this->WarnForm('fm_warn');
	$this->Button('bt_at_sb', '送信する', 'submit', 'upload');
	$this->Button('bt_at_bk', 'キャンセル', 'button', 'caret-square-left');
	return $this->Export();
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
	if(is_array($a_data)) {
	    $this->openList();
	    foreach($a_data as $d) {
		$this->addList($d);
	    }
	    $this->closeList();
	} else {
	    $this->Caption('表示するログはありません');
	}
    }

}

/**
 * [FUNCTION] ローディング画面作成
 * 
 * ローディング画面を作成します。
 * 
 * @param string $id フォームに与えるIDを指定します
 * @param string $title ローディング中に出すタイトル部分です
 * @param string $text ローディング中に出すテキスト部分です
 * @return \form_generator 作成したform_generatorオブジェクトとして返します
 */
function fm_ld($id, $title = '更新反映中です...', $text = '反映されるまで、ページを変えずにしばらくお待ちください...') {
    $fm = new form_generator($id, '');
    $fm->SubTitle($title, $text, 'circle-notch fa-spin');
    return $fm;
}

/**
 * [FUNCTION] 失敗画面作成
 * 
 * 失敗画面を作成します。
 * 
 * @param string $id フォームに与えるIDを指定します
 * @param string $title 失敗画面でのタイトルを指定します
 * @param string $text その原因となるテキスト部分を指定します
 * @return \form_generator 作成したform_generatorオブジェクトとして返します
 */
function fm_fl($id, $title = '失敗しました', $text = '[原因]') {
    $fm = new form_generator($id, '');
    $fm->SubTitle($title, $text, 'exclamation-triangle');
    return $fm;
}

/**
 * [FUNCTION] 認証画面作成
 * 
 * 認証が必要となる場合、その画面を作成します。<br>
 * ユーザIDの入力フォームとして、「in_at_ps」を提供します。<br>
 * また、ボタンにてキャンセルボタン「bt_at_bk」、送信ボタン「bt_at_sb」を提供します。
 * 
 * @param string $id 【任意】フォームに割り当てられるIDです（Default: fm_at）
 * @param string $username 【任意】表示するユーザネームを指定します（Default: [USER]）
 * @return \form_generator 作成したform_generatorオブジェクトとして返します
 */
function fm_at($id = 'fm_at', $username = ''): form_generator {
    $fm_ag = new form_generator($id);
    $fm_ag->SubTitle('この操作を行うには認証が必要です', $username . ' さんのパスワードを入力してください。', 'passport');
    $fm_ag->Password('in_at_ps', 'パスワード', 'あなたのパスワードを入力します。', 'key');
    $fm_ag->WarnForm('fm_warn');
    $fm_ag->Button('bt_at_sb', '送信する', 'submit', 'upload');
    $fm_ag->Button('bt_at_bk', 'キャンセル', 'button', 'caret-square-left');
    return $fm_ag;
}
