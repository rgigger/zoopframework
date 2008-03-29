<ol>
	<li>Try deleting Definitely There from the database.  It will come back when you refresh the page because of the call to _getOne().  But there will always be just one.</li>
	<li>Every time you refresh the page it creates another "Common Name" entry from the _insert command.</li>
	<li>Every time you refresh the page it creates another "Unique Person" entry from the _create command.</li>
</ol>

<br><br>

<strong>_getOne</strong><br>
{$promised->getString()}<br>
<br>

<strong>_create</strong><br>
{$new->getString()}<br>
<br>

<strong>_find</strong><br>
{foreach from=$all item=this}
	{$this->getString()}<br>
{/foreach}
<br>

<a href="{$zoneUrl}">back</a>
