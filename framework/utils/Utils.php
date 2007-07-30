<?php
function RequestIsGet()
{
	return $_SERVER['REQUEST_METHOD'] == 'GET' ? 1 : 0;
}

function RequestIsPost()
{
	return $_SERVER['REQUEST_METHOD'] == 'POST' ? 1 : 0;
}

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

function echo_r($var)
{
	//EchoBacktrace();
	echo '<pre>';
	print_r($var);
	echo '</pre>';
}

function Redirect($url)
{
	header("location: $url");
	die();
}

function EchoBacktrace($value='')
{
	echo '<pre>';
	debug_print_backtrace();
	echo '</pre>';
}

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
	<?php foreach($backtraceInfo as $thisRow): 
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

function EchoStaticFile($filename)
{
	$fp = fopen($filename, 'rb');
	
	//	send the headers
	//header("Content-Type: image/png");	//	figure out what should really be done here
	header("Content-Length: " . filesize($filename));	//	also we want to be able to properly set the cache headers here
	
	fpassthru($fp);
}


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

function GetFileExtention($filename)
{
	$parts = explode('.', $filename);
	return array_pop($parts);
}

if(version_compare(PHP_VERSION, '5.0', '<'))
{
	include_once(dirname(__FILE__) . '/Utils4.php');
}
else
{
	include_once(dirname(__FILE__) . '/Utils5.php');
}
