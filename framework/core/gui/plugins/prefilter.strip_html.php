<?
function smarty_prefilter_strip_html($tpl_source, &$smarty)
{
    $source = ereg_replace('{(\$[^|}]*)}','{\\1|escape:"html"}',$tpl_source);
    return $source;
}
?>
