<table width="100%">
	<tr>
		<th>id</th>
		<th>start</th>
		<th>end</th>
		<th>title</th>
		<th width="20">Has Duration</th>
	</tr>
{foreach from=$entries item=thisEntry}
	<tr>
		<td>{$thisEntry->id}</td>
		<td>{$thisEntry->start}</td>
		<td>{$thisEntry->end}</td>
		<td><a href="{$zoneUrl}/edit/{$thisEntry->id}">{$thisEntry->title}</a></td>
		<td>{if $thisEntry->is_duration == 't'}yes{else}no{/if}</td>
	</tr>
{/foreach}
</table>
