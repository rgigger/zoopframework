<?php
/**
 * Returns true if the current page was requested with the GET method
 *
 * @return boolean
 */
function RequestIsGet()
{
	return $_SERVER['REQUEST_METHOD'] == 'GET' ? 1 : 0;
}

/**
 * Returns true if the current page was requested with the POST method
 *
 * @return boolean
 */
function RequestIsPost()
{
	return $_SERVER['REQUEST_METHOD'] == 'POST' ? 1 : 0;
}

/**
 * Evaluates the POST variables and creates a standard "year-month-day Hour24:minute:second -7:00" date from a POSTed form
 * The fields in the form should be as follows:
 * <name>Month, <name>Day, <name>Year
 * <name>Hour, <name>Minute, <name>Second
 * <name>Meridian (<-- "am" or "pm")
 * 
 * @param $name Prefix of the POST variables to evaluate
 * @return string Date string
 */

function GetFormDate($name, $src = null)
{
	if(!$src)
		$src = $_POST;
	
	if(is_array($src[$name]))
	{
		$year = $src[$name]['Date_Year'];
		$month = $src[$name]['Date_Month'];
		$day = $src[$name]['Date_Day'];
	}
	else
	{
		$name = "{$name}_";
		$month = $src[$name . 'Month'];
		$day = $src[$name . 'Day'];
		$year = $src[$name . 'Year'];
	}
	
	return "$year-$month-$day";
}

/*
there should be separate functions for date and time
function GetPostDate($name)
{
	//echo_r($_POST);
	$name = "{$name}_";
	$month = $_POST[$name . 'Month'];
	$day = $_POST[$name . 'Day'];
	$year = $_POST[$name . 'Year'];
	$hour = $_POST[$name . 'Hour'];
	$minute = $_POST[$name . 'Minute'];
	$second = $_POST[$name . 'Second'];
	$meridian = $_POST[$name . 'Meridian'];
	
	$hour = $meridian == 'pm' ? ($hour + 12) : $hour;
	
	return "$year-$month-$day $hour:$minute:$second -7:00";
}
*/


/**
 * print_r the contents of the variable $var along with a full function backtrace to indicate where in the program this is occurring (great for debugging)
 *
 * @param mixed $var Variable to print
 * @param boolean $supressBacktrace True if you wish to suppress the backtrace (default: False)
 */
function echo_r($var, $supressBacktrace = 0)
{
	if(!$supressBacktrace)
		EchoBacktrace();
	echo '<pre>';
	print_r($var);
	echo '</pre>';
}

/**
 * Redirect the client browser to $url
 *
 * @param string $url URL to which to send them
 */
function Redirect($url = NULL)
{
	if(!$url)
		$url = virtual_url;
	header("location: $url");
	die();
}

/**
 * Redirects the client to a URL relative to the project (index.php/<url>)
 *
 * @param string $virtualPath Path inside the project to which to send them
 */
function BaseRedirect($virtualPath)
{
	Redirect(script_url . '/' . $virtualPath);
}


/**
 * Echos an HTML-formatted backtrace
 *
 * @param unknown_type $value I don't know what this is for
 */
function EchoBacktrace($value='')
{
	echo FormatBacktraceHtml(debug_backtrace());
}


/**
 * Generates and prints backtrace information in readable HTML
 *
 * @param debug_backtrace() $backtraceInfo The results of a debug_backtrace() function call
 */
function FormatBacktraceHtml($backtraceInfo)
{
	// debug_print_backtrace();
	// return;
	//echo_r($backtraceInfo);
?>
<table border="1">
	<tr>
		<th>File</th><th>Line</th><th>Function</th>
	</tr>
	<?php  foreach($backtraceInfo as $thisRow): 
		$lineInfo = FormateBacktraceLineHtml($thisRow);
	?><tr>
		<td><?php echo $lineInfo['file']; ?></td>
		<td><?php echo $lineInfo['line']; ?></td>
		<td><?php echo $lineInfo['function']; ?></td>
	</tr>
	<?php endforeach; ?>
</table>
<?php
}

function FormateBacktraceLineHtml($lineInfo)
{
	// echo_r($lineInfo);
	$result = array();
	$result['file'] = isset($lineInfo['file']) ? $lineInfo['file'] : 'php function';
	$result['line'] = isset($lineInfo['line']) ? $lineInfo['line'] : 'na';
	$result['function'] = FormatBacktraceFunctionCellHtml($lineInfo);
	return $result;
}

