This file is just a list of examples of what we want to implement with zizithra.  Later we should be able to move it to the examples directory for use there.
---------------------------------------
<? for($i = 1; $i < 3; $i++): ?>
	<?/* This next tag should work even if short tags is not on, all tags should be compiled to full "<?php" tags *> */?>
	<?=$i?><br/> 
<? endfor; ?>

<? $a = 3; ?>
<? if($a == 3): ?>
	a = 3<br/>
<? endif; ?>

<? $array = array(1 => 'one', 2 => 'two', 3 => 'three') ?>
<? foreach($array as $key => $vaL): ?>
	<?=$key?> =&lt; <?=$val?><br/>
<? endforeach; ?>
---------------------------------------
<?for $i = 1; $i < 3; $i++ ?>
	<?=$i?><br/> 
<?/for?>

<? $a = 3; ?>
<?if $a == 3 ?>
	a = 3<br/>
<?/if?>

<? $array = array(1 => 'one', 2 => 'two', 3 => 'three') ?>
<?foreach $array as $key => $val ?>
	<?=$key?> =&lt; <?=$val?><br/>
<?/foreach?>
---------------------------------------