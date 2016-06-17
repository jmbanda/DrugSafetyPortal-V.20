define(function(require, exports) {

    require('jquery');
    require('locales/datetimepicker_locale');
    require('datepicker');

    var Class   = require('class'),
        events  = require('microevent');

    var DateTimePicker = exports.DateTimePicker = Class.extend({

        init: function($container) {
            var self = this;
            this.$container  = $container;
            this.$container.data('DateTimePicker-class', self);

            this.dateFormat = this.$container.attr('data-picker-format');
            this.fdow = this.$container.attr('data-picker-first-day-of-week');
            var verticalPosition = this.$container.data('vertical') || 'auto';

            this.visible = false;
            this.calendar = null;

            self.$target = self.$container.closest('.js-datetime-editor-wrap');
            if (self.$target.length === 0) {
                self.$target = $container;
            }

            self.$target.datetimepicker({
                allowInputToggle: true,
                format: self.dateFormat,
                useCurrent: false,
                showClear: true,
                showClose: true,
                showTodayButton: true,
                widgetPositioning: {vertical: verticalPosition},
                icons: {
                    time: 'icon-time',
                    date: 'icon-calendar',
                    up:   'icon-chevron-up',
                    down: 'icon-chevron-down',
                    previous: 'icon-chevron-left',
                    next:  'icon-chevron-right',
                    today: 'icon-today',
                    clear: 'icon-remove',
                    close: 'icon-ok'
                }
            });

            self.$target
                .on("dp.hide", function (e) {
                    self.$container.trigger('keyup');
                });
        },

        onChange: function(callback) {
            this.$target.on('dp.change', callback);
        }

    });
    events.mixin(DateTimePicker);

    exports.setupCalendarControls = function($container) {
        $container.find('[data-calendar=true]').each(function() {
            new DateTimePicker($(this));
        });
    };

});