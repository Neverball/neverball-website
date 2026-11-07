<?php
$title = "Screenshots | Neverball";
include("includes/header.php");
?>

<main id="main">
	<div id="contents" class="content-box">
		<h2>Screenshots</h2>

		<div class="screenshot-grid">
			<a href="/images/screenshots/neverball-01.jpg" class="screenshot-thumb" data-lightbox>
				<img src="/images/screenshots/neverball-01-thumb.webp" alt="Neverball screenshot 1" width="512" height="384" fetchpriority="high">
			</a>
			<a href="/images/screenshots/neverball-02.jpg" class="screenshot-thumb" data-lightbox>
				<img src="/images/screenshots/neverball-02-thumb.webp" alt="Neverball screenshot 2" width="512" height="384">
			</a>
			<a href="/images/screenshots/neverball-03.jpg" class="screenshot-thumb" data-lightbox>
				<img src="/images/screenshots/neverball-03-thumb.webp" alt="Neverball screenshot 3" width="512" height="384">
			</a>
			<a href="/images/screenshots/neverball-04.jpg" class="screenshot-thumb" data-lightbox>
				<img src="/images/screenshots/neverball-04-thumb.webp" alt="Neverball screenshot 4" width="512" height="384" loading="lazy">
			</a>
			<a href="/images/screenshots/neverball-05.jpg" class="screenshot-thumb" data-lightbox>
				<img src="/images/screenshots/neverball-05-thumb.webp" alt="Neverball screenshot 5" width="512" height="384" loading="lazy">
			</a>
			<a href="/images/screenshots/neverball-06.jpg" class="screenshot-thumb" data-lightbox>
				<img src="/images/screenshots/neverball-06-thumb.webp" alt="Neverball screenshot 6" width="512" height="384" loading="lazy">
			</a>
			<a href="/images/screenshots/neverball-07.jpg" class="screenshot-thumb" data-lightbox>
				<img src="/images/screenshots/neverball-07-thumb.webp" alt="Neverball screenshot 7" width="512" height="384" loading="lazy">
			</a>
			<a href="/images/screenshots/neverball-08.jpg" class="screenshot-thumb" data-lightbox>
				<img src="/images/screenshots/neverball-08-thumb.webp" alt="Neverball screenshot 8" width="512" height="384" loading="lazy">
			</a>
			<a href="/images/screenshots/neverball-09.jpg" class="screenshot-thumb" data-lightbox>
				<img src="/images/screenshots/neverball-09-thumb.webp" alt="Neverball screenshot 9" width="512" height="384" loading="lazy">
			</a>
			<a href="/images/screenshots/neverball-10.jpg" class="screenshot-thumb" data-lightbox>
				<img src="/images/screenshots/neverball-10-thumb.webp" alt="Neverball screenshot 10" width="512" height="384" loading="lazy">
			</a>
			<a href="/images/screenshots/neverball-11.jpg" class="screenshot-thumb" data-lightbox>
				<img src="/images/screenshots/neverball-11-thumb.webp" alt="Neverball screenshot 11" width="512" height="384" loading="lazy">
			</a>
			<a href="/images/screenshots/neverball-12.jpg" class="screenshot-thumb" data-lightbox>
				<img src="/images/screenshots/neverball-12-thumb.webp" alt="Neverball screenshot 12" width="512" height="384" loading="lazy">
			</a>
			<a href="/images/screenshots/neverball-13.jpg" class="screenshot-thumb" data-lightbox>
				<img src="/images/screenshots/neverball-13-thumb.webp" alt="Neverball screenshot 13" width="512" height="384" loading="lazy">
			</a>
			<a href="/images/screenshots/neverball-14.jpg" class="screenshot-thumb" data-lightbox>
				<img src="/images/screenshots/neverball-14-thumb.webp" alt="Neverball screenshot 14" width="512" height="384" loading="lazy">
			</a>
		</div>

		<dialog id="lightbox" closedby="any" aria-label="Screenshot preview">
			<div class="lightbox-wrapper">
				<form method="dialog">
					<button type="submit" class="neverball-button lightbox-close" aria-label="Close lightbox">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="18" height="18" aria-hidden="true" focusable="false">
							<path d="M 3 3 L 13 13 M 13 3 L 3 13" stroke="currentColor" stroke-width="2.4" stroke-linecap="butt"/>
						</svg>
					</button>
				</form>
				<button type="button" class="neverball-button lightbox-btn lightbox-prev" aria-label="Previous screenshot">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="18" height="18" aria-hidden="true" focusable="false">
						<polygon points="12,2 4,8 12,14" fill="currentColor"/>
					</svg>
				</button>
				<img id="lightbox-img" src="" alt="Screenshot preview" width="1024" height="768">
				<button type="button" class="neverball-button lightbox-btn lightbox-next" aria-label="Next screenshot">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="18" height="18" aria-hidden="true" focusable="false">
						<polygon points="4,2 12,8 4,14" fill="currentColor"/>
					</svg>
				</button>
			</div>
		</dialog>
	</div>
</main>

<script type="module" src="/js/lightbox.js"></script>

<?php
include("includes/footer.php");
