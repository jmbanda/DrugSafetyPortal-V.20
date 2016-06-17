{if !$Uploader->GetReadOnly()}

    {if $RenderText}
        {if $Uploader->GetShowImage() and !$HideImage}
            <img src="{$Uploader->GetLink()}" style="max-width: 100%;">
            <br/>
        {/if}

        <div style="margin: 1em 0;">
            <div class="btn-group" data-toggle-name="{$Uploader->GetName()}_action" data-toggle="buttons-radio">
                <button type="button" value="Keep" class="active btn btn-default" data-toggle="button">{$Captions->GetMessageString('KeepImage')}</button>
                <button type="button" value="Remove" class="btn btn-default" data-toggle="button">{$Captions->GetMessageString('RemoveImage')}</button>
                <button id="{$Uploader->GetName()}-replace-image-button" type="button" value="Replace" class="btn btn-default" data-toggle="button">{$Captions->GetMessageString('ReplaceImage')}</button>
            </div>
        </div>
        <input type="hidden" name="{$Uploader->GetName()}_action" value="Keep" />

        <div class="file-upload-control">
            <input
                {$Validators.InputAttributes}
                {if $Uploader->GetLink()}data-has-file="true"{/if}
                data-editor="true"
                data-editor-class="ImageUploaderEditor"
                data-field-name="{$Uploader->GetName()}"
                type="file"
                name="{$Uploader->GetName()}_filename"
                {style_block} {$Uploader->GetCustomAttributes()} {/style_block}>
        </div>
{/if}

{else}
{if $RenderText}
{if $Uploader->GetShowImage() and !$HideImage}
    <img src="{$Uploader->GetLink()}"><br/>
{else}
    <a class="image" target="_blank" title="download" href="{$Uploader->GetLink()|escapeurl}">Download file</a>
{/if}
{/if}

{/if}