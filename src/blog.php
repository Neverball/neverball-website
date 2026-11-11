<?php

defined('IN_APP') || exit;

require_once BASE_DIR . '/includes/Blog.php';

$blog = new \Neverball\Blog();

$subroute = substr($route, 4); // remove 'blog'
$subroute = ltrim($subroute, '/');

if ($subroute === '' || preg_match('#^page/(\d+)$#', $subroute, $pageMatches)) {
    // Archive view
    if (!empty($pageMatches[1])) {
        $page = (int)$pageMatches[1];
    } else {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    }

    if ($page < 1) {
        $page = 1;
    }

    $pagination = $blog->getPaginated($page, 5);

    if ($page > 1 && $page > $pagination['totalPages']) {
        http_response_code(404);
        require CONTROLLER_DIR . '/404.php';
        exit;
    }

    $title = "Blog | Neverball";
    $description = "Latest blog posts, updates, and news from Neverball.";
    include("includes/header.php");
    ?>
    <main id="main">
        <section class="content-box" id="blog" aria-labelledby="blog-heading">
            <h2 id="blog-heading">Blog</h2>

            <div>
                <?php if (empty($pagination['posts'])): ?>
                    <p>No blog posts published yet.</p>
                <?php else: ?>
                    <?php foreach ($pagination['posts'] as $post): ?>
                        <article>
                            <h3><a href="/blog/<?= htmlspecialchars($post['slug']) ?>"><?= htmlspecialchars($post['title']) ?></a> <time datetime="<?= htmlspecialchars($post['date']) ?>"><?= htmlspecialchars($post['formattedDate']) ?></time></h3>
                            <?= $post['body'] ?>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <?php if ($pagination['totalPages'] > 1): ?>
                <nav class="pagination flex flex-wrap items-center gap-2 mt-5 pt-4 border-t text-xs" aria-label="Blog archive pages">
                    <?php for ($p = 1; $p <= $pagination['totalPages']; $p++): ?>
                        <?php if ($p === $pagination['currentPage']): ?>
                            <span class="text-muted" aria-current="page"><?= $p ?></span>
                        <?php else: ?>
                            <a href="/blog<?= $p === 1 ? '' : '?page=' . $p ?>" class="text-xs"><?= $p ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                </nav>
            <?php endif; ?>
        </section>
    </main>
    <?php
    include("includes/footer.php");
} else {
    // Single post view
    $slug = $subroute;
    $post = $blog->getBySlug($slug);

    if (!$post) {
        http_response_code(404);
        require CONTROLLER_DIR . '/404.php';
        exit;
    }

    $title = $post['title'] . " | Neverball";
    $description = $post['excerpt'] ?: "Read " . $post['title'] . " on the official Neverball website.";
    include("includes/header.php");
    ?>
    <main id="main">
        <section class="content-box" id="blog">
            <article>
                <h3><a href="/blog/<?= htmlspecialchars($post['slug']) ?>"><?= htmlspecialchars($post['title']) ?></a> <time datetime="<?= htmlspecialchars($post['date']) ?>"><?= htmlspecialchars($post['formattedDate']) ?></time></h3>
                <?= $post['body'] ?>
            </article>

            <div class="mt-5 pt-4 border-t text-xs">
                <a href="/blog" class="text-xs">Back to all posts</a>
            </div>
        </section>
    </main>
    <?php
    include("includes/footer.php");
}
