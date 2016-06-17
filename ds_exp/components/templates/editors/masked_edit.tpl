<input
    {include file="editors/editor_options.tpl" Editor=$MaskedEdit}
    class="form-control"
    masked="true"
    mask="{$MaskedEdit->GetMask()}"
    type="text"
    value="{$MaskedEdit->GetValue()}"
>
<div class="masked-edit-hint">{$MaskedEdit->GetHint()}</div>