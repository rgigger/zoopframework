<table border="1">
	<tr>
		<th>id</th>
		<th>hair color</th>
		<th>eye color</th>
		<th>height</th>
		<th>gender</th>
	</tr>
{foreach from=$all item=thisOne}
	<tr>
		<td>{$thisOne->getId()}</td>
		<td>{$thisOne->hairColor}</td>
		<td>{$thisOne->eyeColor}</td>
		<td>{$thisOne->height}</td>
		<td>{$thisOne->gender}</td>
	</tr>
{/foreach}
</table>