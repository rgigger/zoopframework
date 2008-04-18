<?php /* Smarty version 2.6.14, created on 2008-04-10 09:20:36
         compiled from dbzone/edit.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'dbzone/edit.tpl', 1, false),array('modifier', 'default', 'dbzone/edit.tpl', 8, false),)), $this); ?>
<form method="post" name="main_form" action="<?php echo ((is_array($_tmp=$this->_tpl_vars['virtualUrl'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
">	
<table>
	<?php $this->assign('primaryKey', $this->_tpl_vars['object']->getPrimaryKey()); ?>
	<?php $_from = $this->_tpl_vars['object']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['fieldName'] => $this->_tpl_vars['fieldValue']):
?>
	<tr>
		<td><?php echo ((is_array($_tmp=$this->_tpl_vars['fieldName'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
:</td>
		<?php if ($this->_tpl_vars['object']->primaryKeyAssignedByDb() && in_array ( $this->_tpl_vars['fieldName'] , $this->_tpl_vars['primaryKey'] )): ?>
			<td><?php echo ((is_array($_tmp=@$this->_tpl_vars['fieldValue'])) ? $this->_run_mod_handler('default', true, $_tmp, "&lt;self-assigned&gt;") : smarty_modifier_default($_tmp, "&lt;self-assigned&gt;")); ?>
</td>
		<?php else: ?>
			<td><input type="text" name="fields[<?php echo ((is_array($_tmp=$this->_tpl_vars['fieldName'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['fieldValue'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
"></td>
		<?php endif; ?>
	</tr>
	<?php endforeach; endif; unset($_from); ?>
</table>
<input type="submit" name="save" value="save">
</form>