<link rel="stylesheet" href="http://tablesorter.com/themes/blue/style.css" type="text/css" media="print, projection, screen" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js"></script>
<script type="text/javascript" src="http://tablesorter.com/jquery.tablesorter.js"></script>
{*
<script type="text/javascript" src="http://localhost/~rick/jqplug/jquery.jeditable.pack.js"></script>
<script type="text/javascript" src="http://localhost/~rick/jqplug/jquery.inplace.js"></script>
*}

<script type="text/javascript">
{literal}
	
$.tablesorter.addParser({
	id: 'priority', 
	is: function(s) { 
		return false; 
	}, 
	format: function(s) {
		if(s == 'Low')
			return 1;
		else if(s == 'Medium')
			return 2;
		else if(s == 'High')
			return 3;
		else
			return 0;
		// return s.toLowerCase().replace(/low/,1).replace(/medium/,2).replace(/high/,3); 
	}, 
	type: 'numeric' 
});

$(function() {
	$("#list_table").tablesorter({
		widgets: ['zebra'],
		headers: {
			3: {sorter: 'priority'}
		}
	});
	
	
	// $("#list_table tr").each(function() {
	// 	if(this.cells[this.cells.length - 1].tagName == 'TD')
	// 	{
	// 		var id = $(this.cells[0]).text();
	// 		$(this.cells[this.cells.length - 1]).editInPlace({
	// 			url: zoneUrl + '/setField',
	// 			params: "field=completed&id=" + id,
	// 			field_type: "select",
	// 			select_options: "yes,no"
	// 		});
	// 		$(this.cells[3]).editInPlace({
	// 			url: zoneUrl + '/setField',
	// 			params: "field=priority&id=" + id,
	// 			field_type: "select",
	// 			select_options: "Low,Medium,High"
	// 		});
	// 	}
	// });
});
{/literal}

</script>

<div id="main">
  	<table id="list_table" class="tablesorter" border="0" cellpadding="0" cellspacing="1">
	<thead>
	<tr>
		<th width="25">id</th>
		<th>name</th>
		<th>desc</th>
		<th width="60">priority</th>
		<th width="60">owner</th>
		<th width="80">completed</th>
	</tr>
	</thead>
	<tbody>
	{foreach from=$requests item=thisRequest}
		<tr>
			<td>{$thisRequest->id}</td>
			<td><a href="{$zoneUrl}/edit/{$thisRequest->id}">{$thisRequest->name}</a></td>
			<td>{$thisRequest->description}</td>
			<td>{$thisRequest->priority}</td>
			<td>{$thisRequest->Person->getName()}</td>
			<td id="completed_{$thisRequest->id}">{if $thisRequest->completed == 't'}yes{else}no{/if}</td>
		</tr>
	{/foreach}
	</tbody>
</table>
<input id="add" type="button" value="add" onclick="document.location = '{$scriptUrl}/edit'" ACCESSKEY="a">

</div>
{literal}
{/literal}