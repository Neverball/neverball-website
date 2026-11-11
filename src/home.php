<?php
require_once BASE_DIR . '/includes/Blog.php';
$blog = new \Neverball\Blog();
$recentPosts = $blog->getRecent(3);

$title = "Neverball";
$description = "Neverball is a free, open-source skill and puzzle game. Tilt the floor to roll through obstacle courses. Play right away in your browser or download for desktop.";
include("includes/header.php");
?>
<main id="main">
	<section id="hero" aria-label="Neverball overview">
		<div id="screenshot">
			<video src="/images/anim.mp4" poster="/images/anim.jpg" width="640" height="360" loop muted autoplay playsinline title="Neverball gameplay preview" aria-label="Neverball gameplay preview"></video>
		</div>

		<h2><span class="neverball-text">Tilt the floor. Roll the ball. Beat the clock!</span></h2>

		<p class="leading-relaxed">
			<strong>Neverball</strong> is a free, open-source skill and puzzle game. Guide your ball through precarious obstacle courses before time runs out by tilting the world beneath it. Play right away in your web browser or download the desktop version which also includes <strong>Neverputt</strong>, the companion hot-seat miniature golf game.
		</p>

		<div class="hero-actions flex flex-wrap gap-2 items-center">
			<a href="https://play.neverball.org" target="_blank" class="neverball-button update-button-focused">Play in Browser</a>
			<a href="/download" class="neverball-button">Download Neverball</a>
		</div>

		<p class="text-xs text-muted">
			Free &amp; open-source for Linux, Windows, macOS, and your system.
		</p>
	</section>

	<script type="application/ld+json">
	{
		"@context": "https://schema.org",
		"@type": "VideoGame",
		"name": "Neverball",
		"description": "Neverball is a free, open-source 3D skill and puzzle game. Tilt the floor to roll a ball through obstacle courses before time runs out.",
		"url": "<?php echo BASE_URL; ?>",
		"image": "<?php echo BASE_URL; ?>/images/anim.jpg",
		"genre": ["Puzzle", "Action", "Skill"],
		"gamePlatform": ["Web Browser", "Linux", "Windows", "macOS", "FreeBSD"],
		"applicationCategory": "Game",
		"offers": {
			"@type": "Offer",
			"price": "0",
			"priceCurrency": "USD"
		}
	}
	</script>

	<section class="content-box" id="blog" aria-labelledby="blog-heading">
		<h2 id="blog-heading">Blog</h2>
		
		<div>
			<?php if (empty($recentPosts)): ?>
				<p>No blog posts published yet.</p>
			<?php else: ?>
				<?php foreach ($recentPosts as $post): ?>
					<article>
						<h3><a href="/blog/<?= htmlspecialchars($post['slug']) ?>"><?= htmlspecialchars($post['title']) ?></a> <time datetime="<?= htmlspecialchars($post['date']) ?>"><?= htmlspecialchars($post['formattedDate']) ?></time></h3>
						<?= $post['body'] ?>
					</article>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>

		<div class="blog-more mt-5 pt-4 border-t text-xs">
			<a href="/blog" class="text-xs">Read older posts</a>
		</div>
	</section>

	<section class="content-box" id="community" aria-labelledby="community-heading">
		<h2 id="community-heading">Community &amp; Contact</h2>
		<p>Visit the community <a href="https://discord.gg/HhMfr4N6H6">Neverball Discord</a> server to get in touch with other players and designers.</p>
		<p>We are also on <a href="https://github.com/Neverball">Github</a> (be sure to check out the <a href="https://github.com/Neverball/neverball/discussions">discussions</a> section) and <a href="https://steamcommunity.com/groups/neverball">Steam</a>.</p>
	</section>

	<section class="content-box" id="level-design" aria-labelledby="level-design-heading">
		<h2 id="level-design-heading">Level Design</h2>
		<p>Players interested in trying their hand at level creation are encouraged to read through the <a href="http://github.com/neverball/neverball-docs">level design documentation</a>. Levels of sufficent quality may be included in the next version; authors will be credited in-game.</p>
	</section>
</main>

<?php
include("includes/footer.php");
