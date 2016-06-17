define(function (require, exports) {

    var PlainEditor = require('pgui.editors/plain').PlainEditor;

    exports.ImageUploaderEditor = PlainEditor.extend({
        doChanged: function() {
            var name = $(this.rootElement).data('field-name');
            $('#' + name + '-replace-image-button').click();
            this._super();
        }
    });
});