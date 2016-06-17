require([
    'pgui.page_settings',
    'pgui.datetimepicker',
    'pgui.shortcuts',
    'jquery'
], function (pageSettings, datetimepicker, shortcuts) {

    $(function () {
        var $body = $('body');

        pageSettings($body);
        datetimepicker.setupCalendarControls($body);

        $('[data-pg-select2filter=true]').each(function() {
            var $input = $(this);

            require(['pgui.select2_filter'], function(module) {
                (new module.Select2Filter($input));
            })
        });

        if ($('table.table.fixed-header').length > 0) {
            require(["jquery.stickytableheaders"], function() {
                var $navbar = $('.navbar');
                var $el = $('table.table');
                var marginTop = 0;

                if ($navbar.hasClass('navbar-fixed-top')) {
                    marginTop += $navbar.outerHeight();
                }

                $el.stickyTableHeaders({
                    selector: 'thead:first tr.header',
                    marginTop: marginTop
                });
            });
        }

        shortcuts.push(['grid']);
    });

});
