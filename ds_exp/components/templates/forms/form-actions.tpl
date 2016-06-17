<div class="form-actions{if $top} form-actions-top{/if}">
    <div class="form-group">
        <div class="col-lg-8">
            <div class="row">
                <div class="col-sm-9 col-sm-offset-3">
                    <div class="btn-toolbar">
                        <div class="btn-group">
                            <button {if not $top}id="submit-button" {/if}class="btn btn-primary submit-button" type="submit">{$Captions->GetMessageString('Save')}</button>
                            <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="#" class="save-button" data-value="save">{$Captions->GetMessageString('SaveAndBackToList')}</a>
                                </li>
                                <li>
                                    <a href="#" class="saveinsert-button" data-value="saveinsert">{$Captions->GetMessageString('SaveAndInsert')}</a>
                                </li>
                                <li>
                                    <a href="#" class="saveedit-button" data-value="saveedit">
                                        {$Captions->GetMessageString('SaveAndEdit')}
                                    </a>
                                </li>

                                {if count($Grid.Details) > 0}
                                    <li class="divider"></li>
                                {/if}

                                {foreach from=$Grid.Details item=Detail}
                                    <li><a class="save-and-open-details" href="#" data-action="{$Detail.Link}">{$Detail.Caption|string_format:$Captions->GetMessageString('SaveAndOpenDetail')}</a></li>
                                {/foreach}
                            </ul>
                        </div>

                        <div class="btn-group">
                            <button class="btn btn-default" onclick="window.location.href='{$Grid.CancelUrl}'; return false;">{$Captions->GetMessageString('Cancel')}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>