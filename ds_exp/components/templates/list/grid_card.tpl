<div{if $DataGrid.MaxWidth} style="max-width: {$DataGrid.MaxWidth}"{/if}
        class="grid grid-card{if $isMasterGrid} grid-master{/if}"
        id="{$DataGrid.Id}"
        data-is-master="{$isMasterGrid}"
        data-grid-hidden-values="{$DataGrid.HiddenValuesJson|escape:'html'}"
        data-sortable-columns="{$DataGrid.SortableColumnsJSON|escape}"
        data-quickfilter-fields="{$DataGrid.QuickFilter.FieldsNames|@json_encode|escape}"
        data-inline-edit="{ldelim} &quot;enabled&quot;:&quot;{jsbool value=$DataGrid.UseInlineEdit}&quot;, &quot;request&quot;:&quot;{$DataGrid.Links.InlineEditRequest|escapeurl}&quot;{rdelim}" {$DataGrid.Attributes}>

    {include file="list/grid_header.tpl"}

    <div class="{$DataGrid.Classes}" {$DataGrid.Attributes}>

        <div class="pg-row-list row">

            <div class="grid-card-item {$DataGrid.CardClasses} pg-row js-new-record-row hidden" data-new-row="false">

                <div class="well">

                    {if $DataGrid.AllowDeleteSelected}<div class="row-selection pull-left"></div>{/if}

                    {if $DataGrid.ShowLineNumbers or $DataGrid.AllowDeleteSelected or $DataGrid.HasDetails or $DataGrid.Actions}
                    <div class="grid-card-item-control pull-right">
                        {/if}

                        {if $DataGrid.ShowLineNumbers}
                            <div class="line-number pull-left"></div>
                        {/if}

                        {if $DataGrid.HasDetails}
                            <div dir="ltr" class="details pull-left"></div>
                        {/if}

                        {if $DataGrid.Actions}
                            <div class="operation-column pull-left">{include file="list/action_list_new_record.tpl"}</div>
                        {/if}

                        {if $DataGrid.ShowLineNumbers or $DataGrid.AllowDeleteSelected or $DataGrid.HasDetails or $DataGrid.Actions}
                    </div>
                    {/if}

                    <div class="grid-card-item-data">
                        <table class="table">
                            {foreach item=Band from=$DataGrid.Bands}
                                {foreach item=Column from=$Band.Columns}
                                    <tr>
                                        <th>{$Column.Caption}</th>
                                        <td data-column-name="{$Column.Name}"></td>
                                    </tr>
                                {/foreach}
                            {/foreach}
                        </table>
                    </div>
                </div>

            </div>

            {include file=$SingleRowTemplate}

            <div class="empty-grid{if count($DataGrid.Rows) > 0} hidden{/if}">
                {$DataGrid.EmptyGridMessage}
            </div>

        </div>

        <div>
            {if $DataGrid.Totals}
                <div class="data-summary">
                    {foreach item=Total from=$DataGrid.Totals}
                        {if $Total.Value}
                            <div>
                                <strong>{$Total.Caption}</strong>
                                {$Total.Value}
                            </div>
                        {/if}
                    {/foreach}
                </div>
            {/if}
        </div>
    </div>

    {include file="list/grid_common.tpl"}
</div>