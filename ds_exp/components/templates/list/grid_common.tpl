{if not $isMasterGrid}
    {include file="list/multiple_sorting.tpl" GridId=$DataGrid.Id Levels=$DataGrid.DataSortPriority SortableHeaders=$DataGrid.SortableColumns}
{/if}

{if $DataGrid.FilterBuilder}
    {include file="list/filter_builder.tpl"}
{/if}

<script type="text/javascript">

    {if $AdvancedSearchControl}
    {literal}
    require(['pgui.text_highlight'], function(textHighlight) {
        {/literal}
        {foreach from=$AdvancedSearchControl->GetHighlightedFields() item=HighlightFieldName name=HighlightFields}
        textHighlight.HighlightTextInGrid(
                '#{$DataGrid.Id}', '{$HighlightFieldName}',
                {$TextsForHighlight[$smarty.foreach.HighlightFields.index]},
                '{$HighlightOptions[$smarty.foreach.HighlightFields.index]}');
        {/foreach}
        {literal}
    });
    {/literal}
    {/if}


    {literal}
    require(['pgui.grid', 'pgui.advanced_filter', 'jquery'], function(pggrid, fb) {

        var gridId = '{/literal}{$DataGrid.Id}{literal}';
        var $gridContainer = $('#' + gridId);
        var grid = new pggrid.Grid($gridContainer);

        {/literal}
        {if $DataGrid.FilterBuilder}
        {literal}
        grid.onConfigureFilterBuilder(function(filterBuilder) {
            {/literal}
            {foreach item=FilterBuilderField from=$FilterBuilder.Fields}
            filterBuilder.addField(
                    {jsstring value=$FilterBuilderField.Name charset=$Page->GetContentEncoding()},
                    {jsstring value=$FilterBuilderField.Caption charset=$Page->GetContentEncoding()},
                    fb.FieldType.{$FilterBuilderField.Type},
                    fb.{$FilterBuilderField.EditorClass},
                    {$FilterBuilderField.EditorOptions});
            {/foreach}
            {literal}
        });

        var activeFilterJson = {/literal}{$ActiveFilterBuilderJson}{literal};
        var activeFilter = new fb.FilterGroup();
        activeFilter.fromJson(activeFilterJson);
        grid.setFilter(activeFilter);
        {/literal}
        {/if}
        {literal}
    });
    {/literal}
</script>