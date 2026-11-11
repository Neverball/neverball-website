<?php

namespace Neverball;

class Blog
{
    private string $contentDir;

    public function __construct(?string $contentDir = null)
    {
        $this->contentDir = rtrim($contentDir ?? (BASE_DIR . '/content/blog'), '/');
    }

    /**
     * Get the N most recent published posts for the home page.
     *
     * @param int $limit
     * @return array<int, array>
     */
    public function getRecent(int $limit = 3): array
    {
        return array_slice($this->getAll(), 0, $limit);
    }

    /**
     * Get a paginated slice of published posts for the archive.
     *
     * @param int $page
     * @param int $perPage
     * @return array{
     *   posts: array<int, array>,
     *   currentPage: int,
     *   perPage: int,
     *   totalPosts: int,
     *   totalPages: int,
     *   hasPrev: bool,
     *   hasNext: bool
     * }
     */
    public function getPaginated(int $page = 1, int $perPage = 5): array
    {
        $posts = $this->getAll();
        $total = count($posts);
        $totalPages = max(1, (int)ceil($total / $perPage));
        $page = max(1, $page);
        $offset = ($page - 1) * $perPage;

        return [
            'posts' => array_slice($posts, $offset, $perPage),
            'currentPage' => $page,
            'perPage' => $perPage,
            'totalPosts' => $total,
            'totalPages' => $totalPages,
            'hasPrev' => $page > 1,
            'hasNext' => $page < $totalPages,
        ];
    }

    /**
     * Locate a published post by slug.
     *
     * @param string $slug
     * @return array|null
     */
    public function getBySlug(string $slug): ?array
    {
        $publishedDir = $this->contentDir . '/published';

        // 1. Direct match: <slug>.html
        if (file_exists($publishedDir . '/' . $slug . '.html')) {
            return $this->parseFile($publishedDir . '/' . $slug . '.html');
        }

        // 2. Wildcard pattern match: *-<slug>.html
        $matches = glob($publishedDir . '/*[-_]' . $slug . '.html');
        if (!empty($matches)) {
            return $this->parseFile($matches[0]);
        }

        // 3. Fallback scan for posts with custom slug in header
        foreach (glob($publishedDir . '/*.html') ?: [] as $file) {
            $post = $this->parseFile($file);
            if ($post && $post['slug'] === $slug) {
                return $post;
            }
        }

        return null;
    }

    /**
     * Get all published posts, sorted newest first.
     *
     * @return array<int, array>
     */
    public function getAll(): array
    {
        $publishedDir = $this->contentDir . '/published';
        $posts = [];
        $files = glob($publishedDir . '/*.html') ?: [];

        foreach ($files as $file) {
            $post = $this->parseFile($file);
            if ($post) {
                $posts[] = $post;
            }
        }

        usort($posts, function (array $a, array $b): int {
            $cmp = strcmp($b['timestamp'], $a['timestamp']);
            if ($cmp !== 0) {
                return $cmp;
            }
            $mtimeCmp = ($b['mtime'] ?? 0) <=> ($a['mtime'] ?? 0);
            if ($mtimeCmp !== 0) {
                return $mtimeCmp;
            }
            return strcmp($a['slug'], $b['slug']);
        });

        return $posts;
    }

    /**
     * Parse an HTML post file.
     *
     * @param string $filePath
     * @return array|null
     */
    public function parseFile(string $filePath): ?array
    {
        $raw = @file_get_contents($filePath);
        if ($raw === false) {
            return null;
        }

        $basename = basename($filePath, '.html');
        $date = '';
        $time = '';
        $slug = $basename;

        // Try extracting timestamp from YYYY-MM-DD-HHMM[-SS]-slug or YYYY_MM_DD_HHMM[SS]_slug
        if (preg_match('/^(\d{4})[-_](\d{2})[-_](\d{2})[-_](\d{2})(\d{2})(?:(\d{2}))?[-_](.*)$/', $basename, $m)) {
            $date = "{$m[1]}-{$m[2]}-{$m[3]}";
            $time = "{$m[4]}:{$m[5]}" . (!empty($m[6]) ? ":{$m[6]}" : ":00");
            $slug = $m[7];
        } elseif (preg_match('/^(\d{4}-\d{2}-\d{2})-(.*)$/', $basename, $m)) {
            $date = $m[1];
            $slug = $m[2];
        }

        $title = '';
        $body = $raw;

        // Parse HTML comment header: <!-- title: ... date: ... -->
        if (preg_match('/^<!--\s*\r?\n(.*?)\r?\n-->\s*\r?\n?(.*)$/s', $raw, $matches)) {
            $header = $matches[1];
            $body = $matches[2];
            foreach (explode("\n", $header) as $line) {
                if (strpos($line, ':') !== false) {
                    [$k, $v] = explode(':', $line, 2);
                    $k = strtolower(trim($k));
                    $v = trim($v, " \t\r\n\"'");
                    if ($k === 'title') {
                        $title = $v;
                    } elseif ($k === 'date') {
                        $date = $v;
                    } elseif ($k === 'slug') {
                        $slug = $v;
                    }
                }
            }
        } elseif (preg_match('/^<h3>(.*?)<time datetime="(.*?)">(.*?)<\/time><\/h3>\s*(.*)$/s', $raw, $matches)) {
            // Support raw existing HTML format without comment header
            $title = trim(strip_tags($matches[1]));
            $date = trim($matches[2]);
            $body = $matches[4];
        }

        // Format dates
        $timestampStr = !empty($time) ? "{$date} {$time} UTC" : $date;
        $unixTimestamp = !empty($date) ? strtotime($timestampStr) : filemtime($filePath);
        $formattedDate = date('F j, Y', $unixTimestamp);

        // Compute plain-text excerpt for meta description
        $plainText = trim(strip_tags($body));
        $excerpt = mb_substr(preg_replace('/\s+/', ' ', $plainText), 0, 160);

        return [
            'slug' => $slug,
            'title' => $title ?: $slug,
            'date' => $date,
            'timestamp' => !empty($time) ? "{$date}-{$time}" : "{$date}-00:00:00",
            'formattedDate' => $formattedDate,
            'body' => trim($body),
            'excerpt' => $excerpt,
            'filePath' => $filePath,
            'mtime' => @filemtime($filePath) ?: 0,
        ];
    }
}
