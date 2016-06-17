<input
    {include file="editors/editor_options.tpl" Editor=$ColorEdit}
    class="form-control"
    type="color"
    value="{$ColorEdit->GetValue()}"
    {style_block}
        min-width: 100px;
        padding: 0;
    {/style_block}
>