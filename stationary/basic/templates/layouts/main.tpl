{capture assign="contentOutput"}{include file=$TEMPLATE_CONTENT}{/capture}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Language" content="English" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<BASE HREF="{$scriptUrl}">
<script>
var scriptUrl = '{$scriptUrl}';
var virtualUrl = '{$scriptUrl}';
var zoneUrl = '{$scriptUrl}';
</script>	
<link rel="stylesheet" type="text/css" href="public/css/basic.css" media="screen" />
</head>
<body>
<form action="{$virtualUrl}" name="main_form" method="post">
<div id="wrap">
<div id="header">

<h1><a href="#">Site Title</a></h1>
<h2>Site Sub-title</h2>

</div>

<div class="middle">

<!-- Begin main content area -->
{ $contentOutput }
<!-- End main content area -->

</div>
		
<div class="right">
		
<h2>Navigation</h2>

<ul>

<li><a href="http://www.example.com/one">Menu Link One</a></li>
<li><a href="http://www.example.com/two">Menu Link Two</a></li>
<li><a href="http://www.example.com/three">Menu Link Three</a></li>

</ul>
		
</div>

<div id="clear"></div>

</div>

<div id="footer">
Built with <a target="_external" href="http://code.google.com/p/zoop">Zoop Framework</a>
</div>
</body>
</html>


{*
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
*}
