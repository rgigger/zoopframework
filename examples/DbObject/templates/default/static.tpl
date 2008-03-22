1. Try deleting Jane Doe, John Doe, or Bill Johnson from the database.  They will all come back when you refresh the page because of the calls to _getOne().  But there will always be just one.<br>
2. Every time you refresh the page it creates another "Common Name" entry from the _insert command.<br>
3. Every time you refresh the page it creates another "Unique Person" entry from the _create command.<br>
<br>

<strong>_find</strong><br>
{foreach from=$all item=this}
	{$this->getString()}<br>
{/foreach}
<br>

<strong>_findByWhere</strong><br>
{foreach from=$odd item=this}
	{$this->getString()}<br>
{/foreach}
<br>

<strong>_findBySql</strong><br>
{foreach from=$sqled item=this}
	{$this->getString()}<br>
{/foreach}
<br>

<strong>_findOne</strong><br>
{$one->getString()}<br>
<br>

<strong>_create</strong><br>
{$special->getString()}<br>
<a href="{$zoneUrl}">back</a>
