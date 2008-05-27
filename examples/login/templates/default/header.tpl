{if $loggedIn}
	<p>You are logged in.</p>
	<p>Click <a href="{$zoneUrl}/logout">here</a> to log out</p>
{else}
	You are <strong>not</strong> logged in.
	<p>If you try to access <a href="{$zoneUrl}/protected">pageProtected</a> you will be kicked back out to the login page.</p>
	<p>Try username: test, password: test</p>
{/if}
