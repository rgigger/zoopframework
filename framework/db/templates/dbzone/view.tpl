<form method="post" name="main_form" action="{$virtualUrl}">	
<table>
	{foreach from=$object key=fieldName item=fieldValue}
	<tr>
		<td>{$fieldName}:</td>
		<td>{$fieldValue}</td>
	</tr>
	{/foreach}
</table>
<input type="button" name="done" value="done" onclick="document.location = '{$zoneUrl}/list'">
</form>
