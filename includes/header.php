<?php
/**
* detect_ie() is used to figure out if the user is running IE
* sets a global variable $is_ie to true if running IE, else false.
**/
function detect_ie(){
	global $is_ie;
	if(isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)){
		$is_ie = true;
	}else{
		$is_ie = false;
	}
}
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en-us">
<head>
	<title><?php echo $title; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
	<?php
	detect_ie();
	global $is_ie;
	if($is_ie){
		echo "<link rel=\"stylesheet\" href=\"css/neverstyle-IE/default.css\" type=\"text/css\" title=\"IE Neverstyle\">\n\t";
		echo "<!--[if lte IE 6]><link rel=\"stylesheet\" href=\"css/neverstyle-IE/lte-ie6-fix.css\" type=\"text/css\" title=\"IE Fix\"><![endif]-->\n\t";
		echo "<link rel=\"alternate stylesheet\" href=\"css/neverstyle/default.css\" type=\"text/css\" title=\"Neverstyle\">\n";
	}else{
		echo "<link rel=\"stylesheet\" href=\"css/neverstyle/default.css\" type=\"text/css\" title=\"Neverstyle\">\n\t";
		echo "<link rel=\"alternate stylesheet\" href=\"css/neverstyle-IE/default.css\" type=\"text/css\" title=\"IE Neverstyle\">\n";
	}?>
