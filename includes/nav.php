<?php 
global $current_route;

$links = [
	[
		'url' => '/',
		'external' => false,
		'title' => 'Home',
	],
	[
		'url' => '/screenshots',
		'external' => false,
		'title' => 'Screenshots',
	],
	[
		'url' => '/download',
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
		'url' => '/credits',
		'external' => false,
		'title' => 'Credits'
	],
];
?>

<nav id="navigation" style="--count: <?php echo count($links); ?>;">
	<ul id="primary">
		<?php
		foreach ($links as $i => $link) {
			printf("<li style=\"--index: %d\"><a href=\"%s\" target=\"%s\" class=\"neverball-button %s\">%s</a></li>", $i, $link['url'], $link['external'] ? '_blank' : '', $link['url'] == ('/' . $current_route) ? 'current' : '', $link['title']);
		}
		?>
	</ul>
</nav>