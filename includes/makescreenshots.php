<?php
if(!(basename($_SERVER["SCRIPT_NAME"]) === "makescreenshots.php")){
	if(!function_exists('detect_ie')){
		global $is_ie;
		if(isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)){
			$is_ie = true;
		}else{
			$is_ie = false;
		}
	}else detect_ie();
	buildimagelist();
}else die();

/**
 * buildimagelist() scans the /shots directory for images with certain
 * names and builds an array with image data for the other functions to use.
 **/
function buildimagelist(){
	global $is_ie;
	$basedir = "images/shots";
	$dirarray = array();
	$dir = opendir($basedir);
	while($catchdir = readdir($dir)){
		if(!is_file($catchdir) && $catchdir != '.' && $catchdir != '..' && is_numeric(substr($catchdir, 0, 2))){
			array_push($dirarray, $catchdir);
		}
	}
	closedir($dir);
	natcasesort($dirarray);
	$masterarray = array();
	foreach($dirarray as $dirarray){
		$currdir = $basedir.'/'.$dirarray;
		// open specified directory
		$dirHandle = opendir($currdir);
		$returnstr = "";
		$imagearray = array();
		while($file = readdir($dirHandle)){
			// if not a subdirectory and if filename contains the string '.jpg' 
			if(!is_dir($file) && strpos($file, '.jpg') > 0 && substr($file, 10) == '.jpg'){
				// add image to array
				array_push($imagearray, $file);
			}
		}
		natcasesort($imagearray);
		foreach($imagearray as $imagearray){
			$setinfo = $basedir.'/'.$dirarray.'/set-info.txt';
			if(file_exists($setinfo)){
				$handle = fopen($setinfo, 'r');
				$setname = rtrim(fgets($handle));
				$setdesc = "";
				while(!feof($handle)){
					$setdesc = $setdesc."<p>".rtrim(fgets($handle))."</p>\n\t\t";
				}
				fclose($handle);
			}else{
				$setname = "Error";
			}
			$level = substr($imagearray, 5, 2);
			$setnum = substr($imagearray, 8, 2);
			$currentimage = "?id=".$level."&amp;set=".$setnum;
			if(strstr($level, '0')){
				$level = substr($level, 1);
			}
			if(strstr($setnum, '0')){
				$setnum = substr($setnum, 1);
			}
			$levelname = $setname.": Level ".$level;
			$currentthumb = $basedir.'/'.$dirarray.'/thumb/'.substr($imagearray, 0, 10)."-thumb.jpg";
			$imagefullpath = $basedir.'/'.$dirarray.'/'.$imagearray;
			array_push($masterarray, array($currentimage, $currentthumb, $imagefullpath, $setdesc, $setname, $levelname));
		}
		closedir($dirHandle);
	}
	$id = ($_GET["id"]);
	$set = ($_GET["set"]);
	if($id != "" && $set != "" && (is_numeric($id) && is_numeric($set)) && ($id >= 0 && $id <= 99) && ($set >= 0 && $set <= 99)){
		writefull($masterarray);
	}else{
		writethumbnails($masterarray, false);
	}
}

/**
 * writethumbnails() takes the array of images that was built and makes
 * a page that has all the thumbnails on it. If a thumbnail doesn't exist
 * the makethumbnail() function is called and thumbnails are created only
 * for images that need them. A /thumb directory is also created if needed.
 **/
function writethumbnails($masterarray, $invalid){
	echo "<div class=\"style-img-link thumbimgs\">\n\t\t";
	if($invalid){
		echo "<p class=\"error-img\"><span>Bad id or set number, image may not exist or may have been deleted. Please try one of the images below.</span></p>\n\t\t";
	}
	$prevdesc = $masterarray[0][3];
	$nb_or_np = "error";
	for($i = 0; $i < count($masterarray); $i++){
		$name = substr($masterarray[$i][2], 16, 9);
		if($nb_or_np != $name){
			if($name == "neverball"){
				if($i == 0){
					echo "<h3>Neverball</h3>\n\t\t";
				}else echo "<br>\n\t\t<h3>Neverball</h3>\n\t\t";
			}else if($name == "neverputt"){
				if($i == 0){
					echo "<h3>Neverputt</h3>\n\t\t";
				}else echo "<br>\n\t\t<h3>Neverputt</h3>\n\t\t";
			}else{
				if($i == 0){
					echo "<h3>Error</h3>\n\t\t";
				}else echo "<br>\n\t\t<h3>Error</h3>\n\t\t";
			}
			$nb_or_np = $name;
		}
		if(file_exists(substr($masterarray[$i][1], 0, 36))){
			if(!file_exists($masterarray[$i][1])){
				 makethumbnail($masterarray[$i][1], $masterarray[$i][2]);
			}
		}else{
			mkdir(substr($masterarray[$i][1], 0, 36), 0777);
			makethumbnail($masterarray[$i][1], $masterarray[$i][2]);
		}
		echo "<a href=\"screenshots.php".$masterarray[$i][0]."\"><img src=\"".$masterarray[$i][1]."\" alt=\"".$masterarray[$i][5]."\"></a>\n\t\t";
	}
	echo "</div>\n\t\t<br>\n\t\t";
}

