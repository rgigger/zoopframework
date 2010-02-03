<form method="post" name="main_form" action="{$virtualUrl}">	
<table>
	{foreach from=$object key=fieldName item=fieldValue}
	<tr>
		<td>{$fieldName}:</td>
		{if $object->getFieldOptions($fieldName)}
			{assign var=relationship value=$object->getFieldOptions($fieldName)}
			<td>{$relationship->getInfo()}</td>
		{else}
			<td>{$fieldValue}</td>
		{/if}
	</tr>
	{/foreach}
</table>
<input type="button" name="done" value="done" onclick="document.location = '{$zoneUrl}/list'">
</form>
