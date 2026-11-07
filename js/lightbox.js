const dialog = document.getElementById('lightbox');
const img = document.getElementById('lightbox-img');
const prevBtn = dialog?.querySelector('.lightbox-prev');
const nextBtn = dialog?.querySelector('.lightbox-next');
const links = Array.from(document.querySelectorAll('a[data-lightbox]'));

if (dialog && img && links.length > 0) {
	let currentIndex = 0;
	const preloaded = new Set();

	const preload = (index) => {
		const idx = (index + links.length) % links.length;
		const href = links[idx].href;
		if (!preloaded.has(href)) {
			preloaded.add(href);
			const pre = new Image();
			pre.src = href;
		}
	};

	const preloadAll = () => {
		let i = 0;
		const step = () => {
			if (i < links.length) {
				preload(i++);
				if ('requestIdleCallback' in window) {
					requestIdleCallback(step);
				} else {
					setTimeout(step, 50);
				}
			}
		};
		step();
	};

	const showImage = (index) => {
		currentIndex = (index + links.length) % links.length;
		const link = links[currentIndex];
		img.src = link.href;
		const thumb = link.querySelector('img');
		img.alt = thumb?.alt || 'Screenshot preview';

		// Preload adjacent images immediately for instant flipping
		preload(currentIndex + 1);
		preload(currentIndex - 1);
	};

	links.forEach((link, i) => {
		link.addEventListener('click', (e) => {
			e.preventDefault();
			showImage(i);
			dialog.showModal();
			preloadAll();
		});
	});

	prevBtn?.addEventListener('click', (e) => {
		e.stopPropagation();
		showImage(currentIndex - 1);
	});

	nextBtn?.addEventListener('click', (e) => {
		e.stopPropagation();
		showImage(currentIndex + 1);
	});

	dialog.addEventListener('keydown', (e) => {
		if (e.key === 'ArrowLeft') {
			showImage(currentIndex - 1);
		} else if (e.key === 'ArrowRight') {
			showImage(currentIndex + 1);
		}
	});

	// Fallback for browsers without native closedby="any" support
	if (!('closedBy' in HTMLDialogElement.prototype)) {
		const outside = (e, d) => {
			const r = d.getBoundingClientRect();
			return e.clientX < r.left || e.clientX > r.right || e.clientY < r.top || e.clientY > r.bottom;
		};

		let pressedOutside = false;
		dialog.addEventListener('pointerdown', (e) => {
			pressedOutside = outside(e, dialog);
		});
		dialog.addEventListener('pointerup', (e) => {
			if (pressedOutside && outside(e, dialog)) {
				dialog.close();
			}
		});
	}
}
