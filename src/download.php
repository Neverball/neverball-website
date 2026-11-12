<?php
$title = "Download | Neverball";
include("includes/header.php");
?>

<main id="main">
	<div id="contents">
		<h2>Downloads</h2>
		<?php
			if(isset($_GET["error"])){
				echo "<p class=\"error-img\"><span>Our download servers seem to be offline. Sorry... Please try again later.</span></p>\n";
			}
		?>
		<p>Neverball and Neverputt are available for download here. Have a look at the <a href="release.php">release notes</a> for what's new in this release. Neverball is released under the GNU GPLv2 (or a later version at your option).</p>
		<dl class="add-colons">
			<dt>Linux</dt>
				<dd>
					<p>The easiest way to get Neverball on most Linux distros is to install the Flatpak:</p>
					<p><a href='https://flathub.org/apps/org.neverball.Neverball'>
						<img width='160' alt='Get it on Flathub' src='https://flathub.org/api/badge?svg&locale=en'/>
					</a></p>
				</dd>
			<dt>Microsoft Windows</dt>
				<dd><p><a href="neverball-1.6.0.zip" title="Neverball for Microsoft Windows" id="win">neverball-1.6.0.zip</a> (73 MB)</p></dd>
			<dt>macOS</dt>
				<dd>
					<p>Neverball 1.6.0 is currently not natively available on macOS.  Give the web version a try, playable right from your browser!</p>
					<p><a href="https://play.neverball.org" target="_blank" class="neverball-button update-button-focused">Play in Browser</a></p>
				</dd>
			<dt>Source</dt>
				<dd><p><a href="neverball-1.6.0.tar.gz" title="Neverball Source Code" id="src">neverball-1.6.0.tar.gz</a> (37 MB)</p></dd>
		</dl>
		<h2>Custom Levels</h2>
		<p>If you are looking for additional content for either Neverball or Neverputt, be sure to visit the <a href="https://github.com/Neverball/neverball/discussions">Discussion Forum</a> to find custom levels as well as other content created by community members.</p>
	</div>
</main>

<?php
include("includes/footer.php");
