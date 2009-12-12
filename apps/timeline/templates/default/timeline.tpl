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
<script src="http://static.simile.mit.edu/timeline/api-2.3.0/timeline-api.js?bundle=true" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="public/css/basic.css" media="screen" />
</head>
<body onload="onLoad();" onresize="onResize();">
<form action="{$virtualUrl}" name="main_form" method="post">
<div id="wrap">
<div id="header">

<h1><a href="#">Site Title</a></h1>
<h2>Site Sub-title</h2>

</div>

<div class="middle">

<script>
{literal}
var tl;
 function onLoad() {
	var eventSource = new Timeline.DefaultEventSource();
	var bandInfos = [
		Timeline.createBandInfo({
			eventSource:    eventSource,
			date:           "Jun 28 2006 00:00:00 GMT",
			width:          "50%", 
			intervalUnit:   Timeline.DateTime.MONTH, 
			intervalPixels: 100
		}),
		Timeline.createBandInfo({
			eventSource:    eventSource,
			date:           "Jun 28 2006 00:00:00 GMT",
			width:          "50%", 
			intervalUnit:   Timeline.DateTime.YEAR, 
			intervalPixels: 200	
		})
	];
	bandInfos[1].syncWith = 0;
	bandInfos[1].highlight = true;
	tl = Timeline.create(document.getElementById("my-timeline"), bandInfos);
	
	var xmlPage = zoneUrl + '/data/data.xml';
	// xmlPage = 'public/example1.xml';
	Timeline.loadXML(xmlPage, function(xml, url) { eventSource.loadXML(xml, url); });
	
	
	// alert(xmlPage);
	// tl = Timeline.create(document.getElementById("my-timeline"), bandInfos);
	// Timeline.loadXML(xmlPage, function(xml, url) { eventSource.loadXML(xml, url); });
	
 }

 var resizeTimerID = null;
 function onResize() {
     if (resizeTimerID == null) {
         resizeTimerID = window.setTimeout(function() {
             resizeTimerID = null;
             tl.layout();
         }, 500);
     }
 }
{/literal}
</script>

<h2>Page Title</h2>

<div id="my-timeline" style="height: 400px; border: 1px solid #aaa"></div>

</div>
		
<div class="right">
		
<h2>Navigation</h2>

<ul>

<li><a href="{$scriptUrl}/timeline/">Timeline</a></li>
<li><a href="{$scriptUrl}/entries">List Entries</a></li>
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
