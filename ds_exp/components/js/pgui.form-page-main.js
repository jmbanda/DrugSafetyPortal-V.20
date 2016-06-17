define(function(require, exports) {

    var controls    = require('pgui.controls'),
        pv          = require('pgui.validation'),
        forms       = require('pgui.forms'),
        autoHideMessage = require('pgui.autohide-message');

    $(function() {
        var $form = $('.js-pgui-edit-form');
        $form.pgui_validate_form();

        var isInsert = $form.data('type') == 'insert';
        var FormClass = isInsert ? forms.InsertForm : forms.EditForm;
        controls.initEditors($('body'), function(err) {
            var form = new FormClass($form);
        });

        $form.submit(function(e) {
            _toggleButtonsLoading($form, true);
            if (!pv.ValidateSimpleForm($form, $form.find('.error-container'), isInsert)) {
                _toggleButtonsLoading($form, false);
                e.preventDefault();
            }
        });

        $form.find('.save-and-open-details').click(function(e) {
            e.preventDefault();
            $('form').attr('action', $(this).attr('data-action'));
            $('#submit-button').click();
        });

        $form.find('.dropdown-toggle').dropdown();

        var saveClickHandler = function () {
            $('#submit-action').val($(this).data('value'));
            $('#submit-button').click();
        }

        $form.find('a.save-button').click(saveClickHandler);
        $form.find('a.saveinsert-button').click(saveClickHandler);
        $form.find('a.saveedit-button').click(saveClickHandler);

        var $input = $form.find(".form-control").get(0)
        if ($input) {
            $input.focus();
        }

        autoHideMessage($form.find('.alert').first());
    });

    function _toggleButtonsLoading($form, isLoading) {
        $form.find(".btn-toolbar button").prop('disabled', isLoading);
        var classMethod = isLoading ? 'addClass' : 'removeClass';
        $form.find(".btn-toolbar button[type=submit],submit")[classMethod]('btn-loading');
    }
});