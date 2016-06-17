define(function (require) {

    require('jquery');
    var utils = require('pgui.utils');

    function extractXmlDocumentFromFrame(frame) {
        var doc = frame.contentWindow ?
            frame.contentWindow.document :
            frame.contentDocument ?
                frame.contentDocument :
                frame.document;

        if (doc == null)
            return null;
        if (doc.XMLDocument)
            return $(doc.XMLDocument);
        else
            return $(doc);
    }

    $.fn.sm_inline_grid_edit = function (a_options) {
        var _this = this;
        var pguiValidation;
        require(['pgui.validation'], function (instance) {
            pguiValidation = instance;
        });
        var defaults = {
            row: null,
            debug: false,
            editControlsContainer: '[data-column-name=InlineEdit],[data-column-name=edit]',
            initEditControl: '.js-inline_edit_init',
            otherControl: '.operation-column > [data-column-name!="InlineEdit"]',
            cancelEditControl: '.js-inline_edit_cancel',
            commitEditControl: '.js-inline_edit_commit',
            cancelInsertControl: '.js-inline_insert_cancel',
            commitInsertControl: '.js-inline_insert_commit',
            requestAddress: '',
            useBlockGUI: true,
            inlineAddControl: '.inline_add_button',
            cancelButtonHint: 'Cancel',
            commitButtonHint: 'Commit',
            useImagesForActions: true,
            newRecordRowTemplate: '.js-new-record-row[data-new-row=false]',
            newRecordAfterRowTemplate: '.new-record-after-row[data-new-row=false]',
            responseCheckCount: 200
        };

        var options = $.extend(defaults, a_options);
        var grid;

        function ClearErrorRow() {
            var row = $('td.grid_error_row').closest('.pg-row');
            row.remove();
        }

        function SetupEditControls(editControlsContainer) {
            var initEditControl = editControlsContainer.find(options.initEditControl);
            initEditControl.click(InlineEditHandler);

            var cancelControl = editControlsContainer.find(options.cancelEditControl);
            cancelControl.click(CancelEditingClickHandler);

            var commitControl = editControlsContainer.find(options.commitEditControl);
            commitControl.click(commitEditHandler);

            editControlsContainer.closest('.pg-row')
                .off('click', 'a[inline-copy=true]')
                .on('click', 'a[inline-copy=true]', InlineCopyHandler);

            HideCompleteEditControls(editControlsContainer);
        }

        function ShowInitEditControls(editControlsContainer) {
            editControlsContainer.find(options.initEditControl).show();
            ShowOtherControls(editControlsContainer);
        }

        function HideInitEditControls(editControlsContainer) {
            editControlsContainer.find(options.initEditControl).hide();
            HideOtherControls(editControlsContainer);
        }

        function ShowOtherControls(editControlsContainer) {
            editControlsContainer.closest('.pg-row')
                .find(options.otherControl).show();
        }

        function HideOtherControls(editControlsContainer) {
            editControlsContainer.closest('.pg-row')
                .find(options.otherControl).hide();
        }

        function HideCompleteEditControls(editControlsContainer) {
            editControlsContainer.find(options.cancelEditControl).hide();
            editControlsContainer.find(options.commitEditControl).hide();
        }

        function ShowCompleteEditControls(editControlsContainer) {
            editControlsContainer.find(options.cancelEditControl).show();
            editControlsContainer.find(options.commitEditControl).show();
        }

        function commitBaseHandler(commitControl, action) {
            var row = commitControl.closest('.pg-row');
            var editControlsContainer = row.find(options.editControlsContainer);
            commit(row, editControlsContainer, action);
        }

        function commitEditHandler(event) {
            event.preventDefault();
            commitBaseHandler($(this), 'edit');
        }

        function commitInsertHandler(event) {
            event.preventDefault();
            commitBaseHandler($(this), 'insert');
        }

        function CompleteEditing(editControlsContainer) {
            ShowInitEditControls(editControlsContainer);
            HideCompleteEditControls(editControlsContainer);
            DestroyValidationErrorContainer(editControlsContainer);
            ClearErrorRow();

            require(['pgui.controls'], function (ctrls) {
                ctrls.destroyEditors(editControlsContainer);
            });

        }

        function ReturnOldHtmlForCell(dataCell) {
            var oldHtml = dataCell.children('div.phpgen-ui-inline-edit-cell-old-data').html();
            dataCell.html(oldHtml);
        }

        function EmbedEditorFromXml(row, editorXmlElement, isInsert) {
            var editorHtml = editorXmlElement.find('html').text();
            var editorScript = editorXmlElement.find('script').text();
            var editorFieldName = editorXmlElement.attr('name');

            var dataCell = row.find('[data-column-name="' + editorFieldName + '"]');
            dataCell.attr('data-inline-editing', 'true');

            var inlineEditorContainer = $('<div>');
            inlineEditorContainer.addClass('inline_editor_container');
            var form = $('<form>').addClass('inline-edit-editor-form');
            var idRandom = Math.floor(Math.random() * 100000);
            var formId = 'inline-edit-editor-form_' + idRandom;
            form.attr('id', formId);

            var $controlGroup = $('<div>');
            $controlGroup.addClass('form-group');
            $controlGroup.attr('data-parent-form-id', idRandom);
            $controlGroup.append(editorHtml);

            form.append($controlGroup);
            inlineEditorContainer.append(form);

            var oldHtmlContainer = $('<div>');
            oldHtmlContainer.addClass('phpgen-ui-inline-edit-cell-old-data');
            oldHtmlContainer.css('display', 'none');
            oldHtmlContainer.append(dataCell.html());
            dataCell.html('');
            dataCell.append(inlineEditorContainer);
            dataCell.append(oldHtmlContainer);

            var $editControlsContainer = row.find(options.editControlsContainer);
            form.on('submit', isInsert ? commitInsertHandler : commitEditHandler);

            try {
                eval(editorScript);
            }
            catch (e) {
            }
        }

        function CancelInlineEditing(currentRow, editControlsContainer) {
            currentRow.find('[data-inline-editing=true]').each(function () {
                var dataCell = $(this);
                ReturnOldHtmlForCell(dataCell);
            });
            CompleteEditing(editControlsContainer);
        }

        function blockControls($row) {
            var $col = $row.find("[data-column-name=InlineEdit]");
            $col.children().hide();
            $col.append('<img class="js-block-indicator" src="components/assets/img/loading.gif" style="width:16px;" />');
        }

        function unblockControls($row) {
            var $col = $row.find("[data-column-name=InlineEdit]");
            $col.find('.js-block-indicator').remove();
            $col.children().show();
        }

        function SubmitFormWithTarget(form, target) {
            form.submit(function () {
                form.attr('target', target);
            });
            form.submit();
        }

        /**
         * Validate inline row controls
         * @param row
         * @returns {boolean}
         */
        function checkValidness(row) {
            var isAllControlsValid = true;
            row.find('form').each(function (index, form) {
                if (!$(form).valid())
                    isAllControlsValid = false;
            });
            return isAllControlsValid;
        }


        function GetEditorsNameSuffix(responseXml) {
            return $(responseXml).find('namesuffix').text();
        }

        function CreateInlineInsertingControls(editControlsContainer) {
            editControlsContainer.find(options.cancelInsertControl).click(CancelInsertHandler);
            editControlsContainer.find(options.commitInsertControl).click(commitInsertHandler);
        }

        function DeleteInlineInsertingControls(editControlsContainer) {
            var inlineInsertControls = editControlsContainer.find('span[data-content=inline_insert_controls]');
            inlineInsertControls.remove();
        }

        function CancelEditingClickHandler(event) {
            event.preventDefault();

            var cancelControl = $(this);
            var $row = cancelControl.closest('.pg-row');
            var editControlsContainer = cancelControl.closest(options.editControlsContainer);
            clearRowError($row);
            CancelInlineEditing($row, editControlsContainer);
        }


        function CancelInsertHandler(event) {
            event.preventDefault();

            var $row = $(this).closest('.pg-row');
            clearRowError($row);
            $row.remove();
        }


        function CreateFormForPostInsertInlineEditors() {
            var postForm = $('<form>');
            postForm.attr('id', 'inline_edit_form');
            postForm.attr('enctype', 'multipart/form-data');
            postForm.attr('action', options.requestAddress);
            postForm.attr('method', 'POST');
            postForm.css('display', 'none');

            postForm.append('<input type="hidden" name="operation" value="arqiic" />');
            return postForm;
        }

        /**
         *
         * @returns {*|jQuery|HTMLElement}
         * @constructor
         */
        function CreateFormForPostInlineEditors() {
            var postForm = $('<form>');
            postForm.attr('id', 'inline_edit_form');
            postForm.attr('enctype', 'multipart/form-data');
            postForm.attr('action', options.requestAddress);
            postForm.attr('method', 'POST');
            postForm.css('display', 'none');

            postForm.append('<input type="hidden" name="operation" value="arqiec" />');
            return postForm;
        }

        function InlineAddHandler() {
            var templateRow = grid.find('.pg-row-list:first').children(options.newRecordRowTemplate).first();
            var row = templateRow.clone();

            row.attr('data-new-row', 'true');
            templateRow.before(row);

            var editControlsContainer = row.find(options.editControlsContainer);

            var requestData = {};
            requestData['operation'] = 'arqii';

            $.get(
                options.requestAddress,
                requestData,
                function ready(data) {
                    row.removeClass('hidden');
                    CreateInlineInsertingControls(editControlsContainer);

                    var nameSuffixInput = $('<input name="namesuffix" type="hidden">');
                    nameSuffixInput.val(GetEditorsNameSuffix(data));

                    editControlsContainer.append(nameSuffixInput);

                    $(data).find('editor').each(function () {
                        EmbedEditorFromXml(row, $(this), true);
                    });

                    require(['pgui.controls'], function (ctrls) {
                        ctrls.initEditors(grid);
                    });

                    require(['pgui.forms'], function (forms) {
                        new forms.InsertForm(grid, true);
                    });

                    CreateValidationErrorContainer(row, editControlsContainer);
                    enableValidation(row);
                }
            );
        }

        function InlineEditHandler(event) {
            event.preventDefault();

            var row = $(this).closest('.pg-row');
            var editControlsContainer = $(this).closest(options.editControlsContainer);

            blockControls(row);

            var requestData = {};
            editControlsContainer.find('input[type=hidden]').each(function () {
                requestData[$(this).attr('name')] = $(this).val();
            });
            requestData['operation'] = 'arqie';

            $.get(
                options.requestAddress,
                requestData,
                function ready(data) {
                    HideInitEditControls(editControlsContainer);
                    ShowCompleteEditControls(editControlsContainer);

                    var nameSuffixInput = $('<input name="namesuffix" type="hidden">');
                    nameSuffixInput.val(GetEditorsNameSuffix(data));
                    editControlsContainer.append(nameSuffixInput);

                    $(data).find('editor').each(function () {
                        try {
                            EmbedEditorFromXml(row, $(this), false);
                        }
                        catch (e) {
                            alert(e);
                        }
                    });

                    require(['pgui.controls'], function (ctrls) {
                        ctrls.initEditors(grid);
                    });

                    require(['pgui.forms'], function (forms) {
                        new forms.EditForm(grid);
                    });

                    CreateValidationErrorContainer(row, editControlsContainer);
                    enableValidation(row);

                    unblockControls(row);
                }
            );
        }

        function InlineCopyHandler(event) {
            event.preventDefault();

            var templateRow = grid.find('.pg-row-list').first().find(options.newRecordRowTemplate).first();
            var row = templateRow.clone();

            row.attr('data-new-row', 'true');
            templateRow.after(row);

            var editControlsContainer = row.find(options.editControlsContainer);
            var originalEditControlsContainer = $(this).closest('.pg-row').find(options.editControlsContainer);

            blockControls(row);

            var requestData = {};
            originalEditControlsContainer.find('input[type=hidden]').each(function () {
                requestData[$(this).attr('name')] = $(this).val();
            });
            requestData['operation'] = 'arqie';

            $.get(
                options.requestAddress,
                requestData,
                function ready(data) {
                    row.removeClass('hidden');
                    CreateInlineInsertingControls(editControlsContainer);

                    var nameSuffixInput = $('<input name="namesuffix" type="hidden">');
                    nameSuffixInput.val(GetEditorsNameSuffix(data));

                    editControlsContainer.append(nameSuffixInput);

                    $(data).find('editor').each(function () {
                        EmbedEditorFromXml(row, $(this), true);
                    });

                    require(['pgui.forms'], function (forms) {
                        new forms.InsertForm(grid, true);
                    });

                    CreateValidationErrorContainer(row, editControlsContainer);
                    enableValidation(row);
                    unblockControls(row);
                }
            );
        }

        function enableValidation(row) {
            /**
             * @var {string} errorMessageTitle The title message of validation error popover box
             */
            var errorMessageTitle;

            /**
             * @function unHighlightHandler Handler to override pgui_validate_form unhighlight method via config
             * @param {HTMLElement} element
             */
            function unHighlightHandler(element) {
                /**
                 * @var {jQuery} Wrapped element
                 */
                var $elementToValidate;
                $elementToValidate = $(element);
                $elementToValidate.closest('.form-group').removeClass('has-error');
                $elementToValidate.popover('destroy');
            }

            errorMessageTitle = 'Validation error';

            $(row).find('form').pgui_validate_form(
                {
                    validate_errorClass: 'inline-edit-error',
                    validate_errorPlacement: function (error, element) {
                        if (error.text()) {
                            element.closest('.form-group').popover({
                                container: 'body',
                                trigger: 'hover',
                                placement: 'bottom',
                                title: errorMessageTitle,
                                content: error.text()
                            });
                        }
                    },
                    validate_success: undefined,
                    highlight: function (element, errorClass, validClass) {
                        /**
                         * @var {jQuery} Wrapped element
                         */
                        var $elementToValidate;
                        unHighlightHandler(element);
                        $elementToValidate = $(element);

                        /**
                         * This is not a solution. Some times appending error to class to closest control-group is just not enough.
                         * For example typeahead.
                         * @TODO Refactor
                         */
                        $elementToValidate.closest('.form-group').addClass('has-error');
                    },
                    unhighlight: unHighlightHandler
                });
        }

        function DestroyValidationErrorContainer(editControlsContainer) {
            $(editControlsContainer.closest('.pg-row').attr('error-container')).remove();
        }

        function CreateValidationErrorContainer(row, editControlsContainer) {
            var errorContainerId = 'error-box' + Math.floor(Math.random() * 100000);
            row.attr('error-container', '#' + errorContainerId);

            var errorBox = $("<ul>");

            errorBox.addClass('inline-editing-error-box');
            errorBox.attr('id', errorContainerId);
            errorBox.css('position', 'absolute');

            $('body').append(errorBox);


            errorBox.offset({
                top: editControlsContainer.offset().top + editControlsContainer.outerHeight(),
                left: editControlsContainer.offset().left
            });

        }

        function construct() {
            grid = $(_this);

            var editControlsContainers;

            if (options.row != null) {
                editControlsContainers = options.row.find(options.editControlsContainer);
            } else {
                grid.find(options.inlineAddControl).click(InlineAddHandler);
                editControlsContainers = grid.find('.pg-row').find(options.editControlsContainer);
            }

            editControlsContainers.each(function () {
                SetupEditControls($(this));
            });

            return _this;
        }

        /**
         * @TODO This method should be refactored to SRP. It has too many tasks to do
         * @function commit Commit inline data
         * @param {jQuery} row
         * @param {jQuery} editControlsContainer
         * @param {string} commitOperationTypeName
         * @throws {Error}
         */
        function commit(row, editControlsContainer, commitOperationTypeName) {

            /**
             * Current committing row
             * @type {jQuery}
             */
            var currentRow = row;

            /**
             * Validate each field in row and
             * If one of row fields is invalid then there is nothing else to do return
             */
            if (!checkValidness(currentRow)) return;

            clearRowError(currentRow);

            /**
             * Form level validation
             * @type {Object}
             */
            var legacyValidateForm = pguiValidation.Grid_ValidateForm(currentRow, commitOperationTypeName === 'insert');
            if (!legacyValidateForm.valid) {
                addRowError(currentRow, legacyValidateForm.message);
                return;
            }

            /**
             * Handlers for post form creation operation
             * @type {{edit: CreateFormForPostInlineEditors, insert: CreateFormForPostInsertInlineEditors}}
             */
            var postOperationMakeFormHandlers = {
                'edit': CreateFormForPostInlineEditors,
                'insert': CreateFormForPostInsertInlineEditors
            };

            /**
             * Make new form for post to the server
             * @type {jQuery}
             */
            var postForm = postOperationMakeFormHandlers[commitOperationTypeName]();

            /**
             * The edit and insert operations controls selectors
             * @type {{}}
             */
            var commitOperationControlsSelectors = {
                edit: 'input:hidden',
                insert: 'input[type=hidden]'
            };

            /**
             * append fields to the post form
             */
            postForm.append(editControlsContainer.find(commitOperationControlsSelectors[commitOperationTypeName]).clone());

            require(['pgui.controls'], function (ctrls) {
                ctrls.destroyEditors(currentRow);
            });

            var $inlineEditorContainers = currentRow.find('div.form-group');
            $inlineEditorContainers.detach().appendTo(postForm);

            function moveInlineEditorContainersToTheirParentForms() {
                $inlineEditorContainers.each(function () {
                    var formId = $(this).attr('data-parent-form-id');
                    var $parentForm = $('#inline-edit-editor-form_' + formId);
                    $(this).detach().appendTo($parentForm);
                });
            }

            var operationRandom = Math.floor(Math.random() * 100000);

            /**
             * Iframe
             * @type {*|jQuery|HTMLElement}
             */
            var $resultFrame = $('<iframe/>', {
                id: 'inlineEditPostForm_' + operationRandom,
                name: 'inlineEditPostForm',
                src: 'about:black'
            });

            /**
             * The result frame css styles
             * @type {{width: string, height: string, border: string}}
             */
            var resultFrameCss = {
                width: '0px',
                height: '0px',
                border: 'opx',
                margin: '0px',
                padding: '0px'
            };

            if (options.debug) {
                resultFrameCss.width = '1000px';
                resultFrameCss.height = '800px';
                resultFrameCss.border = '2px';
            }

            $resultFrame.css(resultFrameCss);

            /**
             * Entire body object
             * @type {*|jQuery|HTMLElement}
             */
            var $body = $('body');

            $body.append(postForm);
            $body.append($resultFrame);

            blockControls(currentRow);

            /**
             * @type {number}
             */
            var domCheckCount = options.responseCheckCount;

            function processResponse() {

                var io = $resultFrame[0];

                /**
                 * Some sort of a edit form
                 * @type {*|jQuery|HTMLElement}
                 */
                var $inline_edit_form = $('#inline_edit_form');

                try {
                    var doc = io.contentWindow ? io.contentWindow.document : (io.contentDocument ? io.contentDocument : io.document);
                    var isXml = doc.XMLDocument || $.isXMLDoc(doc);
                }
                catch (e) {
                    isXml = false;
                }

                if (!isXml) {
                    if (--domCheckCount) {
                        setTimeout(processResponse, 250);
                        return;
                    }

                    if (!options.debug)
                        setInterval(function () {
                            $('#inlineEditPostForm_' + operationRandom).remove();
                        }, 1000);


                    $inline_edit_form.remove();

                    if (commitOperationTypeName === 'edit') {
                        currentRow.find('[data-inline-editing=true]').each(function () {
                            var dataCell = $(this);
                            ReturnOldHtmlForCell(dataCell);
                        });
                        CompleteEditing(editControlsContainer);
                    } else if (commitOperationTypeName === 'insert') {
                        currentRow.next().remove();
                        currentRow.remove();
                    }

                    utils.showErrorMessage('Response is not available');

                    if (commitOperationTypeName === 'insert') {
                        DestroyValidationErrorContainer(editControlsContainer);
                    }

                    unblockControls(currentRow);

                    return;
                }

                var responseXml = extractXmlDocumentFromFrame($resultFrame[0]);

                if (responseXml.find('errormessage').length > 0) {

                    utils.showMessage(responseXml.find('errormessage').text());

                    moveInlineEditorContainersToTheirParentForms();

                    require(['pgui.controls'], function (ctrls) {
                        ctrls.initEditors(grid);
                    });
                } else {

                    if (commitOperationTypeName === 'edit') {
                        CompleteEditing(editControlsContainer);
                    } else if (commitOperationTypeName === 'insert') {
                        grid.find('.emplygrid').remove(); // TODO: Не нашел, где это используется?
                        DeleteInlineInsertingControls(editControlsContainer);
                    }

                    var message = responseXml.find('message').html();
                    var displayTime = responseXml.find('message_display_time').text();
                    if (message) {
                        options.grid.showMessage(message, displayTime);
                    }

                    responseXml.find('fieldvalue').each(function () {

                        var newValue = $(this).find('value').text();

                        if (commitOperationTypeName === 'edit') {
                            var afterRow = $(this).find('afterrowcontrol').text();
                            currentRow.next().find('[data-column-name]').append(afterRow);
                        }

                        var style = $(this).find('style');
                        var dataCell = currentRow.find('[data-column-name="' + $(this).attr('name') + '"]:first');
                        dataCell.attr('data-inline-editing', 'false');

                        if (style)
                            dataCell.attr('style', style.text());

                        dataCell.html('');
                        dataCell.append(newValue);

                        if (commitOperationTypeName === 'edit') {
                            if (dataCell.is('[data-column-name=InlineEdit]')) {
                                dataCell.closest('table').sm_inline_grid_edit( // TODO: Change 'table' to card compatible format
                                    {
                                        row: currentRow,
                                        useBlockGUI: true,
                                        requestAddress: options.requestAddress,
                                        grid: options.grid
                                    });
                            }
                        }


                    });

                    if (commitOperationTypeName === 'insert') {
                        currentRow.removeAttr('id');
                        currentRow.removeClass('js-new-record-row');
                        SetupEditControls(editControlsContainer);
                    }

                    options.grid.integrateRows(currentRow);
                }

                unblockControls(currentRow);

                if (!options.debug)
                    setInterval(function () {
                        $('#inlineEditPostForm_' + operationRandom).remove();
                    }, 1000);
                $inline_edit_form.remove();
            }

            /**
             * Something strange going on here
             * @TODO figure it out and refactor
             */
            setTimeout(function () {
                SubmitFormWithTarget(postForm, 'inlineEditPostForm');
            }, 100);

            setTimeout(processResponse, 250);

        }

        /**
         * @function clearRowError Clear row error messages
         * @param {jQuery} $row Table row
         */
        function clearRowError($row) {
            $row.removeClass('error');
            $row.popover('destroy');
            $('.popover').remove();
        }

        /**
         * @function Add row error messages
         * @param {jQuery} $row Table row
         * @param  {string} errorMessage Message to fill in to popover content
         */
        function addRowError($row, errorMessage) {
            $row.removeData('bs.popover');
            $row.popover({
                trigger: 'manual',
                placement: 'bottom',
                title: 'Validation error',
                content: errorMessage
            });
            $row.popover('show');
            $row.addClass('error');
        }

        return construct();
    };

});