<input
	type="hidden"
	class="form-control"
	{include file="editors/editor_options.tpl" Editor=$AutocompleteComboBox}
	data-placeholder="{$Captions->GetMessageString('PleaseSelect')}"
	data-url="{$AutocompleteComboBox->GetDataUrl()}"
	data-minimal-input-length="{$AutocompleteComboBox->getMinimumInputLength()}"
	{if $AutocompleteComboBox->getFormatResult()}
		data-format-result="{$AutocompleteComboBox->getFormatResult()|escape}"
	{/if}
	{if $AutocompleteComboBox->getFormatSelection()}
		data-format-selection="{$AutocompleteComboBox->getFormatSelection()|escape}"
	{/if}
	{if $AutocompleteComboBox->GetReadonly()}readonly="readonly"{/if}
	{if $AutocompleteComboBox->getAllowClear()}data-allowClear="true"{/if}
	value="{$AutocompleteComboBox->GetValue()}"
/>