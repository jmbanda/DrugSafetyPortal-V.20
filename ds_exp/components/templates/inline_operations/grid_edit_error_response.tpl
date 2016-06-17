<?xml version="1.0" encoding="utf-8" ?>
<errorinfo>
    <errormessage><![CDATA[
        {include file='common/message.tpl' type='danger' dismissable=false caption=$Captions->GetMessageString($ErrorCaption) content=$ErrorMessage displayTime=$MessageDisplayTime}
    ]]></errormessage>
{foreach from=$ColumnEditors key=name item=editor name=Editors}
    <editor name="{$name}">
        <html>
            <![CDATA[
                {$editor.Html}
            ]]>
        </html>
        <script>
            <![CDATA[
                {$editor.Script}
            ]]>
        </script>
    </editor>
{/foreach}
</errorinfo>

