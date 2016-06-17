define(['jquery', 'pgui.shortcuts'], function () {
    $(function () {
        var $body = $('body');
        $body.on('click', '.toggle-sidebar,.sidebar-backdrop', function () {
            $body.toggleClass(window.outerWidth <= 992 ? 'sidebar-active' : 'sidebar-desktop-active');
        });
    });
});
