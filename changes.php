<?php
$title = "Neverball - Changes";
include("includes/header.php");
?>
</head>
<body>
	<div id="wrapper">
	<div id="banner"><h1>Neverball</h1></div>
	<div id="navigation">
	<ul id="primary">
		<li><a href="index.php">Home</a></li>
		<li><a href="screenshots.php">Screenshots</a>
		<li><a href="download.php">Download</a></li>
		<li><a href="faq.php">FAQ</a></li>
		<li><span>Changes</span></li>
		<li><a href="contributors.php">Contributors</a></li>
	</ul>
	</div>
	<div id="main">
		<div id="contents">
		<h2>Changes</h2><?php
function parse_changes($fname){
	$cnf = file($fname);
	$ret = "\n";
	$state = 0;
	
	foreach($cnf as $line){
		if(preg_match('/^-+$/', $line)){
			if($state == 2){
				$ret .= "</li>\n\t\t";
			}
			if($state == 1 || $state == 2){
				$ret .= "</ul>\n";
			}
			$state = 0;
		}else if(preg_match('/^(New in .+)$/', $line, $matches) && ($state == 0)){
			$ret .= "\t\t<h3>".htmlentities($matches[1])."</h3>\n\t\t";
			$ret .= "<ul>\n\t\t";
			$state = 1;
		}else if(preg_match('/^\* ([^ ].+)$/', $line, $matches) && ($state == 1 || $state == 2)){
			if($state == 2){
				$ret .= "</li>\n\t\t";
			}
			$ret .= "\t<li>".htmlentities($matches[1]);
			$state = 2;
		}else if(preg_match('/^  ([^ ].+)$/', $line, $matches) && ($state == 2)){
			$ret .= " ".htmlentities($matches[1]);
			$state = 2;
		}
	}
	if($state == 2){
		$ret .= "</li>\n\t\t";
	}
	if($state == 1 || $state == 2){
		$ret .= "</ul>\n\t\t";
	}
	return $ret;
}
if((file_exists("includes/changelog.php") && !file_exists("CHANGES")) || (file_exists("includes/changelog.php") && (filemtime("CHANGES") == filemtime("includes/changelog.php")))){
	include("includes/changelog.php");
}else if(!file_exists("includes/changelog.php") && !file_exists("CHANGES")){
	echo "\n\t\t<p>Failed to read CHANGES data!</p>\n";
}else{
	$changesFile = parse_changes("CHANGES");
	$changesFileWrite = "<?php\nif(basename(\$_SERVER[\"SCRIPT_NAME\"]) == \"changelog.php\"){\n\tdie();\n}\n?>\n".$changesFile;
	$fh = fopen('includes/changelog.php', 'w') or die("Failed to open changelog.php for writing!");
	fwrite($fh, $changesFileWrite);
	fclose($fh);
	chmod("includes/changelog.php", 0666);
	touch("includes/changelog.php", filemtime("CHANGES"));
	echo $changesFile;
}
?></div>
<?php
include("includes/footer.php");
?>
