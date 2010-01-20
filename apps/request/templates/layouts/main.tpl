{capture assign="contentOutput"}{include file=$TEMPLATE_CONTENT}{/capture}
<html>
<head>

<BASE HREF="{$scriptUrl}">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
{*
<script src="public/js/jquery.table.addrow.js"></script>
*}
<script>
var scriptUrl = '{$scriptUrl}';
var virtualUrl = '{$scriptUrl}';
var zoneUrl = '{$scriptUrl}';
</script>	
	
{*
<script>
{literal}
function submitForm(action)
{
	document.main_form.actionField.value = action;
	document.main_form.submit();
}
{/literal}
</script>
*}

<link rel="stylesheet" href="public/css/request.css" type="text/css">
</head>
{*
{if isset($fileUpload) && $fileUpload}
	<form action="{$virtualUrl}" enctype="multipart/form-data" name="main_form" method="post">
{else}
	<form action="{$virtualUrl}" name="main_form" method="post">
{/if}
*}
<form action="{$virtualUrl}" name="main_form" method="post">
<table border="0" cellspacing="0" cellpadding="0" width="100%" height="100%">
<tr>
	<td><img src="public/images/pixel.gif" height="30" width="100"></td>
	<td valign="bottom">
	{if isset($showTopNav) && $showTopNav}
		<table cellspacing="0">
			<tr>
				{foreach from=$topnav key=name item=link}
				<td class="tab">
					<a href="{$scriptUrl}/{$link}" class="topNavLink">
					<div height="100%" width="100%">
						{$name}
					</div>
					</a>
				</td>
				<td><img src="resources/pixel.gif" height="0" width="1"></td>
				{/foreach}
			</tr>
		</table>
	{/if}
	</td>
	<td align="right" class="subTitle">name &nbsp;</td>
</tr>
<tr><td colspan="3" class="navColorDark" style="height: 2px"></td></tr>

{*
{if isset($showSteps) && $showSteps}
	{if isset($navbar)}
	<tr>
		<td class="subtitle">Steps</td>
		<td>
			<table cellspacing="10">
			<tr>
				{foreach from=$navbar key=linkno item=name}
				<td {if $linkno == $curpage}class="currentstep"{else}class="step"{/if}>
					{$name}
				</td>
				{/foreach}
			</tr>
			</table>
		</td>
	</tr>
	{/if}
{/if}
*}

<tr>
	<td valign="top" height="100%" class="navColorLight">{if isset($showLeftNav) && $showLeftNav}{include file="leftNav.tpl"}{/if}</td>
	<td valign="top" height="100%" align="left" width="100%" class="mainContentCell" colspan="2">
	<!-- Begin main content area -->
	{ $contentOutput }
	<!-- End main content area -->
	</td>
</tr>
</table>

<input type="hidden" name="actionField" value="default">
</form>
</body>
</html>
