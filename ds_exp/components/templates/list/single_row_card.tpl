{if count($DataGrid.Rows) > 0}

    {foreach item=Row from=$DataGrid.Rows name=RowsGrid}

        {*  The same code is used in single_row.tpl *}
        {if $Row.Classes}
            {assign var="rowClasses" value="pg-row "|cat:$Row.Classes}
        {else}
            {assign var="rowClasses" value="pg-row"}
        {/if}

        <div class="grid-card-item {if $isMasterGrid}col-md-12{else}{$DataGrid.CardClasses}{/if} {$rowClasses}">

            <div class="well" style="{$Row.Style}">

                {if $DataGrid.AllowDeleteSelected}
                    <div class="row-selection pull-left">
                        <input type="checkbox" name="rec{$smarty.foreach.RowsGrid.index}" >
                        {foreach item=PkValue from=$Row.PrimaryKeys name=CPkValues}
                            <input type="hidden" name="rec{$smarty.foreach.RowsGrid.index}_pk{$smarty.foreach.CPkValues.index}" value="{$PkValue|escapeurl}" />
                        {/foreach}
                    </div>
                {/if}

                {if $DataGrid.ShowLineNumbers or $DataGrid.AllowDeleteSelected or $DataGrid.HasDetails or $DataGrid.Actions}
                <div class="grid-card-item-control pull-right">
                {/if}

                    {if $DataGrid.ShowLineNumbers}
                        <div class="line-number pull-left" style="{$Row.Style}">#{$Row.LineNumber}</div>
                    {/if}

                    {if $DataGrid.HasDetails}
                        <div dir="ltr" class="details pull-left" style="{$Row.Style}">
                            <div class="btn-group text-nowrap">
                                <a class="link-icon" data-toggle="dropdown" href="#" title="{$Captions->GetMessageString('GoToMasterDetailPage')}"><span class="icon-list"></span> <i class="icon-detail-additional"></i></a>
                                <ul class="dropdown-menu">
                                    {foreach from=$Row.Details.Items item=Detail}
                                        <li><a href="{$Detail.SeperatedPageLink|escapeurl}">{$Detail.caption}</a></li>
                                    {/foreach}
                                </ul>
                            </div>
                        </div>
                    {/if}

                    {if $DataGrid.Actions}
                        <div class="operation-column pull-left">{include file="list/action_list.tpl" Actions=$Row.ActionsDataCells}</div>
                    {/if}

                {if $DataGrid.ShowLineNumbers or $DataGrid.AllowDeleteSelected or $DataGrid.HasDetails or $DataGrid.Actions}
                </div>
                {/if}

                <div class="grid-card-item-data">
                    <table class="table">
                        {foreach item=Cell from=$Row.DataCells name=Cells}
                            <tr>
                                <th>{$Cell.ColumnCaption}</th>
                                {include file="list/data_cell.tpl"}
                            </tr>
                        {/foreach}
                    </table>
                </div>
            </div>

        </div>

    {/foreach}

{/if}