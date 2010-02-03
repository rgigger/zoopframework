<script>

var config = {json var=$config};

{literal}
$(document).ready(function() {
	initTemplate();
});

function initTemplate()
{
	//	initialize the first menu
	var menu = $('#template .fieldMenu');
	for(var fieldName in config.fields)
		menu.append('<option value="' + fieldName + '">' + config.fields[fieldName].display + '</option>');
	
	//	initialize the second menu
	updateOperatorMenu($('.rowWrapper:first'));
	
	//	initialize the operand field
	updateOperandField($('.rowWrapper:first'));
	
	//	add the first row to the real table
	var firstRow = $('#template .subWrapper:first').clone();
	firstRow.find('.minusButton').attr({disabled: 'true'})
	$('#editable').append(firstRow);
}

function fieldMenuChanged(fieldMenu)
{
	var row = $(fieldMenu).parents('.rowWrapper');
	updateOperatorMenu(row);
	updateOperandField(row);
}

function operatorMenuChanged(fieldMenu)
{
	var row = $(fieldMenu).parents('.rowWrapper');
	updateOperandField(row);
}

function updateOperatorMenu(row)
{
	var fieldMenu = row.find('.fieldMenu');
	var fieldName = fieldMenu.val();
	var operatorMenu = row.find('.operatorMenu');
	operatorMenu.empty();
	var type = config.fields[fieldName].type;
	for(var i in config.types[type].operators)
	{
		var operatorName = config.types[type].operators[i];
		var operatorDisplayName = config.operators[operatorName].display;
		operatorMenu.append('<option value="' + operatorName + '">' + operatorDisplayName + '</option>');
	}
}

function updateOperandField(row)
{
	//	get jQuery references to all of the desired elements
	var fieldMenu = row.find('.fieldMenu');
	var fieldName = fieldMenu.val();
	var operatorMenu = row.find('.operatorMenu');
	var operatorName = operatorMenu.val();
	var typeName = config.fields[fieldName].type;
	var widgitName = config.type_operator_map[typeName][operatorName].widgit;
	var templateCell = $('#template .' + widgitName);
	var targetCell = row.find('.operandBox');
	
	//	put in the correct element
	targetCell.empty();
	targetCell.append( templateCell.html() );
	
	setOperandOptions(fieldName, widgitName, targetCell);
}

function setOperandOptions(fieldName, widgitName, targetCell)
{
	var option;
	if(widgitName == 'int_menu')
		options = config.widgits[widgitName].list;
	else
		options = config.fields[fieldName].list;
	
	var target = targetCell.find('select');
	if(options && target)
	{
		for(var key in options)
			target.append('<option value="' + key + '">' + options[key] + '</option>');
		target.css({width: '100%'});
	}
}

function newRow(theButton)
{
	var newRow = $('#template .rowWrapper:first').clone();
	var theRow = $(theButton).parents('.rowWrapper:first');
	theRow.after( newRow );
}

function newSubRow(theButton)
{
	var newRow = $('#template .rowWrapper:first').clone();
	var theRow = $(theButton).parents('.subWrapper:first');
	theRow.after( newRow );
}

function removeRow(theButton)
{
	$(theButton).parents('.rowWrapper:first').remove();
}

function newSub(theButton)
{
	var newRow = $('#template .subWrapper:first').clone();
	var theRow = $(theButton).parents('.rowWrapper:first');
	theRow.after( newRow );
}

function newSubSub(theButton)
{
	var newRow = $('#template .subWrapper:first').clone();
	var theRow = $(theButton).parents('.subWrapper:first');
	theRow.after( newRow );
}

function removeSub(theButton)
{
	$(theButton).parents('.subWrapper:first').remove();
}

function saveFilterData(showAlert)
{
	var data = {};	
	var curContainer = $('#editable');
	gatherFilterData(data, curContainer);
	var stringed = JSON.stringify(data);
	if(showAlert)
		alert(stringed);
	$('#filterData').val(stringed);
}

