{assign var="ContentBlockClass" value="col-md-8 col-md-offset-2"}

{capture assign="ContentBlock"}
<div class="alert alert-danger">
    <h3>{$Captions->GetMessageString('ErrorsDuringDataRetrieving')}</h3>

    {$ErrorMessage}

    {if ($DisplayDebugInfo eq 1)}
        <hr/>
        <h3>Additional exception info:</h3>
        <strong>File:</strong> {$File} <br/>
        <strong>Line:</strong> {$Line} <br/>
        <strong>Trace:</strong> {$Trace}
    {/if}

</div>
{/capture}

{* Base template *}
{include file="common/layout.tpl"}