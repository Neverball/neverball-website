<?php
require("includes/cache.php");

$title = "Release Notes | Neverball";
include("includes/header.php");
?>
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