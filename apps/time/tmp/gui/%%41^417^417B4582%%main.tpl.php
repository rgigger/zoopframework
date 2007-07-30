<?php /* Smarty version 2.6.14, created on 2007-07-05 01:59:40
         compiled from layouts/main.tpl */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Time</title>
<BASE HREF="<?php echo $this->_tpl_vars['scriptUrl']; ?>
">
<script type="text/javascript">
<?php echo '
function submitForm(action)
{
	document.main_form.actionField.value = action;
	//document.main_form.onsubmit();
	document.main_form.submit();
	return false;
}
'; ?>

</script>
</head>
<body>
	<form name="main_form" action="<?php echo $this->_tpl_vars['virtualUrl']; ?>
" method="POST">
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['TEMPLATE_CONTENT']), 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<input type="hidden" name="actionField">
	</form>
</body>
</html>