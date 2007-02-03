{foreach from=$fileNames item=thisFileName}
	<a href="{$scriptUrl}/view/{$thisFileName}">{$thisFileName}</a><br>
{/foreach}