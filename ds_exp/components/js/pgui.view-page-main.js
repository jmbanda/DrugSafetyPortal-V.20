define(function(require, exports, module) {

    require('jquery');
    var showFieldEmbeddedVideo = require('pgui.field-embedded-video');

    var $body = $('body');
    require(['pgui.utils'], function(instance){
        instance.updatePopupHints($body);
        showFieldEmbeddedVideo($body);
    });

});
