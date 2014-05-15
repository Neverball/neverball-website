<?php
require("includes/cache.php");

$title = "Neverball - Release Notes";
include("includes/header.php");
?>
</head>
<body>
	<div id="wrapper">
	<div id="banner"><h1>Neverball</h1></div>

<?php include("includes/nav.php"); ?>

	<div id="main">
		<div id="contents">
<?php
$cachefile = cache_file("release-notes.md", "cache/release.php");

if ($cachefile !== false)
	include($cachefile);
else
	echo '<p>Failed to read release notes!</p>';
?>
		</div>
<?php
include("includes/footer.php");
?>
