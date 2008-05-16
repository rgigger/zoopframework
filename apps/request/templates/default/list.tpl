<table>
	<tr>
		<th>id</th>
		<th>name</th>
		<th>desc</th>
		<th>owner</th>
		<th>completed</th>
	</tr>
	{foreach from=$requests item=thisRequest}
		<tr>
			<td>{$thisRequest->id}</td>
			<td>{$thisRequest->name}</td>
			<td>{$thisRequest->description}</td>
			<td>{$thisRequest->Person->getName()}</td>
			<td>{if $thisRequest->completed == 't'}x{else}&nbsp;{/if}</td>
		</tr>
	{/foreach}
</table>
<input type="button" value="add" onclick="document.location = '{$scriptUrl}/edit'">