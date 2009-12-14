<?

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     function
 * Name:     popupwindow
 * Purpose:  make an onclick attribute for popping up a window
 * -------------------------------------------------------------
 */
function smarty_function_popupwindow($params, &$smarty)
{
    if(!isset($params['url']))
	{
        $smarty->trigger_error("assign: missing 'url' parameter");
        return;
    }
    
    $url = $params['url'];
    
    if(isset($params['left']))
    	$leftClause = 'left=' . $params['left'] . ',';
    else
    	$leftClause = '';
    
    if(isset($params['top']))
		$topClause = 'top=' . $params['top'] . ',';
	else
		$topClause = '';
    
    if(isset($params['width']))
    	$width = $params['width'];
    else
    	$width = 900;
    
    if(isset($params['height']))
		$height = $params['height'];
	else
		$height = 700;
	
	if(isset($params['name']))
		$name = $params['name'];
	else
		$name = 'popup';
    
    return "onclick=\"now = new Date(); printpopup = window.open('$url?cache_limiter=private&' + now.getTime(), '$name', '${leftClause}${topClause}width=$width,height=$height,toolbar=0,location=0,directories=1,resizable=1,status=0,menubar=0,scrollbars=1,locationbar=0'); printpopup.focus(); return false;\"";

    
    $smarty->assign($var, $value);
}

/* vim: set expandtab: */

?>

