<?php /* Smarty version 2.6.14, created on 2008-04-09 14:21:41
         compiled from dbzone/list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'dbzone/list.tpl', 12, false),)), $this); ?>
<?php echo '
<script>
function submitForm(action, id)
{
	document.main_form.action.value = action;
	document.main_form.id.value = id;
	document.main_form.submit();
	return false;
}
'; ?>

</script>
<form method="post" name="main_form" action="<?php echo ((is_array($_tmp=$this->_tpl_vars['virtualUrl'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
">	
	<table border="1">
		<?php $_from = $this->_tpl_vars['objects']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['thisObject']):
?>
		<tr>
			<?php $_from = $this->_tpl_vars['thisObject']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['fieldValue']):
?>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['fieldValue'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</td>
			<?php endforeach; endif; unset($_from); ?>
			<td><a href="#" onclick="return submitForm('edit', <?php echo ((is_array($_tmp=$this->_tpl_vars['thisObject']->getId())) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
)">edit</a></td>
			<td><a href="#" onclick="return submitForm('view', <?php echo ((is_array($_tmp=$this->_tpl_vars['thisObject']->getId())) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
)">view</a></td>
			<td><a href="#" onclick="return submitForm('delete', <?php echo ((is_array($_tmp=$this->_tpl_vars['thisObject']->getId())) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
)">delete</a></td>
		</tr>
		<?php endforeach; endif; unset($_from); ?>	
	</table>
	<input type="hidden" name="id" value="_default_">
	<input type="hidden" name="action" value="_default_">
	<input type="button" name="add" value="add" onclick="submitForm('add', null)">
</form>