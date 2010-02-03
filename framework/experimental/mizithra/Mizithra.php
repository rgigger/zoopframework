<?php
class Mizithra
{
	function display($template)
	{
		$content = file_get_contents(app_dir . '/mizithra/test.miz');
		
		$ldq = preg_quote('<?', '~');
        $rdq = preg_quote('?>', '~');
		preg_match_all("~{$ldq}\s*(.*?)\s*{$rdq}~s", $content, $match);
		$text_blocks = preg_split("~{$ldq}.*?{$rdq}~s", $content);
		
		// Interleave the compiled contents and text blocks to get the final result. 
		$compiled_content = '';
		$compiledTags = $match[1];
		for ($i = 0, $for_max = count($compiledTags); $i < $for_max; $i++)
		{
            $compiled_content .= $text_blocks[$i] . '<?php ' . $compiledTags[$i] . '?>';
        }
        $compiled_content .= $text_blocks[$i];
        // echo $compiled_content;
		eval('?>' . $compiled_content);
	}
}