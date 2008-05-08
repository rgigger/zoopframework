<form method="post" name="main_form" action="{$virtualUrl}">	
<table>
	{assign var=primaryKey value=$object->getPrimaryKey()}
	{foreach from=$object key=fieldName item=fieldValue}
	<tr>
		<td>{$fieldName}:</td>
		{if $object->primaryKeyAssignedByDb() && in_array($fieldName, $primaryKey)}
			<td>{$fieldValue|default:"&lt;self-assigned&gt;"}</td>
		{else}
			{if isset($fieldInfo[$fieldName].type)}
				{if $fieldInfo[$fieldName].type == 'textarea'}
					<td><textarea name="fields[{$fieldName}]" rows="4" cols="50">{$fieldValue}</textarea></td>
				{else}
					<td><input type="text" name="fields[{$fieldName}]" value="{$fieldValue}"></td>
				{/if}
			{elseif $object->getFieldOptions($fieldName)}
				{assign var=relationship value=$object->getFieldOptions($fieldName)}
				<td>{html_options name="fields[$fieldName]" selected=$fieldValue options=$relationship->getOptions()}</td>
			{else}
				<td><input type="text" name="fields[{$fieldName}]" value="{$fieldValue}"></td>
			{/if}
		{/if}
	</tr>
	{/foreach}
</table>
<input type="submit" name="save" value="save">
</form>
