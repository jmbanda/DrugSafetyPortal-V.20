define(['moment', 'jquery'], function (moment) {
    var charts = [];

    google.setOnLoadCallback(drawCharts);
    $(window).on('resize', drawCharts);

    function getTable(chart) {
        var table = new google.visualization.DataTable();
        $.each(chart.data.columns, function (i, column) {
            // annotations and tooltips
            if (column.role !== 'data') {
                table.addColumn({
                    role: column.role,
                    type: 'string',
                    p: {'html': true}
                });
                return;
            }

            table.addColumn({
                type: transformType(column.type),
                label: column.label
            });
        });

        $.each(chart.data.rows, function (i, rowData) {
            var row = [];

            $.each(chart.data.columns, function (j, col) {
                var value = rowData[j];

                if (col.type === 'date') {
                    value = moment(value).toDate();
                }

                row.push(value);
            });

            table.addRow(row);
        });

        applyFormatters(chart.data.columns, table);

        return table;
    };

    var formatterClasses = {
        'number': google.visualization.NumberFormat,
        'date': google.visualization.DateFormat,
    };

    function applyFormatters(columns, table) {
        $.each(columns, function (i, column) {
            var Formatter = formatterClasses[transformType(column.type)]
            if (!column.format || !Formatter) {
                return;
            }

            (new Formatter({
                pattern: column.format
            })).format(table, i);
        });
    }

    function transformType(type) {
        type = type || 'string';
        switch (type.toLowerCase()) {
            case 'string':
                return 'string'
            case 'date':
                return 'date';
            default:
                return 'number';
        }
    }

    function mergeStyles($container, chartType, options) {
        var textColor = $container.css('color');
        var gridlinesColor = $container.css('border-color');
        var result = $.extend(true, {
            backgroundColor: 'none',
            titleTextStyle: {
                color: textColor
            },
            legend:{
                textStyle: {
                    color: textColor
                },
            },
            vAxis: {
                gridlines: {color: gridlinesColor}
            },
            hAxis: {
                gridlines: {color: gridlinesColor}
            }
        }, options);

        if (chartType == 'Pie') {
            return result;
        }

        var axisStyles = {
            titleTextStyle: {color: textColor},
            textStyle: {color: textColor},
        };

        return $.extend(true, {
            hAxis: axisStyles,
            vAxis: axisStyles,
        }, result);
    }

    function drawCharts() {
        $.each(charts, function (i, chartConfig) {
            chartConfig.table = chartConfig.table || getTable(chartConfig);
            var $container = $('#pgui-chart-' + chartConfig.id);
            chartConfig.options = mergeStyles($container, chartConfig.type, chartConfig.options);
            var chart = new google.visualization[chartConfig.type + 'Chart']($container.get(0));
            chart.draw(chartConfig.table, chartConfig.options);
        });
    }

    return function (chartConfig) {
        charts.push(chartConfig);
    }
});