function FormatBacktraceFunctionCellHtml($lineInfo)
{
//	echo "here we are<br>";
//	var_dump($lineInfo);
//	echo_r($lineInfo);
	$call = '';
	$call .= isset($lineInfo['class']) ? ($lineInfo['class'] . $lineInfo['type']) : '';
	$call .= $lineInfo['function'] . '(';
	$argStrings = array();
	foreach($lineInfo['args'] as $thisArg)
	{
//		echo '<b>arg = ' . $thisArg . '</b><br>';
//		echo '<b>type = ' . gettype($thisArg) . '</b>';
//		echo_r($thisArg);
		switch(gettype($thisArg))
		{
			case 'string':
				$argStrings[] = '"' . $thisArg . '"';
				break;
			case 'integer':
				$argStrings[] = $thisArg;
				break;
			case 'array':
				$argStrings[] = '&lt;array&gt;';
				break;
			case 'object':
				$argStrings[] = '&lt;object&gt;';
				break;
			case 'resource':
				$argStrings[] = 'resource: ' . $thisArg;
				break;
			case 'boolean':
				$argStrings[] = 'boolean: -' . $thisArg . '-';
				break;
			case 'NULL':
				$argStrings[] = 'NULL';
				break;
			default:
				die('unhandled type ' . gettype($thisArg));
				break;
		}
		
//		echo '<strong>call = ' . $call . '</strong><br>';
	}
	
	$call .= implode(', ', $argStrings);
	
	$call .= ')';
	
	return $call;
}



/**
 * Given a filename, outputs the contents of the file to the client
 *
 * @param string $filename Path and filename of the file to output
 */
function EchoStaticFile($filename)
{
	$fp = fopen($filename, 'rb');
	
	//	send the headers
	//header("Content-Type: image/png");	//	figure out what should really be done here
	header("Content-Length: " . filesize($filename));	//	also we want to be able to properly set the cache headers here
	
	fpassthru($fp);
}


/**
 * Returns a list of files in the specified directory, optionally filtered by the values in array $extention
 *
 * @param string $path $path Directory path to scan
 * @param array $params Array of file extensions (without leading ".")
 * @return array Array of filenames found in the directory
 */
function ListDir($path, $params)
{
	$entries = array();
	$d = dir($path);
	while (false !== ($entry = $d->read()))
	{
		$keep = 1;
		if(isset($params['extentions']))
		{
			$keep = 0;
			$extention = GetFileExtention($entry);
			
			if(in_array($extention, $params['extentions']))
				//echo $extention . "\n";
				$keep = 1;
		}
		
		if($keep)
			$entries[] = $entry;
	}
	$d->close();
	
	return $entries;
}


/**
 * Return the extension of the given filename
 *
 * @param string $filename Filename to process
 * @return string extension of the filename
 */
function GetFileExtention($filename)
{
	$parts = explode('.', $filename);
	return array_pop($parts);
}

/**
 * Appends a prefix to a string, if given prefix doesn't already exist
 *
 * @param string $string String to analyze
 * @param string $prefix Prefix to append (if it isn't already there)
 * @return string Prefixed string
 */
function str_prefix($string, $prefix)
{
	return substr($string, 0, strlen($prefix)) == $prefix ? 1 : 0;
}

function StripMagicQuotesFromPost()
{
	_StripMagicQuotes($_POST);
}

function _StripMagicQuotes(&$cur)
{
	foreach($cur as $key => $val)
	{
		if(gettype($val) == 'string')
			$cur[$key] = stripslashes($val);
		else if(gettype($val) == 'array')
			_StripMagicQuotes($cur[$key]);
	}
}

//	adapted from the excellent phpass security package
function GetRandomBytes($count, $allowFallback = false)
{
	$output = '';
	if(($fh = fopen('/dev/urandom', 'rb')))
	{
		$output = fread($fh, $count);
		fclose($fh);
	}

	if (strlen($output) < $count)
	{
		if(!$allowFallback)
			trigger_error('system could not generate enough random data');
		
		$output = '';
		for ($i = 0; $i < $count; $i += 16) {
			$this->random_state =
			    md5(microtime() . $this->random_state);
			$output .=
			    pack('H*', md5($this->random_state));
		}
		$output = substr($output, 0, $count);
	}

	return $output;
}
