{literal}
<script>
function submitForm(action, id)
{
	document.main_form.action.value = action;
	document.main_form.id.value = id;
	document.main_form.submit();
	return false;
}
{/literal}
</script>
<form method="post" name="main_form" action="{$virtualUrl}">	
	<table border="1">
		<tr>
			{foreach from=$table item=thisColumn}
				{if $thisColumn->name <> 'filepath'}
					<td>{$thisColumn->name}</td>
				{/if}
			{/foreach}
			<td>Edit</td>
			<td>View</td>
			<td>Delete</td>
		</tr>
		{foreach from=$objects item=thisObject}
		<tr>
			{foreach from=$thisObject key=fieldName item=fieldValue}
				{if $fieldName <> 'filepath'}
					{if $fieldName == 'url'}
						<td>{if $fieldValue}<a href="{$fieldValue}">&nbsp;url&nbsp;</a>{else}&nbsp;{/if}</td>
					{elseif $thisObject->getFieldOptions($fieldName)}
						{assign var=relationship value=$thisObject->getFieldOptions($fieldName)}
						<td>{$relationship->getInfo()}</td>
					{else}
						<td>{$fieldValue|nl2br}</td>
					{/if}
				{/if}
			{/foreach}
			<td><a href="#" onclick="return submitForm('edit', {$thisObject->getId()})">edit</a></td>
			<td><a href="#" onclick="return submitForm('view', {$thisObject->getId()})">view</a></td>
			<td><a href="#" onclick="return submitForm('delete', {$thisObject->getId()})">delete</a></td>
		</tr>
		{/foreach}	
	</table>
	<input type="hidden" name="id" value="_default_">
	<input type="hidden" name="action" value="_default_">
	<input type="button" name="add" value="add" onclick="submitForm('add', null)">
</form>
