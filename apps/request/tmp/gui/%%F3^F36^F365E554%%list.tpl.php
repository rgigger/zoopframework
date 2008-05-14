<?php /* Smarty version 2.6.14, created on 2008-05-13 19:04:54
         compiled from default/list.tpl */ ?>
<?php $_from = $this->_tpl_vars['requests']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['thisRequest']):
?>
	<?php echo $this->_tpl_vars['thisRequest']; ?>

<?php endforeach; endif; unset($_from); ?>
<input type="button" value="add">