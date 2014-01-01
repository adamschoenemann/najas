<?php
class Utils {

	public static function json_encode($obj, $indent = true){
		$result;
		if(self::is_assoc($obj)){
			$result = json_encode(($obj));
		} else {
			$result = json_encode($obj);
		}

		return ($indent) ? self::indent($result) : $result;
	}

	public static function is_assoc($array) {
		return (bool)count(array_filter(array_keys($array), 'is_string'));
	}

	/**
	 * Indents a flat JSON string to make it more human-readable.
	 *
	 * @param string $json The original JSON string to process.
	 *
	 * @return string Indented version of the original JSON string.
	 */
	public static function indent($json) {

		$result      = '';
		$pos         = 0;
		$strLen      = strlen($json);
		$indentStr   = '  ';
		$newLine     = "\n";
		$prevChar    = '';
		$outOfQuotes = true;

		for ($i=0; $i<=$strLen; $i++) {

        // Grab the next character in the string.
			$char = substr($json, $i, 1);

        // Are we inside a quoted string?
			if ($char == '"' && $prevChar != '\\') {
				$outOfQuotes = !$outOfQuotes;

        // If this character is the end of an element,
        // output a new line and indent the next line.
			} else if(($char == '}' || $char == ']') && $outOfQuotes) {
				$result .= $newLine;
				$pos --;
				for ($j=0; $j<$pos; $j++) {
					$result .= $indentStr;
				}
			}

        // Add the character to the result string.
			$result .= $char;

        // If the last character was the beginning of an element,
        // output a new line and indent the next line.
			if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
				$result .= $newLine;
				if ($char == '{' || $char == '[') {
					$pos ++;
				}

				for ($j = 0; $j < $pos; $j++) {
					$result .= $indentStr;
				}
			}

			$prevChar = $char;
		}

		return $result;
	}

	public static function upload_file($file, $f3){
		$dir = $f3->get("UPLOADS") . date("Y/m/");;
		self::mkdir($dir);
		$filename = $dir . basename($file["name"]);
		// echo $filename;
		
		if(file_exists($filename)){
			$filename = self::increment_file_name($filename);
		}

		if(move_uploaded_file($file["tmp_name"], $filename)){
			return $filename;
		}
		return "";
	}

	public static function mkdir($pathname){
		if(file_exists($pathname) == false){
			mkdir($pathname, 0777, true);
		}
		return $pathname;
	}

	public static function increment_file_name($filename){
		$info = pathinfo($filename);
		$newname = $filename;
		for($i = 0; file_exists($newname); $i++){
			$newname = sprintf("%s/%s_%02d.%s", 
				$info["dirname"],
				$info["filename"],
				$i,
				$info["extension"]
			);
		}
		return $newname;
	}

}