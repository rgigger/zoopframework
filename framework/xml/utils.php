<?php
function XmlDisplayNode($node)
{
	echo '<ol>';
	$children = $node->getChildren();
	for($thisChild = $children->current(); $children->valid(); $thisChild = $children->next())
	{
		echo '<li>';
		echo 	$thisChild->getName();
		if($thisChild->hasContent())
		{
			$content = $thisChild->getContent();
			echo '(' . $content['content'] . ')';
		}
		XmlDisplayNode($thisChild);
		echo '</li>';
	}
	echo '</ol>';
}