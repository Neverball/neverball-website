<?php 
$filename = basename($_SERVER['PHP_SELF']);

$links = [
	[
		'url' => 'index.php',
		'external' => false,
		'title' => 'Home',
	],
	[
		'url' => 'screenshots.php',
		'external' => false,
		'title' => 'Screenshots',
	],
	[
		'url' => 'download.php',
		'external' => false,
		'title' => 'Download',
	],
	[
		'url' => 'https://discord.gg/HhMfr4N6H6',
		'external' => true,
		'title' => 'Discord',
	],
	[
		'url' => 'https://github.com/Neverball',
		'external' => true,
		'title' => 'Github',
	],
	[
		'url' => 'contributors.php',
		'external' => false,
		'title' => 'Credits'
	],
];
?>

<nav id="navigation" style="--count: <?php echo count($links); ?>;">
	<ul id="primary">
		<?php
		foreach ($links as $i => $link) {
			printf("<li style=\"--index: %d\"><a href=\"%s\" target=\"%s\" class=\"neverball-button %s\">%s</a></li>", $i, $link['url'], $link['external'] ? '_blank' : '', $link['url'] == $filename ? 'current' : '', $link['title']);
		}
		?>
	</ul>
</nav>