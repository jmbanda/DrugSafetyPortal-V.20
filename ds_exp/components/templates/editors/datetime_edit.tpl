<div class="input-group pgui-date-time-edit js-datetime-editor-wrap">
    <input
        {include file="editors/editor_options.tpl" Editor=$DateTimeEdit}
        class="form-control"
        type="text"
        value="{$DateTimeEdit->GetValue()}"
        data-picker-format="{$DateTimeEdit->GetFormat()}"
        data-picker-show-time="{if $DateTimeEdit->GetShowsTime()}true{else}false{/if}">
    <span class="input-group-addon" style="cursor: pointer">
        <span class="icon-datetime-picker"></span>
    </span>
</div>
