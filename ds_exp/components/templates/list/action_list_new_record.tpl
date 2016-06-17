{foreach item=Operation from=$DataGrid.Actions.Operations}
    <span data-column-name="{$Operation->GetName()}" class="operation-item">

        {if $Operation->GetName() == 'edit' OR $Operation->GetName() == 'InlineEdit'}

            <span data-content="inline_insert_controls text-nowrap">

                <a href="#" class="js-inline_insert_cancel link-icon" title="Cancel">
                    <span class="text-lg text-danger"><i class="icon-remove"></i></span>
                </a>

                <a href="#" class="js-inline_insert_commit link-icon" title="Commit">
                    <span class="text-lg text-success"><i class="icon-ok"></i></span>
                </a>

            </span>

        {/if}

    </span>
{/foreach}