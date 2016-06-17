 {include file="page-header.tpl" pageTitle=$Grid.Title pageWithForm=true}

<form class="js-pgui-edit-form form-horizontal" enctype="multipart/form-data" method="POST" action="{$Grid.FormAction}" data-type="insert">

    {include file='forms/form-actions.tpl' top=true}

    {if not $Grid.ErrorMessage eq ''}
        {include file='common/message.tpl' type='danger' dismissable=true caption=$Captions->GetMessageString('ErrorsDuringInsertProcess') content=$Grid.ErrorMessage}
    {/if}

    {if not $Grid.Message eq ''}
        {include file='common/message.tpl' type='success' dismissable=true content=$Grid.Message}
    {/if}

    <div class="row">
        <div class="col-lg-8">
            <fieldset>
                <input id="submit-action" name="submit1" type="hidden" value="save">
                {foreach key=HiddenValueName item=HiddenValue from=$HiddenValues}
                    <input type="hidden" name="{$HiddenValueName}" value="{$HiddenValue}">
                {/foreach}

                {foreach item=Column from=$Grid.Columns name=EditColumns}
                    {include file='forms/form-group.tpl' Column=$Column}
                {/foreach}

                {include file='forms/form-required.tpl'}
            </fieldset>
        </div>
    </div>

    <div class="error-container"></div>

    {include file='forms/form-actions.tpl' top=false}
</form>

<script type="text/javascript">
    {literal}
        function InsertForm_initd(editors) {
            {/literal}{$Grid.OnLoadScript}{literal};
        }
    {/literal}
</script>