<data>
	{foreach from=$entries item=thisEntry}
    <event 
        start="{$thisEntry->start|date_format:'%b %d %Y %H:%M:%S %Z'}"
		{if $thisEntry->is_duration}end="{$thisEntry->end|date_format:'%b %d %Y %H:%M:%S %Z'}"{/if}
        isDuration="{if $thisEntry->is_duration}true{else}false{/if}"
        title="{$thisEntry->title}"
        >
        </event>
	{/foreach}
</data>
