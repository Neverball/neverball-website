<?php
$title = "Neverball - Download";
include("includes/header.php");
include("includes/getdownload.php");
?>
</head>
<body>
	<div id="wrapper">
	<div id="banner"><h1>Neverball</h1></div>

<?php include("includes/nav.php"); ?>

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
				<dd><p><a href="neverball-1.5.4.tar.gz" title="Neverball Source Code" onClick="javascript: pageTracker._trackPageview('/downloads/src-1.5.4');" id="src">neverball-1.5.4.tar.gz</a> (25.53 MB)</p></dd>
			<dt>Microsoft Windows</dt>
				<dd><p><a href="neverball-1.5.4-setup.exe" title="Neverball for Microsoft Windows" onClick="javascript: pageTracker._trackPageview('/downloads/win-1.5.4');" id="win">neverball-1.5.4-setup.exe</a> (35.81 MB)<br>(Administrator privileges may be required to run the installer.)</p></dd>
			<dt>Mac OS X (Universal Binary)</dt>
				<dd><p><a href="neverball-1.5.3.dmg" title="Neverball for Mac OS X" onClick="javascript: pageTracker._trackPageview('/downloads/mac-1.5.3');" id="mac">neverball-1.5.3.dmg</a> (52.04 MB)</p></dd>
			<dt>Desura (Linux and Windows)</dt>
				<dd><p><a href="http://www.desura.com/games/neverball" title="Neverball on Desura for Linux and Windows" id="desura">Neverball on Desura</a> (self-updating)</p></dd>
		</dl>
		<h2>Extra Levelsets</h2>
		<p>If you are looking for extra levels for Neverball or Neverputt, please visit the <a href="http://www.nevercorner.net/forum/" onClick="javascript: pageTracker._trackPageview('/outgoing/neverforum');">Discussion Forum</a> to find un-official and in development sets. Maps found there may become integrated and official during the next release, however we do not anticipate releasing them as separate add-ons.</p>
		</div>
<?php
include("includes/footer.php");
?>
