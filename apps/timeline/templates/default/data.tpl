<data>
	{foreach from=$entries item=thisEntry}
    <event 
        start="{$thisEntry->start}"
        end="{$thisEntry->end}"
        isDuration="{if $thisEntry->is_duration}true{else}false{/if}"
        title="{$thisEntry->title}"
    >
    </event>
	{/foreach}
</data>