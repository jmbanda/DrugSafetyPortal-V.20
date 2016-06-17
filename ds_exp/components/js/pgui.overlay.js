define(function(require, exports) {

    require('jquery.plainoverlay');

    exports.showOverlay = function(image, text) {

        $('body').plainOverlay('show', {
            progress: function () {
                return $(
                    '<div class="pgui-overlay">' +
                        '<div class="comment">' + text + '</div>' +
                    '</div>'
                );
            }
        });

    };

    exports.hideOverlay = function() {
        $('body').plainOverlay('hide');
    };

    window.overlay = exports;

});