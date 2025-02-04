<?php
require("includes/cache.php");

$title = "Changes | Neverball";
include("includes/header.php");
?>

<main id="main">
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
</main>

<?php
include("includes/footer.php");