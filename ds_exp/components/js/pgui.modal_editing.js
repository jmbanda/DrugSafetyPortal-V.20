define(function(require, exports) {

    require('bootbox.min');
    var localizer = require('pgui.localizer');
    var utils = require('pgui.utils');

    exports.setupModalEditors = function(context, grid) {
        if (!context) {
            context = $(document);
        }

        context
            .off('click', 'a[data-modal-operation=delete]')
            .on('click', 'a[data-modal-operation=delete]', function (event) {
                var $button = $(this);
                event.preventDefault();
                bootbox.confirm(localizer.getString('DeleteRecordQuestion'), function(confirmed) {
                    if (!confirmed) {
                        return;
                    }

                    var url = $button.attr('href');
                    var handlerName = $button.attr('data-delete-handler-name');

                    $.ajax({
                        url: url + "&hname=" + handlerName,
                        data: {},
                        success: function (data) {
                            var response = $(data).find('response');
                            if (response.find('type').text() == 'error') {
                                utils.showErrorMessage(response.find('error_message').text());
                            } else {
                                var rowToDelete = $button.closest('.pg-row');
                                if (grid) {
                                    grid.removeRow(rowToDelete);
                                }

                                var message = response.find('message').html();
                                var displayTime = response.find('message_display_time').text();
                                if (message) {
                                    grid.showMessage(message, displayTime);
                                }
                            }
                        },
                        dataType: 'xml',
                        error: function () {
                        }
                    });
                });
            });
    }
});

