<?php

class Format{
	public function validation($data){
		$data = trim($data);
		$data = stripcslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
	public function textShorten($text, $limit=40){
		$text = $text." ";
		$text = substr($text,0,$limit);
		$text = substr($text, 0 , strrpos($text, ' '));
		$text = $text. "......";
		return $text;
	}
}

?>
