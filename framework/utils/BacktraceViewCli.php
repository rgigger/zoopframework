<?php
class BacktraceViewCli
{
	private $info, $padding, $formatted;
	
	function __construct($info)
	{
		$this->info = $info;
		$this->padding = 4;
	}
	
	function display()
	{
		$this->formatFields();
		$maxLengths = $this->getMaxLengths();
		
		$fields = array('file', 'line', 'function');
		foreach($this->formatted as $thisRow)
		{
			$parts = array();
			foreach($fields as $thisField)
			{
				$padside = $thisField == 'file' ? STR_PAD_LEFT : STR_PAD_RIGHT;
				$padding = $thisField == 'function' ? '' : str_pad('', $this->padding, ' ');
				if(isset($thisRow[$thisField]))
					$parts[] = str_pad($thisRow[$thisField], $maxLengths[$thisField], ' ', $padside) . $padding;
			}
			
			echo implode('', $parts) . "\n";
		}
	}
	
	function getMaxLengths()
	{
		$maxLengths = array('file' => 0, 'line' => 0, 'function' => 0);
		foreach($this->formatted as $thisRow)
		{
			foreach($maxLengths as $field => $max)
			{
				if(isset($thisRow[$field]) && strlen($thisRow[$field]) > $max)
					$maxLengths[$field] = strlen($thisRow[$field]);
			}
		}
		
		return $maxLengths;
	}
	
	function formatFields()
	{
		$this->formatted = array();
		foreach($this->info as $thisRow)
		{
			$lineInfo = $this->formatLine($thisRow);
			$this->formatted[] = $lineInfo;
		}
	}
	
	function formatLine($lineInfo)
	{
		$result = array();
		$result['file'] = isset($lineInfo['file']) ? $lineInfo['file'] : 'php function';
		$result['line'] = isset($lineInfo['line']) ? $lineInfo['line'] : 'na';
		$result['function'] = $this->formatFunctionInfo($lineInfo);
		return $result;
	}

	function formatFunctionInfo($lineInfo)
	{
		$call = '';
		$call .= isset($lineInfo['class']) ? ($lineInfo['class'] . $lineInfo['type']) : '';
		$call .= $lineInfo['function'] . '(';
		$argStrings = array();
		foreach($lineInfo['args'] as $thisArg)
		{
			switch(gettype($thisArg))
			{
				case 'string':
					$argStrings[] = '"' . $thisArg . '"';
					break;
				case 'integer':
					$argStrings[] = $thisArg;
					break;
				case 'array':
					$argStrings[] = '<array>';
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
		}

		$call .= implode(', ', $argStrings);

		$call .= ')';

		return $call;
	}
}
