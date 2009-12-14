<?

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     function
 * Name:     printclick
 * Purpose:  make an onclick attribute for popping up a print window
 * -------------------------------------------------------------
 */
function smarty_function_printclick($params, &$smarty)
{
    if(!isset($params['url']))
	{
        $smarty->trigger_error("assign: missing 'var' parameter");
        return;
    }
    $url = $params['url'];
    
    if(isset($params['left']))
    	$left = $params['left'];
    else
    	$left = 0;
    
    if(isset($params['top']))
		$top = $params['top'];
	else
		$top = 0;
    
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
	{
		$time = time();
		$name = "printpopup$time";
	}
	if(isset($params['nolimiter']))
	{
		$cachelimiter = '\'';
	}
	else
	{
		$cachelimiter = "?cache_limiter=private&' + now.getTime()";
	}
		
    return "onclick=\"now = new Date(); printpopup = window.open('{$url}{$cachelimiter}, '$name', 'left=$left,top=$top,width=$width,height=$height,toolbar=0,location=0,directories=1,resizable=1,status=0,menubar=0,scrollbars=1,locationbar=0'); printpopup.focus(); return false;\"";

    
    $smarty->assign($var, $value);
}

/* vim: set expandtab: */

?>

