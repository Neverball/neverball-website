<?php
require("includes/cache.php");

$title = "Neverball - Contributors";
include("includes/header.php");
?>
</head>
<body>
	<div id="wrapper">
	<div id="banner"><h1>Neverball</h1></div>

<?php include("includes/nav.php"); ?>

	<div id="main">
		<div id="contents">
		<h2>Neverball Contributions</h2>
		<dl class="add-colons">
<?php
$cachefile = cache_file("docs/authors.txt", "cache/authors.php");

if ($cachefile !== false)
	include($cachefile);
else
	echo "\n\t\t<dd>Failed to read author data!</dd>\n";
?>
		</dl>
		<br>
		<h2>Website Contributions</h2>
		<dl class="add-colons">
			<dt>Ben Anderson</dt>
			<dd><ul>
				<li>Website content, maintenance and php programming</li>
				<li>Various images</li>
			</ul></dd>
			<dt>Josh Bush</dt>
			<dd><ul>
				<li>CSS layout and related images</li>
			</ul></dd>
			<dt>Pasi Kallinen</dt>
			<dd><ul>
				<li>Website parsing code for CHANGES file</li>
			</ul></dd>
			<dt>Jānis Rūcis</dt>
			<dd><ul>
				<li>Content layout</li>
				<li>Various random stuff</li>
			</ul></dd>
			<dt><a href="http://tango.freedesktop.org/">Tango Desktop Project</a></dt>
			<dd><ul>
				<li>Various icons</li>
			</ul></dd>
		</dl>
		</div>
<?php
include("includes/footer.php");
?>
