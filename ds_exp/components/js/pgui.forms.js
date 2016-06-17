define(function(require, exports) {

    var Class = require('class'),
        shortcuts = require('pgui.shortcuts'),
        editors = require('pgui.editors');

    var Form = exports.Form = Class.extend({
        init: function($container, disableShortcuts) {
            this.$container = $container;

            if (!disableShortcuts) {
                if ($container.hasClass('modal')) {
                    $container
                        .one('shown.bs.modal', function () {
                            shortcuts.push(['form']);
                        })
                        .one('hidden.bs.modal', function () {
                            shortcuts.pop();
                        });
                } else {
                    shortcuts.push(['form']);
                }
            }
        }
    });

    exports.EditForm = Form.extend({
        init: function($container, disableShortcuts) {
            this._super($container, disableShortcuts);
            editors.InitEditorsController(editors.DataOperation.Edit, this.$container);
        }
    });

    exports.InsertForm = Form.extend({
        init: function($container, disableShortcuts) {
            this._super($container, disableShortcuts);
            editors.InitEditorsController(editors.DataOperation.Insert, this.$container);
        }
    });

});