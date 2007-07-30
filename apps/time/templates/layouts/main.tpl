<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Time</title>
<BASE HREF="{$scriptUrl}">
<script type="text/javascript">
{literal}
function submitForm(action)
{
	document.main_form.actionField.value = action;
	//document.main_form.onsubmit();
	document.main_form.submit();
	return false;
}
{/literal}
</script>
</head>
<body>
	<form name="main_form" action="{$virtualUrl}" method="POST">
		{include file="$TEMPLATE_CONTENT"}
		<input type="hidden" name="actionField">
	</form>
</body>
</html>