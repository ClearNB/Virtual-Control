/* [Dialog Click Event Functions]
 * Open & Close Events are defined.
 * Coded by Project GSC.
 */

(function () {
    const close = document.getElementById('close');
    const dialog = document.getElementById('dialog');

    close.addEventListener('click', function () {
        dialog.close();
    });

    dialog.addEventListener('click', function (event) {
        if (event.target === dialog) {
            dialog.close('cancelled');
        }
    });
}());
