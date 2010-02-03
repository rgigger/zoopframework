<?php
function smarty_function_init($params, &$smarty)
{
	$modules = isset($params['type']) ? $params['type'] : array('dnd', 'ajax', 'input');
	
	foreach($modules as $thisModule)
	{
		GuiInit::$thisModule();
	}
}

class GuiInit
{
	function dnd()
	{
		
	}
	
	function ajax()
	{
		
	}
	
	function input()
	{
?>
<script>
function GuiValidateForm()
{
	var ok = true;
	var message = 'Please correct the following mistakes in the form:\n';

	var constraints = document.getElementsByTagName("constraint");
//	var names = {};
	for(var i = 0; i < constraints.length; i++)
	{
		var res = GuiCheckConstraint(constraints[i]);
		if(!res['ok'])
		{
			ok = false;
			message += "\n" + res['message'];
			var onemessage = res['message'];
		}
		else
			var onemessage = '';
		
		var status = document.getElementById('gui_status_' + constraints[i].name);
		
		if(status && onemessage)
			status.innerText = onemessage;
	}

	if(!ok)
	{
		alert(message);
		return false;
	}

	return true;
}

function GuiCheckConstraint(constraint)
{
	var res = {ok: true, message: ''};

	switch(constraint.type)
	{
		case 'minlen':
		var input = document.getElementById(constraint.name);
		if(input.value.length < constraint.value)
		{
			res['ok'] = false;
			res['message'] = constraint.inline;
		}
		break;
		case 'sameas':
		var input = document.getElementById(constraint.name);
		var input2 = document.getElementById(constraint.value);
		if(input.value != input2.value)
		{
			res['ok'] = false;
			res['message'] = constraint.inline;
		}
		break;
		default:
		throw "Bad constraint type";
		break;
	}
	return res;
}
</script>
<?php
	}
}