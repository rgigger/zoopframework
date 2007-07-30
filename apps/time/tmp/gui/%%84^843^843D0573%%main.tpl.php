<?php /* Smarty version 2.6.14, created on 2007-07-05 02:12:52
         compiled from default/main.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_select_date', 'default/main.tpl', 28, false),array('function', 'html_select_time', 'default/main.tpl', 28, false),)), $this); ?>
<?php $this->assign('entries', $this->_tpl_vars['person']->getMany('Entry')); ?>
<table border="1">
	<tr>
		<th>Start Date/Time</th>
		<th>End Date/Time</th>
	</tr>
	<?php $_from = $this->_tpl_vars['entries']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['thisEntry']):
?>
		<?php if ($this->_tpl_vars['thisEntry']->endtime): ?> 		<tr>
			<td><?php echo $this->_tpl_vars['thisEntry']->starttime; ?>
</td>
			<td><?php echo $this->_tpl_vars['thisEntry']->endtime; ?>
</td>
		</tr>
		<?php endif; ?>
	<?php endforeach; else: ?>
		<p>no entries</p>
	<?php endif; unset($_from); ?>
</table>

<p><strong>Open Session:</strong></p>
<?php $this->assign('openEntry', $this->_tpl_vars['person']->getOpenEntry());  if ($this->_tpl_vars['openEntry']): ?>
	<p>Start Date/Time: <?php echo $this->_tpl_vars['openEntry']->starttime; ?>
 <input type="submit" onclick="return submitForm('stop')" value="Clock Out"></p>
<?php else: ?>
	<p>You have no open entry.  <input type="submit" onclick="return submitForm('start')" value="Clock In"></p>
<?php endif; ?>

<p><strong>Create New Entry Manually:</strong></p>
<p>Start Date: <?php echo smarty_function_html_select_date(array('prefix' => 'start_'), $this);?>
 | <?php echo smarty_function_html_select_time(array('use_24_hours' => false,'prefix' => 'start_'), $this);?>
</p>
<p>End&nbsp; Date: <?php echo smarty_function_html_select_date(array('prefix' => 'end_'), $this);?>
 | <?php echo smarty_function_html_select_time(array('use_24_hours' => false,'prefix' => 'end_'), $this);?>
</p>
<input type="submit" value="Add Entry" onclick="return submitForm('create')">