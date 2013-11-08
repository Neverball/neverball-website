<?php
function getcount($fileToRead){
	if(!file_exists(substr($fileToRead, 0, 8)) || !file_exists("counters/downloadstats.stat")){ //Setup counter directory and time file
		if(!file_exists(substr($fileToRead, 0, 8))){
			mkdir(substr($fileToRead, 0, 8), 1755);
		}
		$timeFile = "counters/downloadstats.stat";
		$handle = fopen($timeFile, 'w');
		$starttime = time(); //1167627600 UNIX timestamp for Jan 1, 2007 @ 0:0
		fwrite($handle, $starttime."\n".date("G")."\n".date("z")."\n".date("W")."\n".date("n")."\n".date("Y")."\n0\n0\n0\n0\n0\n"); //Store origin time and timestamps, zero all else.
		fclose($handle);
		chmod($timeFile, 0666);
	}
	if(!file_exists($fileToRead)){ //If download counters have been reset (file removed)
		touch($fileToRead);
		$handle = fopen($fileToRead, 'r+'); //Let's open for read and write
		$count = 0;
		rewind($handle); //Go back to the beginning
		fwrite($handle, $count);
	}else{
		$handle = fopen($fileToRead, 'r'); //Let's open for read
		$count = fread($handle, filesize($fileToRead));
		settype($count, "integer");
	}
	fclose($handle); //Done
	chmod($fileToRead, 0666);
	return $count;
}
$dlSuffix = $_GET["dlSuffix"];
if(($dlSuffix == ".tar.gz" || $dlSuffix == "-setup.exe" || $dlSuffix == ".dmg") && (basename($_SERVER["SCRIPT_NAME"]) == "getdownload.php")){
	function hitcount($fileToIncr){
		if(!file_exists(substr($fileToIncr, 0, 11))){
			mkdir(substr($fileToIncr, 0, 11), 1755);
		}
		if(!file_exists($fileToIncr)){
			touch($fileToIncr); //File doesn't exist, create
			$handle = fopen($fileToIncr, 'r+'); //Let's open for read and write
			$count = 0;
		}else{
			$handle = fopen($fileToIncr, 'r+'); //Let's open for read and write
			$count = fread($handle, filesize($fileToIncr));
			settype($count, "integer");
		}
		rewind($handle); //Go back to the beginning
		/*
		 * Note that we don't have problems with 9 being fewer characters than
		 * 10 because we are always incrementing, so we will always write at
		 * least as many characters as we read
		 **/
		fwrite($handle, ++$count); //Don't forget to increment the counter
		fclose($handle); //Done 
	}

	($dlSuffix == ".tar.gz") ? $dlType = "source" : (($dlSuffix == "-setup.exe") ? $dlType = "win" : $dlType = "mac");
	hitcount("../counters/".$dlType.".count");

	// parasti[2009-12-12]: connection timeouts cause warnings to
	// spill all over the page, tweaked this to silence them.

	$whichServer = time() % 2;
	$server0 = @fsockopen("icculus.org", 80, $errno, $errstr, 10);
	$server1 = @fsockopen("caffeinatedcontent.com", 80, $errno, $errstr, 10);
	if($server0 == false){
		$whichServer = 1;
		if($server1 == false){
			$whichServer = 2;
		}
	}else if($server1 == false){
		$whichServer = 0;
	}
	@fclose($server0);
	@fclose($server1);
	switch($whichServer){
	case 0:
		header('Location: http://icculus.org/neverball/'.$_GET["fileName"]);
		break;
	case 1:
		header('Location: http://caffeinatedcontent.com/neverball/files/releases/'.$_GET["fileName"]);
		break;
	case 2:
		header('Location: download.php?error');
		break;
	default:
		break;
	}
	die();
}
?>
