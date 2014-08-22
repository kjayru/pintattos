<?php
function getParam($param, $default = "") {
	$result = $default;
	if (isset($param)) {
  		$result = (get_magic_quotes_gpc()) ? $param : addslashes($param);
	}
	return $result;
}
function getSQL($theValue, $theType) {
	$theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
	$theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

	switch ($theType) {
		case "text":
			$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
			break;    
		case "long":
		case "int":
			$theValue = ($theValue != "") ? intval($theValue) : "NULL";
			break;
		case "double":
			$theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
			break;
		case "date":
			$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
			break;
	}
	return $theValue;
}
function randomkey($length) {
    $pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
    for($i=0;$i<$length;$i++) {
      $key .= $pattern{rand(0,35)};
    }
    return $key;
}
function makeURL($path) {
	echo PATHABS.$path;
}
function makePATH($path) {
	echo PATHABS.$path;
}
function makeURLWEB($path) {
	echo PATHWEB.$path;
}
function checkMenu($opt) {
	global $SECTN;
	if ($opt == $SECTN) {
		echo " current";
	}
}
/* Session functions */
function checkSession() {
	if (!isset($_SESSION)) {
	  session_start();
	}
}
/* mix functions */
function limitText($text, $max=90) {
	if (strlen($text) > $max) {
		return substr($text, 0, $max)."...";	
	} else {
		return $text;
	}
}
function getMetaDescription($text) {
	$text = strip_tags($text);
	$text = str_replace("\"", "", $text);
	$text = str_replace("'", "", $text);
	$text = trim($text);
	$text = substr($text, 0, 180);
	return $text."...";
}
function makeLinks($s) {
  return preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">$1</a>', $s);
}
?>