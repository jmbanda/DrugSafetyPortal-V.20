define(function(require, exports) {

    var async = require('async'),
        editors = require('pgui.editors');

    exports.initEditors = function(container, callback) {

        callback = callback || function() {};

        async.parallel([

            function(callback) {

                async.forEach(container.find('[data-editor-class=HtmlEditor]').get(), function(item, callback) {
                    var editorContainer = $(item);
                    require(['pgui.wysiwyg'], function(w) {
                        var editor = new w.WYSISYGEditor(editorContainer);
                        var htmlEdit = new editors.HtmlEditor(editorContainer);
                        htmlEdit.updateState();
                        callback();
                    });
                }, callback);
            },

            function(callback) {
                async.forEach(container.find('input[masked=true]').get(), function(item, callback) {
                    var maskEdit = $(item);
                    require(["jquery.maskedinput"], function()
                    {
                        if (maskEdit.attr('mask') != '')
                            maskEdit.mask(maskEdit.attr('mask'));
                        callback();
                    });
                }, callback);
            },

            function(callback) {

                async.forEach(container.find('div.btn-group[data-toggle-name]').get(), function(item, callback) {
                    var group   = $(item);
                    var form    = group.parents('form').eq(0);
                    var name    = group.attr('data-toggle-name');
                    var hidden  = $('input[name="' + name + '"]', form);

                    group.on('click', 'button', function () {
                        group.find('button').removeClass('active');
                        var $button = $(this).addClass('active');
                        hidden.val($button.val());
                    });

                    group.find('button[value=' + hidden.val() + ']').addClass('active');

                    callback();
                }, callback);
            },


            function(callback) {

                async.forEach(container.find('.field-options').get(), function(item, callback) {
                    var optionsPanel = $(item);

                    optionsPanel.find('.set-default.btn').mouseup(function() {
                        var button = $(this);
                        setTimeout(function() {
                            optionsPanel.find('.set-default-input').val(button.hasClass('active') ? '1' : '0');
                        }, 0);
                    });

                    optionsPanel.find('.set-to-null.btn').mouseup(function() {
                        var button = $(this);
                        setTimeout(function() {
                            optionsPanel.find('.set-to-null-input').val(button.hasClass('active') ? '1' : '0');
                        }, 0);
                    });
                    callback();
                }, callback);
            },

            // DateTime and Time editors
            function(callback) {

                async.forEach(container.find('[data-editor-class=DateTimeEdit],[data-editor-class=TimeEdit]').get(), function(item, callback) {
                    var $editor = $(item);
                    require(['pgui.datetimepicker'], function(dtp) {
                        new dtp.DateTimePicker($editor);
                        callback();
                    });
                }, callback)

            },

            // Combobox
            function(callback) {
                async.forEach(container.find('[data-editor-class=ComboBox]').get(), function(item, callback) {
                    var comboBox = new editors.ComboBoxEditor($(item));
                    comboBox.updateState();
                    callback();
                }, callback)

            },

            // Hide invisible editors
            function(callback) {

                async.forEach(container.find('[data-editor=true][data-editor-visible=false]').get(), function(item, callback) {
                    var $editor = $(item);
                    $editor.closest('.form-group').hide();
                    callback();
                }, callback)

            },

            function(callback) { callback(); }
        ],
        callback);

    };


    exports.destroyEditors = function(container, callback) {

        callback = callback || function() {};

        async.parallel([

            function(callback) {
                async.forEach(container.find('[data-editor-class=HtmlEditor]').get(), function(item, callback) {
                    var editorContainer = $(item);
                    require(['pgui.wysiwyg'], function(w) {
                        if (editorContainer.data('pgui-class'))
                            editorContainer.data('pgui-class').destroy();
                        callback();
                    });
                }, callback);
            }

        ],callback);

    };

    });