<style>
{literal}
input.text {
	width: 400px;
}
{/literal}
</style>
{openform action="$virtualUrl"}
	<table>
		<tr>
			<td>what goes here?</td>
			<td>
				{input type="radio" name="is_duration" value="f" checked="1" data_object=$entry data_field="is_duration"} bullet 
				{input type="radio" name="is_duration" value="t" data_object=$entry data_field="is_duration"} bar
			</td>
		</tr>
		<tr>
			<td>start date:</td>
			<td>{input name="start" value="May 28 2006 09:00:00 GMT" class="text" data_object=$entry data_field="start"}</td>
		</tr>
		<tr>
			<td>end date:</td>
			<td>{input name="end" value="Jun 15 2006 09:00:00 GMT" class="text" data_object=$entry data_field="end"}</td>
		</tr>
		<tr>
			<td>title:</td>
			<td>{input name="title" class="text" data_object=$entry data_field="title" default="some silly title"}</td>
		</tr>
		<tr>
			<td colspan="2" align="center">{input type="submit" style="width: 200px"}</td>
		</tr>
	</table>
</form>
{closeform}
