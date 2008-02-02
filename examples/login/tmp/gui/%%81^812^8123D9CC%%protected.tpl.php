<?php /* Smarty version 2.6.14, created on 2008-02-02 14:07:47
         compiled from default/protected.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'printr', 'default/protected.tpl', 1, false),)), $this); ?>
<?php echo smarty_function_printr(array('var' => $this->_tpl_vars['session']), $this);?>

Because you are logged in you aren't redirected back to the login page.