/**
 * makethumbnail() takes the the path that a thumbnail is supposed to have
 * and the path to the fullsize image. It resizes and resamples the fullsize
 * image and makes a smaller thumbnail where the path says it should be.
 **/
function makethumbnail($thumbpath, $fullpath){
	list($width, $height) = getimagesize($fullpath);
	$thumbimg = imagecreatetruecolor(256, 192);
	$origimg = imagecreatefromjpeg($fullpath);
	imagecopyresampled($thumbimg, $origimg, 0, 0, 0, 0, 256, 192, $width, $height);
	imageinterlace($thumbimg, 5);
	imagejpeg($thumbimg, $thumbpath, 95);
	chmod($thumbpath, 0777);
}

/**
 * writefull() takes the array of images that was built and generates html
 * that displays the fullsize image if the set and id match a fullsize image.
 * Otherwise it makes the thumbnails page with the exception of a small
 * message to let the user know something is wrong.
 **/
function writefull($masterarray){
	global $is_ie;
	$id = ($_GET["id"]);
	$set = ($_GET["set"]);
	for($i = 0; $i < count($masterarray); $i++){
		if("?id=".$id."&amp;set=".$set == $masterarray[$i][0]){
			if($i != 0){
				$previmg = $masterarray[$i - 1][0];
			}
			if($i + 1 < count($masterarray)){
				$nextimg = $masterarray[$i + 1][0];
			}
			$theimagearray = $masterarray[$i];
		}
	}
	if(count($theimagearray) != 0){
		echo "<div class=\"fullimg\">\n\t\t";
		echo "<p>".$theimagearray[5]."</p>\n\t\t";
		
		if($is_ie){
			echo "<div class=\"style-imgnav\">\n\t\t";
			if($previmg != ""){
				echo "<div class=\"prev\"><a href=\"screenshots.php".$previmg."\">Previous Image</a></div>\n\t\t";
			}else echo "<div class=\"prevd\"><span class=\"invalid-link\">Previous Image</span></div>\n\t\t";
			echo "<div class=\"back\"><a href=\"screenshots.php\">Back to thumbnails</a></div>";
			if($nextimg != ""){
				echo "\n\t\t<div class=\"next\"><a href=\"screenshots.php".$nextimg."\">Next Image</a></div>";
			}else echo "\n\t\t<div class=\"nextd\"><span class=\"invalid-link\">Next Image</span></div>";
			echo "\n\t\t</div><br>\n\t\t<br>\n\t\t";
		}else{
			echo "<div class=\"shot\">\n\t\t";
			echo "<div class=\"controls\">\n\t\t\t";
			if($previmg != ""){
				echo "<a class=\"previous\" href=\"screenshots.php".$previmg."\"><span>Previous</span></a>\n\t\t\t";
			}
			echo "<a class=\"thumb\" href=\"screenshots.php\"><span>Back to thumbnails</span></a>\n\t\t\t";
			if($nextimg != ""){
				echo "<a class=\"next\" href=\"screenshots.php".$nextimg."\"><span>Next</span></a>\n\t\t";
			}
			echo "</div>\n\t\t";
		}
		
		echo "<a href=\"".$theimagearray[2]."\"><img src=\"".$theimagearray[2]."\" width=\"700px\" height=\"525px\" alt=\"".$theimagearray[4]."\"></a>\n\t\t";
		if(!$is_ie){
			echo "</div>\n\t\t";
		}else{
			echo "<br>\n\t\t";
		}
		
		
		if($is_ie){
			echo "<div class=\"style-imgnav\">\n\t\t";
			if($previmg != ""){
				echo "<div class=\"prev\"><a href=\"screenshots.php".$previmg."\">Previous Image</a></div>\n\t\t";
			}else echo "<div class=\"prevd\"><span class=\"invalid-link\">Previous Image</span></div>\n\t\t";
			echo "<div class=\"back\"><a href=\"screenshots.php\">Back to thumbnails</a></div>";
			if($nextimg != ""){
				echo "\n\t\t<div class=\"next\"><a href=\"screenshots.php".$nextimg."\">Next Image</a></div>";
			}else echo "\n\t\t<div class=\"nextd\"><span class=\"invalid-link\">Next Image</span></div>";
			echo "\n\t\t</div><br>\n\t\t";
		}
		echo "</div>\n\t\t";
	}else{
		writethumbnails($masterarray, true);
	}
}
