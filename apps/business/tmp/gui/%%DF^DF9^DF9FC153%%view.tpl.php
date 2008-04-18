<?php /* Smarty version 2.6.14, created on 2008-04-10 09:22:05
         compiled from dbzone/view.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'dbzone/view.tpl', 1, false),)), $this); ?>
<form method="post" name="main_form" action="<?php echo ((is_array($_tmp=$this->_tpl_vars['virtualUrl'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
">	
<table>
	<?php $_from = $this->_tpl_vars['object']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['fieldName'] => $this->_tpl_vars['fieldValue']):
?>
	<tr>
		<td><?php echo ((is_array($_tmp=$this->_tpl_vars['fieldName'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
:</td>
		<td><?php echo ((is_array($_tmp=$this->_tpl_vars['fieldValue'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</td>
	</tr>
	<?php endforeach; endif; unset($_from); ?>
</table>
<input type="button" name="done" value="done" onclick="document.location = '<?php echo ((is_array($_tmp=$this->_tpl_vars['zoneUrl'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
/list'">
</form>