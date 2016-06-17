<div class="modal-dialog {$modalSizeClass}">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">{$Grid.Title}</h4>
        </div>

        <div class="modal-body">
            <form class="js-pgui-edit-form form-horizontal" enctype="multipart/form-data" method="POST" action="{$Grid.FormAction}">
                <fieldset>
                    <input type="hidden" name="edit_operation" value="commit_insert">
                    <input id="submit-action" name="submit1" type="hidden" value="save">

                    {foreach key=HiddenValueName item=HiddenValue from=$HiddenValues}
                        <input type="hidden" name="{$HiddenValueName}" value="{$HiddenValue}">
                    {/foreach}

                    {foreach item=Column from=$Grid.Columns name=EditColumns}
                        {include file='forms/form-group.tpl' Column=$Column}
                    {/foreach}

                    {include file='forms/form-required.tpl'}
                </fieldset>
            </form>

            <div class="error-container"></div>
        </div>

        <div class="modal-footer">
            <div class="btn-toolbar pull-right">

                <div class="btn-group">
                    <button class="btn btn-default cancel-button">
                        {$Captions->GetMessageString('Cancel')}
                    </button>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-primary submit-button">
                        {$Captions->GetMessageString('Save')}
                    </button>
                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li data-value="save"><a href="#" id="save">{$Captions->GetMessageString('SaveAndBackToList')}</a></li>
                        <li data-value="saveedit"><a href="#" id="saveedit">{$Captions->GetMessageString('SaveAndEdit')}</a></li>
                        <li data-value="saveinsert"><a href="#" id="saveinsert">{$Captions->GetMessageString('SaveAndInsert')}</a></li>
                    </ul>
                </div>

            </div>
        </div>
        <script type="text/javascript">
            {literal}
                function InsertForm_initd(editors) {
                    {/literal}{$Grid.OnLoadScript}{literal}
                }
            {/literal}
        </script>
    </div>
</div>
