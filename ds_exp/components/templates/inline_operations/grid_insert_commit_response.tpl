<?xml version="1.0" {if $encoding}encoding="{$encoding}"{/if}?>
<response>
<message>{$message}</message>
<message_display_time>{$displayTime}</message_display_time>
<fieldvalues>
{if $AllowDeleteSelected}
    <fieldvalue name="sm_multi_delete_column">
		<value>
			<![CDATA[
			<input type="checkbox" name="inline_inserted_rec_{$RecordUID}" id="inline_inserted_rec_{$RecordUID}">
			{foreach item=PrimaryKeyValue from=$PrimaryKeys name=CPkValues}
				<input type="hidden" name="inline_inserted_rec_{$RecordUID}_pk{$smarty.foreach.CPkValues.index}" value="{$PrimaryKeyValue}">
			{/foreach}
			]]>
		</value>
    </fieldvalue>
{/if}
{if $HasDetails}
	<fieldvalue name="details">
		<value>
			<![CDATA[
				{include file="list/details_icon.tpl" Details=$Details}
			]]>
		</value>
	</fieldvalue>
{/if}
{foreach item=Cell from=$Actions}
	{if $Cell.Data}
		<fieldvalue name="{$Cell.OperationName}">
			<value>
				<![CDATA[
					{php}
						$this->assign("ArrayWithCurrentAction", array($this->get_template_vars('Cell')));
					{/php}
					{include file="list/action_list.tpl" Actions=$ArrayWithCurrentAction UseOperationContainer=false}
				]]>
			</value>
		</fieldvalue>
	{/if}
{/foreach}
{foreach from=$Columns key=name item=Column}
    <fieldvalue name="{$name}">
		<value>
			<![CDATA[
				{$Column.Value}
			]]>
		</value>
		<afterrowcontrol>
			<![CDATA[
				{$Column.AfterRowControl}
			]]>
		</afterrowcontrol>
		<style>
			<![CDATA[
				{$Column.Style}
			]]>
		</style>
    </fieldvalue>
{/foreach}
</fieldvalues>
</response>