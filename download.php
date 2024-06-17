<?php
$title = "Neverball - Download";
include("includes/header.php");
include("includes/getdownload.php");
?>
</head>
<body>
	<?php include("includes/nav.php"); ?>

	<div id="banner"><h1>Neverball</h1></div>

	<div id="main">
		<div id="contents">
			<h2>Downloads</h2>
			<?php
				if(isset($_GET["error"])){
					echo "<p class=\"error-img\"><span>Our download servers seem to be offline. Sorry... Please try again later.</span></p>\n";
				}
			?>
			<p>Neverball and Neverputt are available for download here. Have a look at the <a href="release.php">release notes</a> for what's new in this release. Neverball is released under the GNU GPLv2 (or a later version at your option).</p>
			<dl class="add-colons">
				<dt>Source</dt>
					<dd><p><a href="neverball-1.6.0.tar.gz" title="Neverball Source Code" id="src">neverball-1.6.0.tar.gz</a> (37 MB)</p></dd>
				<dt>Microsoft Windows</dt>
					<dd><p><a href="neverball-1.6.0.zip" title="Neverball for Microsoft Windows" id="win">neverball-1.6.0.zip</a> (73 MB)</p></dd>
				<dt>Mac OS X (Universal Binary)</dt>
					<dd><p><a href="neverball-1.5.3.dmg" title="Neverball for Mac OS X" id="mac">neverball-1.5.3.dmg</a> (52.04 MB)<br>Neverball 1.6.0 is currently not available on Mac OS X. Give <a href="http://uppgarn.com/nuncabola/" title="Nuncabola">Nuncabola</a> a try!</p></dd>
			</dl>
			<h2>Custom Levels</h2>
			<p>If you are looking for additional content for either Neverball or Neverputt, be sure to visit the <a href="http://neverforum.com">Discussion Forum</a> to find custom levels as well as other content created by community members.</p>
		</div>
	</div>

	<?php
	include("includes/footer.php");
	?>