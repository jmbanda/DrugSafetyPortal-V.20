<div class="form-group">
    <label class="col-sm-3 control-label" for="{$Column.Id}">
        {$Column.Caption}
        {if $Column.Required}
            <span class="required-mark">*</span>
        {/if}
        {include file="edit_field_options.tpl" Column=$Column}
    </label>
    <div class="col-sm-9 col-input">
        {$Column.Editor}
    </div>
</div>