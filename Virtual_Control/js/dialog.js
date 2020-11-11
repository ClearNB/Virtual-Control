/* [Dialog Click Event Functions]
 * Open & Close Events are defined.
 * Coded by Project GSC.
 */

function generate_yesorno_dialog($id, $content_id, $content, $oktext) {
    generate_empty_dialog($id, $content_id, $content);
    $('#' + $id).dialog({
        autoOpen: false,
        resizable: false,
        width: "50%",
        closeOnEscape: false,
        modal: true,
        show: {
            effect: "fade",
            duration: 800
        },
        hide: {
            effect: "fade",
            duration: 800
        },
        open: function () {
            $(".ui-dialog-titlebar-close").hide();
        },
        buttons: [
            {
                text: $oktext,
                class: 'btn btn-block btn-lg btn-primary active',
                click: function () {
                    $(this).dialog('close');
                }
            },
            {
                text: 'キャンセル',
                class: 'btn btn-block btn-lg btn-primary active',
                click: function () {
                    $(this).dialog('close');
                }
            }
        ]
    });
    $('#' + $id).parent().css({position: "fixed"}).end().dialog('open');
}

function generate_ok_dialog($id, $content_id, $content, $canceltext) {
    generate_empty_dialog($id, $content_id, $content);
    $('#' + $id).dialog({
        autoOpen: false,
        resizable: false,
        width: "80%",
        closeOnEscape: true,
        modal: true,
        show: {
            effect: "fade",
            duration: 800
        },
        hide: {
            effect: "fade",
            duration: 800
        },
        open: function () {
            $(".ui-dialog-titlebar-close").hide();
        },
        buttons: [
            {
                text: $canceltext,
                class: 'btn btn-block btn-lg btn-primary active',
                click: function () {
                    $(this).dialog('close');
                }
            }
        ],
        close: function() {
            $(this).remove();
        }
    });
    $('#' + $id).parent().css({position: "fixed"}).end().dialog('open');
}