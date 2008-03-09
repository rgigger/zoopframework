<?php /* Smarty version 2.6.14, created on 2008-03-09 03:34:04
         compiled from layouts/main.tpl */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
<html>
  <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Main Title</title>
	<BASE HREF="<?php echo $this->_tpl_vars['scriptUrl']; ?>
">
    <link href="public/css/write.css" rel="stylesheet" type="text/css">
  </head>
  <body>
	<div id="document">
		<div id="header">Header Info</div>
		<div id="nav">nav</div>
		<div id="body"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['TEMPLATE_CONTENT']), 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>
	</div>
  </body>
</html>