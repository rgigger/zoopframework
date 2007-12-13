<form action="{$virtualUrl}" method="post">
	{$person->firstname}
	<br>
	{$person->lastname}
	<br>
	<input type="button" value="done" onclick="document.location = '{$zoneUrl}/list'">
</form>