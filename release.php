<?php
require("includes/cache.php");

$title = "Neverball - Release Notes";
include("includes/header.php");
?>
</head>
<body>
	<?php include("includes/nav.php"); ?>

	<h1 id="banner" class="neverball-text">Neverball</h1>

	<div id="main">
		<div id="contents">
			<?php
			$cachefile = cache_file("docs/release-notes.md", "cache/release.php");

			if ($cachefile !== false)
				include($cachefile);
			else
				echo '<p>Failed to read release notes!</p>';
			?>
		</div>
	</div>

	<?php
	include("includes/footer.php");
	?>
