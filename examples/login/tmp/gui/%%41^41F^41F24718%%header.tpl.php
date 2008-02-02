<?php /* Smarty version 2.6.14, created on 2008-02-02 14:07:17
         compiled from default/header.tpl */ ?>
<?php if ($this->_tpl_vars['loggedIn']): ?>
	<p>You are logged in.</p>
	<p>Click <a href="<?php echo $this->_tpl_vars['zoneUrl']; ?>
/logout">here</a> to log out</p>
<?php else: ?>
	You are <strong>not</strong> logged in.
	<p>If you try to access <a href="<?php echo $this->_tpl_vars['zoneUrl']; ?>
/protected">pageProtected</a> you will be kicked back out to the login page.</p>
<?php endif; ?>