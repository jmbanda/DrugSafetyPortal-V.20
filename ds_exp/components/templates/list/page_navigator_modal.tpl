{if $PageNavigator}
    {assign var="totalRecords" value=$PageNavigator->GetRowCount()}
    {assign var="countPerPage" value=$PageNavigator->GetRowsPerPage()}
{else}
    {assign var="totalRecords" value=0}
    {assign var="countPerPage" value=0}
{/if}

{if $PageNavigator or $EnableRunTimeCustomization}
    <div id="page-settings" class="modal modal-top fade js-page-settings-dialog" data-total-record-count="{$totalRecords}" data-record-count-per-page="{$countPerPage}" tabIndex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">{$Captions->GetMessageString('PageSettings')}</h3>
                </div>

                <div class="modal-body">

                    <h4>{$Captions->GetMessageString('Appearance')}</h4>

                    <table class="table table-bordered table-condensed form-inline">
                        {assign var="Grid" value=$Page->GetGrid()}
                        <tr>
                            <th style="width: 50%;"><label for="page-settings-viewmode-control">{$Captions->GetMessageString('ViewMode')}</label></th>
                            <td>
                                <select id="page-settings-viewmode-control" class="form-control js-page-settings-viewmode-control" style="width: 70%;">
                                    {assign var="CurrentViewMode" value=$Grid->GetViewMode()}
                                    {foreach from=$ViewModes item=caption key=mode}
                                        <option value="{$mode}" data-name="{$caption}"{if $CurrentViewMode == $mode} selected="selected"{/if}>{$Captions->GetMessageString($caption)}</option>
                                    {/foreach}
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="page-settings-card-column-count">{$Captions->GetMessageString('CardRowCount')}</label></th>
                            <td><select class="form-control js-page-settings-card-column-count" id="page-settings-card-column-count" style="width: 70%;">
                                    {assign var="AvailableCardCountInRow" value=$Grid->GetAvailableCardCountInRow()}
                                    {assign var="CardCountInRow" value=$Grid->GetCardCountInRow()}
                                    {foreach from=$AvailableCardCountInRow item=Count}
                                        <option{if $CardCountInRow == $Count} selected="selected"{/if}>{$Count}</option>
                                    {/foreach}
                                </select></td>
                        </tr>
                    </table>

                    {if $PageNavigator}
                        <h4>{$Captions->GetMessageString('ChangePageSizeTitle')}</h4>

                        {assign var="row_count" value=$PageNavigator->GetRowCount()}
                        <p>{eval var=$Captions->GetMessageString('ChangePageSizeText')}</p>

                        <table class="table table-bordered table-condensed form-inline">
                            <tr>
                                <th style="width:50%;"> <label for="page-settings-page-size-control">{$Captions->GetMessageString('RecordsPerPage')}</label></th>
                                <td>
                                    <span class="js-page-settings-page-size-container">
                                        <select id="page-settings-page-size-control" class="form-control js-page-settings-page-size-control" style="width: 70%;">
                                            {foreach from=$PageNavigator->GetRecordsPerPageValues() key=name item=value}
                                                {assign var="record_count" value=$value}
                                                {assign var="page_count" value=$PageNavigator->GetPageCountForPageSize($name)}
                                                <option value="{$name}">{eval var=$Captions->GetMessageString('CountRowsWithCountPages')}</option>
                                            {/foreach}
                                            <option value="custom">{$Captions->GetMessageString('CustomRecordsPerPage')}</option>
                                        </select>
                                    </span>

                                    <span class="js-page-settings-custom-page-size-container" style="display: none">
                                        <input type="number" min="1" max="{$PageNavigator->GetRowCount()}" value="{$PageNavigator->GetRowsPerPage()|escape:html}" id="page-settings-custom-page-size-control" class="js-page-settings-custom-page-size-control form-control" style="width: 40%;">
                                        <span style="margin-left: .5em;">
                                            {assign var="current_page_count" value='<span class="js-page-settings-custom-page-size-pager"></span>'}
                                            {eval var=$Captions->GetMessageString('CurrentPageCount')}
                                        </span>
                                    </span>
                                </td>
                            </tr>
                        </table>
                    {/if}

                </div>

                <div class="modal-footer">
                    <a href="#" class="js-page-settings-cancel btn btn-default" data-dismiss="modal">{$Captions->GetMessageString('Cancel')}</a>
                    <a href="#" class="js-page-settings-save btn btn-primary">{$Captions->GetMessageString('SaveChanges')}</a>
                </div>

            </div>
        </div>
    </div>
{/if}