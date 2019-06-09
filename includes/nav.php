<?php 
$filename = basename($_SERVER['PHP_SELF']);
$class = 'class="current" ';

// Be sure to recompute #navigation width and offset in default.css
// when changing the number of links in this list.
$navArray = array(
	'index.php' => 'Home',
	'screenshots.php' => 'Screenshots',
	'download.php' => 'Download',
	'https://neverforum.com' => 'Forum',
	'https://github.com/Neverball' => 'Github',
#	'faq.php' => 'FAQ',
#	'changes.php' => 'Changes',
	'contributors.php' => 'Credits'
);
?>

	<div id="navigation">
		<ul id="primary">

<?php
foreach ($navArray as $url => $title) {
	echo '			<li><a ';
	echo ($url == $filename ? $class : '');
	echo "href=\"{$url}\">{$title}</a></li>\n";
}
?>

		</ul>
	</div>