function gatherFilterData(data, cur)
{
	data.subs = [];
	data.fields = [];
	
	cur.children('.subWrapper').each(function() {
		var newNode = {anyall: $(this).find('.anyAllWrapper:first .anyall:first').val()};
		data.subs.push(newNode);
		gatherFilterData(newNode, $(this).find('.rowContainer'));
	});
	
	cur.children('.rowWrapper').each(function() {
		var operandValues = {};
		$(this).find('.operandBox:first .operand').each(function() {
			operandValues[$(this).attr('name')] = $(this).val();
		});
		
		data.fields.push({
			field: $(this).find('.fieldMenu:first').val(),
			operator: $(this).find('.operatorMenu:first').val(),
			operandValues: operandValues
		});
	});
}

{/literal}
</script>

<style>
{literal}
{/literal}
</style>

<div id="template" style="border: 1px solid black; display: none">
	<div class="subWrapper" style="border: 0px solid red">
		<div class="anyAllWrapper" style="border: 0px solid green">
			<div style="float: left">
				<select class="anyall">
					<option value="all">all</option>
					<option value="any">any</option>
				</select> of the following rules:
			</div>
			<div style="float: right">
				<table>
					<tr>
						<td>
							<table border="0" width="100%">
								<tr>
									<td width="30px"><div style="float: left; width: 30px"><button class="minusButton" onclick="removeSub(this); return false;">-</button></div></td>
									<td width="30px"><div style="float: left; width: 30px"><button class="plusButton" onclick="newSubRow(this); return false;">+</button></div></td>
									<td width="30px"><div style="float: left; width: 30px"><button class="subButton" onclick="newSubSub(this); return false;">..</button></div></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
			<br style="clear: both"/>
		</div>
		<div style="border: 0px solid orange; margin-left: 20px" class="rowContainer">
			<div class="rowWrapper" style="border: 0px solid blue;">
				<table border="0" width="100%">
					<tr>
						<td>
							<table border="0" width="100%">
								<tr>
									<td width="20%"><select class="fieldMenu" style="width: 100%" onchange="fieldMenuChanged(this); return false;"></select></td>
									<td width="20%"><select class="operatorMenu" style="width: 100%" onchange="operatorMenuChanged(this); return false;"></select></td>
								</tr>
							</table>
						</td>
						<td>
							<table border="0" width="100%">
								<tr>
									<td class="operandBox"></td>
									<td width="30px"><div style="float: left; width: 30px"><button class="minusButton" onclick="removeRow(this); return false;">-</button></div></td>
									<td width="30px"><div style="float: left; width: 30px"><button class="plusButton" onclick="newRow(this); return false;">+</button></div></td>
									<td width="30px"><div style="float: left; width: 30px"><button class="subButton" onclick="newSub(this); return false;">..</button></div></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	
	<div class="date">
		<input class="operand" name="date">
	</div>
	<div class="date_range">
		<input class="operand" name="date1">
		<input class="operand" name="date2">
	</div>
	<div class="select">
		<select class="operand" name="menu"></select>
	</div>
	<div class="select_range">
		<select class="operand" name="menu1"></select>
		<select class="operand" name="menu2"></select>
	</div>
	<div class="select_multiple">
		<select class="operand" name="multi" multiple="multiple"></select>
	</div>
	<div class="int_menu">
		<table width="100%"><tr><td width="10%">
			<input class="operand" name="amount" type="text" size="4">
		</td>
		<td>
			<select class="operand" name="units"></select>
		</td></tr></table>
	</div>
</div>

<div style="border: 1px; width: 100%" id="editable">
</div>
<input type="hidden" name="filterData" id="filterData" value="asdfff">
<button onclick="saveFilterData(true); return false;" type="button">Save</button><button onclick="saveFilterData(false);">Save For Reals</button>