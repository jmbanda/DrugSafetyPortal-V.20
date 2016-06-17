define(function(require, exports, module)
{
    var Class       = require('class'),
        pv          = require('pgui.validation'),
        _           = require('underscore'),
        autoHideMessage = require('pgui.autohide-message');

    function destroyDialog(formContainer) {
        formContainer.modal('hide');
    }

    function destroyEditors(formContainer) {
        require(['pgui.controls'], function (ctrls) {
            ctrls.destroyEditors(formContainer, function () {
                formContainer.remove();
            });
        });
    }

    exports.ModalOperationLink = Class.extend({
        init: function(container, parentGrid)
        {
            this.parentGrid = parentGrid;
            this.container = container;
            this.contentLink = container.data('content-link');
            this.$row = this.container.closest('.pg-row');
            var self = this;
            this.container.click(function(event)
                {
                    event.preventDefault();
                    self._invokeModalDialog();
                });
        },

        _doOkCreateButton: function(container, formContainer, errorContainer)
        {
            return null;
        },

        _doValidateForm: function(form)
        {
            return null;
        },

        _doUpdateGridAfterCommit: function(response)
        {
            return null;
        },

        _invokeModalDialog: function(){
            $.get(this.contentLink, {},
                _.bind(function(data) {
                    this._showModalDialog($(data));
                }, this));
        },

        _bindButtonEvents: function($formContainer, errorContainer) {
            $formContainer.find('.dropdown-toggle').dropdown();

            $formContainer.find('.cancel-button').click(function(e) {
                e.preventDefault();
                destroyDialog($formContainer);
            });

            var self = this;
            $formContainer.find('.save-and-open-details').click(function (e) {
                e.preventDefault();
                var $link = $(this);
                self._processCommit($formContainer, errorContainer, function () {
                    location.href = $link.data('action');
                });
            });
        },

        _showModalDialog: function(content)
        {
            var self = this;
            require(['pgui.controls'], function(ctrls) {

                /**
                 * Container for form
                 * @type {*|jQuery|HTMLElement}
                 */
                var formContainer = $('#modalFormContainer');
                if(formContainer.length === 0){
                    formContainer = $('<div/>', {
                        class: 'modal wide-modal fade',
                        id: 'modalFormContainer',
                        tabIndex: '-1'
                    })
                        .appendTo($('body'))
                        .append(content);
                }

                formContainer.find('form').on('submit', function (e) {
                    e.preventDefault();
                });

                self._applyFormValidator(formContainer, errorContainer);
                self._applyUnobtrusive(formContainer);

                var errorContainer = self._createErrorContainer(formContainer);

                ctrls.initEditors(formContainer, function() {
                    self._bindButtonEvents(formContainer, errorContainer);
                    formContainer.modal();
                    formContainer.on('hidden.bs.modal', function () {
                        destroyEditors(formContainer);
                    });
                });
            });

        },

        _createButtons: function(dialog, formContainer, errorContainer)
        {
            var uiDialogButtonPane = $('<div></div>')
                    .addClass('ui-dialog-buttonpane')
                    .addClass('ui-widget-content')
                    .addClass('ui-helper-clearfix');

            var uiButtonSet = $( "<div></div>" )
                    .addClass( "ui-dialog-buttonset" )
                    .appendTo( uiDialogButtonPane );

            var cancelButtonBlock = $('<div></div>').css('float', 'right').appendTo(uiButtonSet);

            var cancelButton =
                    $('<button type="button">Cancel</button>')
                            .click(function() { dialog.dialog('close'); })
                            .appendTo(cancelButtonBlock);
            cancelButton.button();

            var saveButtonBlock = $('<div></div>');
            saveButtonBlock.addClass('drop-down-list-margin-fix-wrapper');

            var saveButtonElement = this._doOkCreateButton(saveButtonBlock, formContainer, errorContainer);

            saveButtonBlock.appendTo(uiButtonSet);

            dialog.dialog('widget').append(uiDialogButtonPane);
            dialog.dialog('widget').css('overflow', 'visible');

            //var saveButton = new PhpGen.DropDownButton(saveButtonElement);
        },

        _applyUnobtrusive: function(formContainer)
        {
            //controls.initEditors(formContainer);
        },

        _createErrorContainer: function(formContainer)
        {
            /*var errorContainer = $('<ul class="modal-editing-error-box">');
            formContainer.append(errorContainer);
            errorContainer.hide();
            return errorContainer;*/
            return formContainer.find('.error-container');
        },

        _applyFormValidator: function(formContainer, errorContainer)
        {
            formContainer.find('form').pgui_validate_form();
        },

        _toggleLoading: function (formContainer, isLoading) {
            var $toolbar = formContainer.find('.btn-toolbar');
            var $submitButtons = $toolbar.find("button[type=submit],submit");

            $toolbar.find("button").prop('disabled', isLoading);

            if (isLoading) {
                $submitButtons.addClass('btn-loading');
            } else {
                $submitButtons.removeClass('btn-loading');
            }
        },

        _beforeFormSubmit: function(formContainer, errorContainer)
        {
            var form = formContainer.find("form");
            this._toggleLoading(formContainer, true);

            var result = pv.ValidateSimpleForm(form, errorContainer, false);

            if (!result) {
                this._toggleLoading(formContainer, false);
            }

            return result;
        },

        _showError: function(formContainer, message, displayTime)
        {
            var $errorContainer = formContainer.find('.error-container');
            var $errorMessage =
                $('<div class="alert alert-danger">')
                    .appendTo($errorContainer);
            $errorMessage.html(message);
            $errorMessage.prepend(
                $('<button class="close" type="button" data-dismiss="alert">&times;</button>')
            );

            autoHideMessage($errorMessage, displayTime);
        },

        _processCommit: function(formContainer, errorContainer, success)
        {
            var self = this;
            var form = formContainer.find("form");

            require(['jquery.form'], _.bind(function()
            {
                form.ajaxSubmit(
                {
                    dataType: 'xml',

                    beforeSubmit: function () {
                        if (!self._beforeFormSubmit(formContainer, errorContainer)) {
                            return false;
                        }

                        $("body").addClass("cursor-wait");
                    },

                    success: function (response) {
                        var displayTime = $(response).find('message_display_time').text();

                        if ($(response).find('type').text() == 'error') {
                            self._showError(formContainer, $(response).find('error_message').text(), displayTime);
                        } else {
                            var newRow = self._doUpdateGridAfterCommit(response);
                            formContainer.one('hidden.bs.modal', function() {
                                success(newRow);
                            });
                            destroyDialog(formContainer);

                            var message = $(response).find('message').html();
                            if (message) {
                                self.parentGrid.showMessage(message, displayTime);
                            }
                        }

                        $("body").removeClass("cursor-wait");

                        self._toggleLoading(formContainer, false);
                    },

                    error: function () {
                        self._toggleLoading(formContainer, false);
                    }

                });
            }, this));

        }
    });
});