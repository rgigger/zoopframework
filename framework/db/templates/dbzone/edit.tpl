<form method="post" name="main_form" action="{$virtualUrl}">	
<table>
	{assign var=primaryKey value=$object->getPrimaryKey()}
	{foreach from=$object key=fieldName item=fieldValue}
	<tr>
		<td>{$fieldName}:</td>
		{if $object->primaryKeyAssignedByDb() && in_array($fieldName, $primaryKey)}
			<td>{$fieldValue|default:"&lt;self-assigned&gt;"}</td>
		{else}
			<td><input type="text" name="fields[{$fieldName}]" value="{$fieldValue}"></td>
		{/if}
	</tr>
	{/foreach}
</table>
<input type="submit" name="save" value="save">
</form>
