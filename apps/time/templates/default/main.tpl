{assign var=entries value=$person->Entry}
<table border="1">
	<tr>
		<th>Start Date/Time</th>
		<th>End Date/Time</th>
	</tr>
	{foreach from=$entries item=thisEntry}
		{if $thisEntry->endtime} {* this should really be done in the domain object *}
		<tr>
			<td>{$thisEntry->starttime}</td>
			<td>{$thisEntry->endtime}</td>
		</tr>
		{/if}
	{foreachelse}
		<p>no entries</p>
	{/foreach}
</table>

<p><strong>Open Session:</strong></p>
{assign var=openEntry value=$person->getOpenEntry()}
{if $openEntry}
	<p>Start Date/Time: {$openEntry->starttime} <input type="submit" onclick="return submitForm('stop')" value="Clock Out"></p>
{else}
	<p>You have no open entry.  <input type="submit" onclick="return submitForm('start')" value="Clock In"></p>
{/if}

<p><strong>Create New Entry Manually:</strong></p>
<p>Start Date: {html_select_date prefix="start_"} | {html_select_time use_24_hours=false prefix="start_"}</p>
<p>End&nbsp; Date: {html_select_date prefix="end_"} | {html_select_time use_24_hours=false prefix="end_"}</p>
<input type="submit" value="Add Entry" onclick="return submitForm('create')">