<?php /* Smarty version 2.6.14, created on 2008-04-09 14:20:33
         compiled from layouts/main.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'layouts/main.tpl', 4, false),)), $this); ?>
<html>
<head>

<BASE HREF="<?php echo ((is_array($_tmp=$this->_tpl_vars['scriptUrl'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
">

<script>
<?php echo '
/*
function submitForm(action)
{
	document.main_form.actionField.value = action;
	document.main_form.submit();
}
*/
'; ?>

</script>
<link rel="stylesheet" href="public/css/business.css" type="text/css">
</head>
<body <?php if (isset ( $this->_tpl_vars['focus'] )): ?>onload="document.main_form.<?php echo ((is_array($_tmp=$this->_tpl_vars['focus'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
.focus();"<?php endif; ?>>
<table border="0" cellspacing="0" cellpadding="0" width="100%" height="100%">
<tr>
	<td><img src="public/images/pixel.gif" height="30" width="100"></td>
	<td valign="bottom">
	<?php if (isset ( $this->_tpl_vars['showTopNav'] ) && $this->_tpl_vars['showTopNav']): ?>
		<table cellspacing="0">
			<tr>
				<?php $_from = $this->_tpl_vars['topnav']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['name'] => $this->_tpl_vars['link']):
?>
				<td class="tab">
					<a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['scriptUrl'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['link'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" class="topNavLink">
					<div height="100%" width="100%">
						<?php echo ((is_array($_tmp=$this->_tpl_vars['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

					</div>
					</a>
				</td>
				<td><img src="resources/pixel.gif" height="0" width="1"></td>
				<?php endforeach; endif; unset($_from); ?>
			</tr>
		</table>
	<?php endif; ?>
	</td>
	<td align="right" class="subTitle">name &nbsp;</td>
</tr>
<tr><td colspan="3" class="navColorDark"><img src="resources/pixel.gif" height="2" width="0"></td></tr>


<tr>
	<td valign="top" height="100%" class="navColorLight"><?php if (isset ( $this->_tpl_vars['showLeftNav'] ) && $this->_tpl_vars['showLeftNav']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "leftNav.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?></td>
	<td valign="top" height="100%" align="left" width="100%" class="mainContentCell" colspan="2">
	<!-- Begin main content area -->
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['TEMPLATE_CONTENT']), 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<!-- End main content area -->
	</td>
</tr>
</table>

<input type="hidden" name="actionField" value="default">
</body>
</html>