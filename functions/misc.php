<?php
function htmllink($text, $to, $tooltip = "") {
	$text = htmlspecialchars_decode ( $text );
	$to = htmlspecialchars_decode ( $to );
	return "<a href=\"{$to}\" title =\"{$tooltip}\" class=\"alink\">{$text}</a>";
}
function htmlHeader($text, $to) {
	$text = htmlspecialchars_decode ( $text );
	$to = htmlspecialchars_decode ( $to );
	return "<a href=\"{$to}\" >{$text}</a>";
}

function delim($char, $count){
	$return ="";
	for($i = 0; $i < $count; $i++){
		$return .= $char;
	}
	return $return;
}