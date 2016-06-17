<div class="alert alert-{$type}{if !isset($dismissable) or $dismissable} alert-dismissable{/if}"{if isset($displayTime)} data-display-time="{$displayTime}"{/if}>

    {if !isset($dismissable) or $dismissable}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    {/if}

    {if isset($caption) and $caption}
        <strong>{$caption}</strong><br>
    {/if}

    <div class="js-content">{$content}</div>

</div>