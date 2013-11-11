<?php 
$basename = basename($_SERVER['PHP_SELF']);
$basename = explode('.',$basename);
$filename = $basename[0]; 
$class = 'class="current" ';
$navArray = array(
	'index' => 'Home',
	'screenshots' => 'Screenshots',
	'download' => 'Download',
	'faq' => 'FAQ',
	'changes' => 'Changes',
	'contributors' => 'Contributors'
);
?>

	<div id="navigation">
		<ul id="primary">

<?php
foreach ($navArray as $k => $v) {
	echo '			<li><a ';
	echo ($filename == $k ? $class : '');
	echo "href=\"{$k}.php\">{$v}</a></li>\n";
}
?>

		</ul>
	</div>
