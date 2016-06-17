{php}
    $this->assign("IconByOperationMap", array('view' => 'icon-view', 'edit' => 'icon-edit', 'delete' => 'icon-remove', 'copy' => 'icon-copy' ));
{/php}

{assign var="UseOperationContainer" value=$UseOperationContainer|default:true}

{foreach item=Cell from=$Actions}

    {if $Cell.Data}
        {if $UseOperationContainer}<span data-column-name="{$Cell.OperationName}" class="operation-item">{/if}

            {if $Cell.Data.type == 'link'}

                <a href="{$Cell.Data.link}" title="{$Cell.Data.caption}"{if $Cell.Data.useImage} class="link-icon"{/if}
                    {foreach from=$Cell.Data.additionalAttributes key=key item=value} {$key}="{$value}"{/foreach}>

                    {if $Cell.Data.useImage}
                        <i class="{$Cell.IconClass}"></i>
                    {else}
                        {$Cell.Data.caption}
                    {/if}

                </a>

            {elseif $Cell.Data.type == 'modal'}

                <a href="#" title="{$Cell.Data.dialogTitle}"{if $Cell.Data.useImage} class="link-icon"{/if}
                    data-dialog-title="{$Cell.Data.dialogTitle}" data-modal-operation="{$Cell.Data.name}" data-content-link="{$Cell.Data.link}">

                    {if $Cell.Data.useImage}
                        <i class="{$IconByOperationMap[$Cell.OperationName]}"></i>
                    {else}
                        {$Cell.Data.caption}
                    {/if}

                </a>

            {elseif $Cell.Data.type == 'inline_edit'}

                <span class="inline_edit_controls{if $Cell.Data.useImage} default-fade-in fade-out-on-hover{/if} text-nowrap">

                    <a href="#" class="js-inline_edit_init{if $Cell.Data.useImage} link-icon{/if}" title="{$Cell.Data.editCaption}">
                        {if $Cell.Data.useImage}<i class="icon-edit"></i>{else}{$Cell.Data.editCaption}{/if}
                    </a>

                    <a href="#" style="display: none;" class="js-inline_edit_cancel{if $Cell.Data.useImage} link-icon{/if}" title="{$Cell.Data.cancelCaption}">
                        <span class="text-danger text-lg">{if $Cell.Data.useImage}<i class="icon-remove"></i>{else}{$Cell.Data.cancelCaption}{/if}</span>
                    </a>

                    <a href="#" style="display: none;" class="js-inline_edit_commit{if $Cell.Data.useImage} link-icon{/if}" title="{$Cell.Data.commitCaption}">
                        <span class="text-success text-lg">{if $Cell.Data.useImage}<i class="icon-ok"></i>{else}{$Cell.Data.commitCaption}{/if}</span>
                    </a>

                    {foreach from=$Cell.Data.keys key=name item=value}
                        <input type="hidden" name="{$name}" value="{$value}" />
                    {/foreach}

                </span>

            {else}

                {$Cell.Data}

            {/if}

        {if $UseOperationContainer}</span>{/if}
    {/if}

{/foreach}