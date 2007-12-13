<table>
	<tr>
		<th>id</th>
		<th>firstname</th>
		<th>lastname</th>
	</tr>
	{foreach from=$people item=thisPerson}
		<tr>
			<td>{$thisPerson->id}</td>
			<td>{$thisPerson->firstname}</td>
			<td>{$thisPerson->lastname}</td>
		</tr>
	{/foreach}
</table>
<br>
<input type="button" value="add" onclick="document.location = '{$zoneUrl}/edit';">