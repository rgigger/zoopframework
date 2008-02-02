{if $bad}
<p>Invalid username or password</p>
{/if}
<form method="post" action="{$virtualUrl}">
	<input name="username" type="text"><br>
	<input name="password" type="password"><br>
	<input type="submit">
</form>