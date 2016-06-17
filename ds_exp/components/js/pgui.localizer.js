define(['components/js/jslang.php?'], function(locale) {
    return {
        getString: function (code, defaultValue) {
            return typeof(locale.translations[code]) !== 'undefined'
                ? locale.translations[code]
                : defaultValue || code;
        },

        getFirstDayOfWeek: function () {
            return locale.firstDayOfWeek || 0;
        }
    };
});