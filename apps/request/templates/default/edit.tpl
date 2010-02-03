<table>
	<tr>
		<td>name:</td>
		<td><input name="_record[name]" value="{$request->name}" style="width: 400px"></td>
	</tr>
	<tr>
		<td>description:</td>
		<td><textarea name="_record[description]" style="width: 400px; height: 100px">{$request->description}</textarea></td>
	</tr>
	<tr>
		<td>priority:</td>
		<td>{html_dboptions name="_record[priority_id]" tablename="priority" selected=$request->priority_id}</td>
	</tr>
	<tr>
		<td colspan="2">
			<input name="submitAction" value="Save" type="submit">
			<input name="submitAction" value="Delete" type="submit" style="float: right">
		</td>
	</tr>
</table>
