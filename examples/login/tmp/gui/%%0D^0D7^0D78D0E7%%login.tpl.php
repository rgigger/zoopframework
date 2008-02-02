<?php /* Smarty version 2.6.14, created on 2008-02-02 01:46:58
         compiled from default/login.tpl */ ?>
<?php if ($this->_tpl_vars['bad']): ?>
<p>Invalid username or password</p>
<?php endif; ?>
<form method="post" action="<?php echo $this->_tpl_vars['virtualUrl']; ?>
">
	<input name="username" type="text"><br>
	<input name="password" type="password"><br>
	<input type="submit">
</form>