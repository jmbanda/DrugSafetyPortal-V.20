{if $DataGrid.ActionsPanelAvailable}
    <div class="addition-block">
        <div class="btn-toolbar addition-block-left pull-left">
            <div class="btn-group">
                {if $DataGrid.ActionsPanel.InlineAdd}
                    <button class="btn btn-default inline_add_button pgui-add" title="{$Captions->GetMessageString('AddNewRecord')}">
                        <i class="icon-plus"></i>
                        <span class="visible-lg-inline">{$Captions->GetMessageString('AddNewRecord')}</span>
                    </button>
                {/if}

                {if $DataGrid.ActionsPanel.AddNewButton}
                    {if $DataGrid.ActionsPanel.AddNewButton eq 'modal'}
                        <button class="btn btn-default pgui-add"
                                data-dialog-title="{$Captions->GetMessageString('AddNewRecord')}"
                                data-content-link="{$DataGrid.Links.ModalInsertDialog|escapeurl}"
                                data-modal-insert="true"
                                title="{$Captions->GetMessageString('AddNewRecord')}">
                            <i class="icon-plus"></i>
                            <span class="visible-lg-inline">{$Captions->GetMessageString('AddNewRecord')}</span>
                        </button>
                    {else}
                        <a class="btn btn-default pgui-add" href="{$DataGrid.Links.SimpleAddNewRow|escapeurl}"
                           title="{$Captions->GetMessageString('AddNewRecord')}">
                            <i class="icon-plus"></i>
                            <span class="visible-lg-inline">{$Captions->GetMessageString('AddNewRecord')}</span>
                        </a>
                    {/if}
                {/if}

                {if $DataGrid.ActionsPanel.DeleteSelectedButton}
                    <button class="btn btn-default js-delete-selected" title="{$Captions->GetMessageString('DeleteSelected')}">
                        <i class="icon-delete-selected"></i>
                        <span class="visible-lg-inline">{$Captions->GetMessageString('DeleteSelected')}</span>
                    </button>
                {/if}

                {if $DataGrid.ActionsPanel.RefreshButton}
                    <a class="btn btn-default" href="{$DataGrid.Links.Refresh|escapeurl}" title="{$Captions->GetMessageString('Refresh')}">
                        <i class="icon-page-refresh"></i>
                        <span class="visible-lg-inline">{$Captions->GetMessageString('Refresh')}</span>
                    </a>
                {/if}
            </div>

            {assign var="pageTitleButtons" value=$Page->GetExportListButtonsViewData()}

            {if $pageTitleButtons}
                <div class="btn-group export-button">

                    {if $Page->getExportListAvailable()}
                        {include file="view/export_buttons.tpl" buttons=$pageTitleButtons spanClasses="visible-lg-inline"}
                    {/if}

                    {if $Page->getPrintListAvailable()}
                    {include file="view/print_buttons.tpl" buttons=$pageTitleButtons spanClasses="visible-lg-inline"}
                    {/if}

                    {if $Page->GetRssLink()}
                        <a href="{$Page->GetRssLink()}" class="btn btn-default" title="RSS">
                            <i class="icon-rss"></i>
                            <span class="visible-lg-inline">RSS</span>
                        </a>
                    {/if}

                </div>

            {/if}

        </div>

        <div class="addition-block-right pull-right">

            {if $DataGrid.FilterBuilder}
                <div class="btn-group">
                    <button type="button" class="btn btn-default js-filter-builder-open" title="{if $IsActiveFilterEmpty}{$Captions->GetMessageString('CreateFilter')}{else}{$Captions->GetMessageString('EditFilter')}{/if}">
                        <i class="icon-filter{if $IsActiveFilterEmpty}-new{/if}"></i>
                    </button>
                </div>
            {/if}

            <div class="btn-group">
                <button id="multi-sort-{$DataGrid.Id}" class="btn btn-default" title="{$Captions->GetMessageString('Sort')}" data-toggle="modal" data-target="#multiple-sorting-{$DataGrid.Id}">
                    <i class="icon-sort"></i>
                </button>
            </div>

            {if $PageNavigator or $EnableRunTimeCustomization}
                <div class="btn-group">
                    <button class="btn btn-default" title="{$Captions->GetMessageString('PageSettings')}" data-toggle="modal" data-target="#page-settings">
                        <i class="icon-settings"></i>
                    </button>
                </div>
            {/if}

            {if $Page->getDetailedDescription()}
                <div class="btn-group">
                    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#detailedDescriptionModal" title="{$Captions->GetMessageString('PageDescription')}"><i class="icon-question"></i></button>
                </div>

                <div class="modal fade" id="detailedDescriptionModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                {$Page->getDetailedDescription()}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">{$Captions->GetMessageString('Close')}</button>
                            </div>
                        </div>
                    </div>
                </div>
            {/if}

        </div>

        <div class="addition-block-quick-fitler pull-right">
            {if $DataGrid.AllowQuickFilter}
                <div class="quick-filter-toolbar btn-group" id="quick-filter-toolbar">
                    <div class="input-group js-filter-control">
                        <input placeholder="{$Captions->GetMessageString('QuickSearch')}" type="text" size="16" class="js-quick-filter-text form-control" value="{$DataGrid.QuickFilter.Value|escape:html}">
                        <div class="input-group-btn">
                            <button type="button" id="quick-filter-go" class="btn btn-default quick-filter-go" title="{$Captions->GetMessageString('QuickSearchApply')}"><i class="icon-search"></i></button>
                            <button type="button" class="btn btn-default quick-filter-reset" title="{$Captions->GetMessageString('QuickSearchClear')}"><i class="icon-filter-reset"></i></button>
                        </div>
                    </div>
                </div>
            {/if}
            &thinsp;
        </div>

    </div>
{/if}

