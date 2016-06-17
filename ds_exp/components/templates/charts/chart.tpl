<div id="pgui-chart-{$chart.id}" class="pgui-chart" style="height: {$chart.height}px">
    <img class="pgui-chart-loading" src="components/assets/img/loading.gif">
</div>

{literal}
<script type="text/javascript">
    require(['pgui.charts'], function (initializeChart) {
        initializeChart({
            id: '{/literal}{$chart.id}{literal}',
            type: '{/literal}{$type}{literal}',
            options: {/literal}{$chart.options|@json_encode}{literal},
            data: {/literal}{$chart.data|@json_encode}{literal}
        });
    });
</script>
{/literal}