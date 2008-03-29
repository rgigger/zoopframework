<strong>_find</strong><br>
{foreach from=$all item=this}
	{$this->getString()}<br>
{/foreach}
<br>

<strong>new row</strong><br>
{$gp->getString()}<br>
<br>

<a href="{$zoneUrl}">back</a>