{if $DataGrid.FilterBuilder and not $IsActiveFilterEmpty}
    <div class="filter-builder-status js-filter-builder-status-string">
        <div class="btn-group filter-builder-status-btn-group pull-right">
            <button type="button" class="btn btn-primary btn-sm js-filter-builder-open" title="{$Captions->GetMessageString('EditFilter')}">
                <i class="icon-edit"></i>
            </button>
            <button type="button" class="btn btn-default btn-sm js-reset-filter text-nowrap" title="{$Captions->GetMessageString('ResetFilter')}">
                <i class="icon-remove"></i>
            </button>
            <button type="button" class="btn btn-default btn-sm js-toggle-filter" data-enabled="{if $DataGrid.FilterBuilder.IsEnabled}true{else}false{/if}" title="{if $DataGrid.FilterBuilder.IsEnabled}{$Captions->GetMessageString('DisableFilter')}{else}{$Captions->GetMessageString('EnableFilter')}{/if}">
                {if $DataGrid.FilterBuilder.IsEnabled}
                    <i class="icon-disable"></i>
                {else}
                    <i class="icon-enable"></i>
                {/if}
            </button>
        </div>
        <div class="filter-builder-status-container {if not $DataGrid.FilterBuilder.IsEnabled} filter-builder-status-disabled{/if}">
            <i class="filter-builder-status-icon icon-filter"></i>
            <span class="filter-builder-status-query">{$ActiveFilterBuilderAsString}</span>
        </div>
    </div>
{/if}

{if $DataGrid.ErrorMessage}
    {include file='common/message.tpl' type='danger' dismissable=true content=$DataGrid.ErrorMessage displayTime=$DataGrid.MessageDisplayTime}
{/if}

{if $DataGrid.GridMessage}
    {include file='common/message.tpl' type='success' dismissable=true content=$DataGrid.GridMessage displayTime=$DataGrid.MessageDisplayTime}
{/if}

<div class="js-grid-message-container" data-template='{include file="common/message.tpl" type="success" dismissable="true"}'>
</div>