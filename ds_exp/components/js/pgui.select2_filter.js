define(function(require, exports) {
    require('libs/select2');
    require('jquery.resize');

    var Class = require('class');

    exports.Select2Filter = Class.extend({

        init: function($input) {
            var count = parseInt($input.data('pg-select2filter-count'));
            var url = location.protocol + '//' + location.host + location.pathname;

            $input.select2({
                ajax: {
                    url: url,
                    minimumInputLength: 0,
                    data: function (term) {
                        return {
                            hname: $input.data('pg-select2filter-handler'),
                            term: term
                        };
                    },
                    results: function (data) {
                        return {
                            results: $.map(
                                data.slice(0, count || data.length),
                                function (item) {
                                    return {
                                        id: item.id,
                                        text: item.value
                                    };
                                }
                            )
                        };
                    }
                },
                initSelection: function (element, callback) {
                    if (!element.select2('data') && element.data('post-value')) {
                        callback({
                            id: element.data('post-value'),
                            text: element.val()
                        });

                        return;
                    };

                    $.getJSON(url, {
                        hname: $input.data('pg-select2filter-handler'),
                        id: element.data('post-value')
                    }, function (data) {
                        var item = data[0] || {id:'', value:''};
                        callback({
                            id: item.id,
                            text: item.value
                        });
                    });
                },
                placeholder: ' ',
                allowClear: true,
                width: 'auto',
                dropdownCss: {width: '200px'}
            });

            $input.on('change', function () {
                var item = $input.select2('data') || {id:'', text:''};
                $input.data('post-value', item.id);
                $input.data('raw-value', item.text);
                $input.data('pg-events').changed();
            });
        },

        getDisplayValue: function() {
        }
    });
});