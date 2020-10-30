/* 2020/10/28 Coded.
 * [Project GSC] Form Generator v 1.0.0
 * For Input Form, Generate the Input Form.
 * [Items]
 * > Input(id, desc, small_desc, required)
 */
class form_generator {

    constructor(id, ActionPHP) {
        this.data = ['<form id="' + id + '" action="' + ActionPHP + '" method="POST">'];
    }

    /* [Document]
     * @param {String} $title
     * @param {String} $icon
     * @returns {undefined}
     */
    Title($title, $icon) {
        /* Replacement
         * [DESC] ... $title
         * [ICON] ... $icon
         */
        var text = '<div class="form-group pt-2">'
                + '<div class="col-md-12"><h1><i class="fa fa-ICON fa-fw"></i>DESC</h1></div>'
                + ' </div>';
        text = text.replace("DESC", $title)
                .replace("ICON", $icon);
        this.data.push(text);
    }

    /* [Document]
     * @param {String} $id
     * @param {String} $desc
     * @param {String} $small_desc
     * @param {String} $icon
     * @param {bool} $required
     * @returns {undefined}
     */
    Input($id, $desc, $small_desc, $icon, $required) {
        /* Replacement
         * [ID]            ... $id
         * [REQUIRED]      ... '必須' or '任意'
         * [DESC]          ... $desc
         * [ICON]          ... $icon
         * [SMALL_DESC]    ... $small_desc
         * [REQUIRED_FORM] ... 'required="required"' or ''
         */
        var text = '<div class="form-group pt-2"> <label class="importantLabel col-md-2">【REQUIRED】</label><label class="formtext col-md-9">DESC<i class="fa fa-ICON fa-lx"></i></label><input type="text" class="form-control bg-dark my-1 form-control-lg shadow-sm text-monospace" placeholder="Input Here" R_FORM id="ID" name="ID"'
        + '><small class="form-text text-body">SMALL_DC</small></div>';
        text = text.replace(/ID/g, $id)
                .replace(/DESC/g, $desc)
                .replace("ICON", $icon)
                .replace("SMALL_DC", $small_desc);
        if ($required) {
            text = text.replace("REQUIRED", "必須")
                    .replace("R_FORM", "required=\"required\"");
        } else {
            text = text.replace("REQUIRED", "任意")
                    .replace("R_FORM", "");
        }
        this.data.push(text);
    }

    /* [Documents]
     * @param {String} $id
     * @param {String} $desc
     * @returns {undefined}
     */
    Button($id, $desc) {
        /* Replacement
         * [ID]   ... $id
         * [DESC] ... $desc
         */
        var text = '<input type="submit" id="ID" class="btn btn-dark btn-block btn-lg shadow-lg mb-2" value="DESC" />';
        text = text.replace("ID", $id)
                .replace("DESC", $desc);
        this.data.push(text);
    }

    Export() {
        this.data.push("</form>");
        var size = this.data.length;
        var text = '';
        for (var i = 0; i < size; i++) {
            text += this.data.shift();
        }
        document.getElementById('former').innerHTML = text;
    }
}