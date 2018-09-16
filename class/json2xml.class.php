<?php
class json2xml
{
    private $root = 'document';
    private $indentation = '    ';
    // TODO: private $this->addtypes = false; // type="string|int|float|array|null|bool"
    public function export($data)
    {
        $data = array($this->root => $data);
        $xml = '<?xml version="1.0" encoding="UTF-8" ?>';
        $xml .= $this->recurse($data, 0);
		return $xml;
    }
    private function recurse($data, $level)
    {
        $return = '';
		$indent = str_repeat($this->indentation, $level);
        foreach ($data as $key => $value) {
	            if ($key=="text")
					continue;
    			$return .= PHP_EOL . $indent . '<' . $key . '>';
					
                if (is_array($value)) {
                    if ($value) {
                        $temporary = $key;
                        foreach ($value as $entry) {
                            $return .= $this->recurse(array($temporary => $entry), $level + 1);
                        }
                        $return .= PHP_EOL . $indent;
                    }
                } else if (is_object($value)) {
                    if ($value) {
                        $return .= $this->recurse($value, $level + 1);
                        $return .= PHP_EOL . $indent;
                    }
                } else {
                    if (is_bool($value)) {
                        $value = $value ? 'true' : 'false';
                    }
                    $return .= $value;
                }
				$return .= '</' . $key . '>';
        }
		return $return;
    }
}
?>