{if ($TextEdit->getPrefix() or $TextEdit->getSuffix())}
    <div class="input-group">
{/if}
{if $TextEdit->getPrefix()}
    <span class="input-group-addon">{$TextEdit->getPrefix()}</span>
{/if}
<input
    {include file="editors/editor_options.tpl" Editor=$TextEdit}
    class="form-control"
    value="{$TextEdit->GetHTMLValue()}"
    {if $TextEdit->getPlaceholder()}
        placeholder="{$TextEdit->getPlaceholder()}"
    {/if}
    {if $TextEdit->GetPasswordMode()}
        type="password"
    {else}
        type="text"
    {/if}
    {if $TextEdit->GetMaxLength()}
        maxlength="{$TextEdit->GetMaxLength()}"
    {/if}
>
{if $TextEdit->getSuffix()}
    <span class="input-group-addon">{$TextEdit->getSuffix()}</span>
{/if}
{if $TextEdit->getPrefix() or $TextEdit->getSuffix()}
    </div>
{/if}
