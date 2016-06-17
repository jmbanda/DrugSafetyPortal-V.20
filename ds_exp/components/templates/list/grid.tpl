<div{if $DataGrid.MaxWidth} style="max-width: {$DataGrid.MaxWidth}"{/if}
        class="grid grid-table{if $isMasterGrid} grid-master{/if}"
        id="{$DataGrid.Id}"
        data-is-master="{$isMasterGrid}"
        data-grid-hidden-values="{$DataGrid.HiddenValuesJson|escape:'html'}"
        data-sortable-columns="{$DataGrid.SortableColumnsJSON|escape}"
        data-quickfilter-fields="{$DataGrid.QuickFilter.FieldsNames|@json_encode|escape}"
        data-inline-edit="{ldelim} &quot;enabled&quot;:&quot;{jsbool value=$DataGrid.UseInlineEdit}&quot;, &quot;request&quot;:&quot;{$DataGrid.Links.InlineEditRequest|escapeurl}&quot;{rdelim}" {$DataGrid.Attributes}>

    {include file="list/grid_header.tpl"}

    <table class="table text-center {$DataGrid.Classes}{if $DataGrid.TableIsBordered} table-bordered{/if}{if $DataGrid.TableIsCondensed} table-condensed{/if}">
        <thead>

            <tr class="header">

                {if $DataGrid.AllowDeleteSelected}
                    <th style="width:1%;">
                        <div class="row-selection">
                            <input type="checkbox">
                        </div>
                    </th>
                {/if}

                {if $DataGrid.HasDetails}
                    <th class="details">
                        <a class="expand-all-details js-expand-all-details collapsed link-icon" href="#" title="{$Captions->GetMessageString('ToggleAllDetails')}">
                            <i class="icon-detail-plus"></i>
                            <i class="icon-detail-minus"></i>
                        </a>
                    </th>
                {/if}

                {if $DataGrid.ShowLineNumbers}
                    <th style="width:1%;">#</th>
                {/if}

                {if $DataGrid.Actions and $DataGrid.Actions.PositionIsLeft}
                    <th style="width:1%;">
                        {$DataGrid.Actions.Caption}
                    </th>
                {/if}

                {foreach item=Band from=$DataGrid.Bands}
                    {if $Band.ConsolidateHeader and $Band.ColumnCount > 0}
                        <th colspan="{$Band.ColumnCount}" style="width:1%;">
                            {$Band.Caption}
                        </th>
                    {else}
                        {foreach item=Column from=$Band.Columns}
                            <th class="{$Column.Classes}{if $Column.Sortable} sortable{/if}"
                                {if $Column.Width}
                                    style="width: {$Column.Width};"
                                {/if}
                                data-field-caption="{$Column.Caption}"
                                data-field-name="{$Column.Name}"
                                data-sort-index="{$Column.SortIndex}"
                                {if $Column.SortOrderType == 'ASC'}
                                    data-sort-order="asc"
                                {elseif $Column.SortOrderType == 'DESC'}
                                    data-sort-order="desc"
                                {/if}
                                data-comment="{$Column.Comment}">
                                {if $Column.Keys.Primary and $Column.Keys.Foreign}
                                    <i class="icon-keys-pk-fk"></i>
                                {else}
                                    {if $Column.Keys.Primary}
                                        <i class="icon-keys-pk"></i>
                                    {/if}
                                    {if $Column.Keys.Foreign}
                                        <i class="icon-keys-fk"></i>
                                    {/if}
                                {/if}
                                <span{if $Column.Comment} class="commented"{/if}>{$Column.Caption}</span>
                                {if $Column.SortOrderType == 'ASC'}
                                    <i class="icon-sort-asc"></i>
                                {elseif $Column.SortOrderType == 'DESC'}
                                    <i class="icon-sort-desc"></i>
                                {/if}
                            </th>
                        {/foreach}
                    {/if}
                {/foreach}

                {if $DataGrid.Actions and $DataGrid.Actions.PositionIsRight}
                    <th style="width:1%;">
                        {$DataGrid.Actions.Caption}
                    </th>
                {/if}
            </tr>

        </thead>
        <tbody class="pg-row-list">
            <tr class="pg-row js-new-record-row hidden" data-new-row="false">
                {if $DataGrid.AllowDeleteSelected}
                    <td data-column-name="sm_multi_delete_column"></td>
                {/if}

                {if $DataGrid.HasDetails}
                    <td dir="ltr" data-column-name="details" class="details">
                    </td>
                {/if}

                {if $DataGrid.ShowLineNumbers}
                    <td class="line-number"></td>
                {/if}

                {if $DataGrid.Actions and $DataGrid.Actions.PositionIsLeft}
                    <td class="operation-column">{include file="list/action_list_new_record.tpl"}</td>
                {/if}

                {foreach item=Band from=$DataGrid.Bands}
                    {foreach item=Column from=$Band.Columns}
                        <td data-column-name="{$Column.Name}"></td>
                    {/foreach}
                {/foreach}

                {if $DataGrid.Actions and $DataGrid.Actions.PositionIsRight}
                    <td class="operation-column">{include file="list/action_list_new_record.tpl"}</td>
                {/if}
            </tr>

            {include file=$SingleRowTemplate}

            <tr class="empty-grid{if count($DataGrid.Rows) > 0} hidden{/if}">
                <td colspan="{$DataGrid.ColumnCount}" class="empty-grid">
                    {$DataGrid.EmptyGridMessage}
                </td>
            </tr>

        </tbody>

        <tfoot>
            {if $DataGrid.Totals}
                <tr class="data-summary">
                    {if $DataGrid.AllowDeleteSelected}
                        <td></td>
                    {/if}

                    {if $DataGrid.HasDetails}
                        <td></td>
                    {/if}

                    {if $DataGrid.ShowLineNumbers}
                        <td></td>
                    {/if}

                    {if $DataGrid.Actions and $DataGrid.Actions.PositionIsLeft}
                        <td></td>
                    {/if}

                    {foreach item=Total from=$DataGrid.Totals}
                        <td class="{$Total.Classes}">{$Total.Value}</td>
                    {/foreach}

                    {if $DataGrid.Actions and $DataGrid.Actions.PositionIsRight}
                        <td></td>
                    {/if}
                </tr>
            {/if}
        </tfoot>
    </table>

    {include file="list/grid_common.tpl"}
</div>