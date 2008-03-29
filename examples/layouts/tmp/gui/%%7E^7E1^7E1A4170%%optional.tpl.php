<?php /* Smarty version 2.6.14, created on 2008-03-20 23:17:03
         compiled from layouts/optional.tpl */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
<html>
  <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Optional Title</title>
	<BASE HREF="<?php echo $this->_tpl_vars['scriptUrl']; ?>
">
  </head>
  <body style="background-color: blue">
	<div id="document" align="center">
		<div>Plain layout</div>
		<div id="body"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['TEMPLATE_CONTENT']), 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>
	</div>
  </body>
</html>