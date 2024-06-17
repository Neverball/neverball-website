<?php
require("includes/cache.php");

$title = "Neverball - Changes";
include("includes/header.php");
?>
</head>
<body>
	<?php include("includes/nav.php"); ?>
	
	<div id="banner"><h1>Neverball</h1></div>

	<div id="main">
		<div id="contents">
			<h2>Changes</h2>
			<?php
			$cachefile = cache_file("docs/changes.txt", "cache/changelog.php");

			if ($cachefile !== false)
				include($cachefile);
			else
				echo "\n\t\t<p>Failed to read CHANGES data!</p>\n";
			?>
		</div>
	</div>
	<?php
	include("includes/footer.php");
	?>