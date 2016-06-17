{assign var="ContentBlockClass" value="col-md-8 col-md-offset-2"}

{capture assign="ContentBlock"}

<div class="alert alert-danger">
    <h3>{$Captions->GetMessageString('Error')}</h3>

    {$Captions->GetMessageString('CriticalErrorSuggestions')}

    <br /><br />

    <h4>{$Captions->GetMessageString('ErrorDetails')}:</h4>

    <div style="padding-left: 20px;">
        {$Message}
    </div>
</div>

{/capture}


{include file="common/layout.tpl"}
