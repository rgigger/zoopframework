Try deleting Definitely There from the database.  It will come back when you refresh the page because of the call to _getOne().  But there will always be just one.

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

<a href="{$zoneUrl}">back</a>
