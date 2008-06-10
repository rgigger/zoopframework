{globalappend var=yuiModules value='event'}
{globalappend var=yuiModules value='datatable'}
<script>


// alert(YAHOO.util.Event);
{literal}
function PageOnload()
{
	// alert('setting up table');
    YAHOO.example.EnhanceFromMarkup = new function() {
		
        var myColumnDefs = [
            {key:"id",label:"id",formatter:YAHOO.widget.DataTable.formatNumber,sortable:true},
            {key:"name",label:"name", sortable:true,},
            {key:"desc",label:"desc", sortable:true},
            {key:"owner",label:"owner", sortable:true},
            {key:"completed",label:"completed", sortable:true, editor:"radio", editorOptions:{radioOptions:["yes","no"],disableBtns:true}}
        ];

        this.parseNumberFromCurrency = function(sString) {
            // Remove dollar sign and make it a float
            return parseFloat(sString.substring(1));
        };

        this.myDataSource = new YAHOO.util.DataSource(YAHOO.util.Dom.get("accounts"));
        this.myDataSource.responseType = YAHOO.util.DataSource.TYPE_HTMLTABLE;
        this.myDataSource.responseSchema = {
            fields: [{key:"id", parser:YAHOO.util.DataSource.parsNumber},
                    {key:"name"},
                    {key:"desc"},
                    {key:"owner"},
                    {key:"completed"}
            ]
        };

        this.myDataTable = new YAHOO.widget.DataTable("markup", myColumnDefs, this.myDataSource,
                {sortedBy:{key:"id",dir:"desc"}}
        );

        // Set up editing flow
        this.highlightEditableCell = function(oArgs) {
            var elCell = oArgs.target;
            if(YAHOO.util.Dom.hasClass(elCell, "yui-dt-editable")) {
                this.highlightCell(elCell);
            }
        };
        this.myDataTable.subscribe("cellMouseoverEvent", this.highlightEditableCell);
        this.myDataTable.subscribe("cellMouseoutEvent", this.myDataTable.onEventUnhighlightCell);
        this.myDataTable.subscribe("cellClickEvent", this.myDataTable.onEventShowCellEditor);

        // Hook into custom event to customize save-flow of "radio" editor
        this.myDataTable.subscribe("editorUpdateEvent", function(oArgs) {
			var id = oArgs.editor.record._oData.id;
			var value = oArgs.editor.value;
			
			var postData = "id=" + id;
			var postData = postData + "&value=" + value;
			var url = gScriptUrl + '/setCompleted';
			var callback =
			{
			  success: function(o) {
				// document.getElementById('result').innerHTML = o.responseText;
			  },
			  failure: function() {alert('it failed')},
			  argument: ['foo','bar']
			};
			var request = YAHOO.util.Connect.asyncRequest('POST', url, callback, postData);
			this.saveCellEditor();
        });
        this.myDataTable.subscribe("editorBlurEvent", function(oArgs) {
			this.cancelCellEditor();
        });
    };
	// alert('done');
}

{/literal}
</script>
<div id="markup">
<table id="accounts">
	<thead>
	<tr>
		<th>id</th>
		<th>name</th>
		<th>desc</th>
		<th>owner</th>
		<th>completed</th>
	</tr>
	</thead>
	<tbody>
	{foreach from=$requests item=thisRequest}
		<tr>
			<td>{$thisRequest->id}</td>
			<td>{$thisRequest->name}</td>
			<td>{$thisRequest->description}</td>
			<td>{$thisRequest->Person->getName()}</td>
			<td>{if $thisRequest->completed == 't'}yes{else}no{/if}</td>
		</tr>
	{/foreach}
	</tbody>
</table>
</div>
<input type="button" value="add" onclick="document.location = '{$scriptUrl}/edit'">
