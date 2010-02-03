<?php
/**
 * Smarty {html_dboptions} function plugin
 *
 * Type:     function<br>
 * Name:     html_dboptions<br>
 * Input:<br>
 *           - name       (optional) - string default "select"
 *			 - tablename  (required 
 *           - selected   (optional) - string default not set
 * Purpose:  Pulls a list of options from the database and prints the list of <option> tags generated from
 *           the passed parameters
 * @param array
 * @param Smarty
 * @return string
 * @uses smarty_function_html_options()
 */

function smarty_function_html_dboptions($params, &$smarty)
{
    require_once $smarty->_get_plugin_filepath('function','html_options');
    
    $tableName = $params['tablename'];
    if( isset($params['namefield']) )
    	$nameField = $params['namefield'];
    else
    	$nameField = 'name';
    
    $params['options'] = SqlFetchSimpleMap("SELECT id, :nameField AS name FROM :tableName:identifier order by id", 'id', 'name', 
							array('nameField:identifier' => $nameField, 'tableName' => $tableName));
    
    return smarty_function_html_options($params, $smarty);
}
?>
