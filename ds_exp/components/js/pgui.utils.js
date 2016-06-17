define(function(require, exports) {

    require('jquery');
    require('bootbox.min');
    var sprintf = require("libs/sprintf").sprintf;

    exports.showInfoMessage = function(message) {
        _showBootBoxAlert(_buildMessage(message, 'info'));
    };

    exports.showSuccessMessage = function(message) {
        _showBootBoxAlert(_buildMessage(message, 'success'));
    };

    exports.showWarningMessage = function(message) {
        _showBootBoxAlert(_buildMessage(message, 'warning'));
    };

    exports.showErrorMessage = function(message) {
        _showBootBoxAlert(_buildMessage(message, 'danger'));
    };

    var showMessage = exports.showMessage = function(message) {
        _showBootBoxAlert(message);
    };

    function _buildMessage(message, alertClass) {
        return sprintf('<div class="alert alert-%s"', alertClass) +  'role="alert" style="margin-bottom: 0">' +
            '<p>' + message + '</p>' +
            '</div>'
    }

    function _showBootBoxAlert(messageToDisplay) {
        bootbox.alert({
            closeButton: false,
            message: messageToDisplay
        });
    }

    exports.updatePopupHints = function ($container) {
        $container.find('.js-more-hint').each(function () {
            var $hintLink = $(this);
            var $hintMessage = $hintLink.siblings('.js-more-box').html();
            $hintLink
                .on('click', function() {
                    $(this).popover('hide');
                    showMessage($hintMessage);
                    return false;
                })
                .popover({
                    title: '',
                    placement: function () {
                        if ($hintLink.offset().top - $(window).scrollTop() < $(window).height() / 2)
                            return 'bottom';
                        else
                            return 'top';
                    },
                    html: true,
                    trigger: 'hover',
                    content: $hintMessage
                });
        });
    };

    /**
     *
     * @param $checkBox jQuery
     * @param state string "checked" | "unchecked" | "indeterminate"
     */
    function _setCheckBoxState ($checkBox, state) {
        if (state === "checked") {
            $checkBox.prop("indeterminate", false);
            $checkBox.prop('checked', true);
        }
        else if (state === "unchecked") {
            $checkBox.prop("indeterminate", false);
            $checkBox.prop('checked', false);

        }
        else if (state === "indeterminate") {
            $checkBox.prop('checked', false);
            $checkBox.prop("indeterminate", true);
        }
    }

    exports.setCheckBoxStateOn = function($checkBox) {
        _setCheckBoxState($checkBox, "checked");
    };

    exports.setCheckBoxStateOff = function($checkBox) {
        _setCheckBoxState($checkBox, "unchecked");
    };

    exports.setCheckBoxStateIndeterminate = function($checkBox) {
        _setCheckBoxState($checkBox, "indeterminate");
    };

});
