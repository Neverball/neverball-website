<?php
require("includes/cache.php");

$title = "Neverball - Changes";
include("includes/header.php");
?>
</head>
<body>
	<?php include("includes/nav.php"); ?>
	
	<h1 id="banner" class="neverball-text">Neverball</h1>

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