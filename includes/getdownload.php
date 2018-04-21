<?php
$dlSuffix = $_GET["dlSuffix"];
if(($dlSuffix == ".tar.gz" || $dlSuffix == ".zip" || $dlSuffix == "-setup.exe" || $dlSuffix == ".dmg") && (basename($_SERVER["SCRIPT_NAME"]) == "getdownload.php")){

	// parasti[2009-12-12]: connection timeouts cause warnings to
	// spill all over the page, tweaked this to silence them.

	//$whichServer = time() % 2;
	$whichServer = 0;
	$server0 = @fsockopen("icculus.org", 80, $errno, $errstr, 10);

//      CaffeinatedContent is going offline (for now, shutting down webhost, planned move to CDN) -- Jammnrose 4/11/18
// 	$server1 = @fsockopen("caffeinatedcontent.com", 80, $errno, $errstr, 10);
// 	if($server0 == false){
// 		$whichServer = 1;
// 		if($server1 == false){
// 			$whichServer = 2;
// 		}
// 	}else if($server1 == false){
// 		$whichServer = 0;
// 	}
	
	if($server0 == false){
		$whichServer = 2;
	}

	@fclose($server0);
// 	@fclose($server1);
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
