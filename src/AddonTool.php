<?php

namespace Neverball;

defined('IN_APP') || exit;

class AddonTool
{
    private const MAX_ZIP_SIZE = 20 * 1024 * 1024; // 20 MB
    private const MAX_FILE_COUNT = 4096;

    private string $uploadDir;

    public function __construct()
    {
        $this->uploadDir = BASE_DIR . '/uploads';
    }

    // -------------------------------------------------------------------------
    // Public entry points
    // -------------------------------------------------------------------------

    public function handlePost(): void
    {
        header('Content-Type: application/json');

        $this->checkAntispam();
        $this->checkRateLimit();
        $this->validateEmail();
        $this->validateTextFields();

        $tmpPath = $this->validateUpload();
        $this->checkDuplicate($tmpPath);
        $zip     = $this->extractZip($tmpPath);

        $storagePath   = $this->storeZip($tmpPath, $zip['id']);
        $approvalToken = $this->generateApprovalToken($zip, $storagePath);

        $this->notifyAdmin($zip, $storagePath, $approvalToken);

        $this->jsonSuccess(['message' => 'Submission received. Thank you!']);
    }

    private function checkRateLimit(): void
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $ipHash = md5($ip);
        $rateDir = $this->uploadDir . '/.ratelimit';
        if (!is_dir($rateDir)) {
            mkdir($rateDir, 0755, true);
        }
        $ipFile = $rateDir . '/' . $ipHash;
        $now = time();
        $requests = [];
        if (file_exists($ipFile)) {
            $requests = json_decode(file_get_contents($ipFile), true) ?: [];
        }
        $requests = array_filter($requests, fn($t) => $t > $now - 3600);
        if (count($requests) >= 5) {
            $this->jsonError('Too many submissions. Please try again later.');
        }
        $requests[] = $now;
        file_put_contents($ipFile, json_encode(array_values($requests)));
    }

    private function notifyAdmin(array $zip, string $storagePath, string $approvalToken): void
    {
        $to = $_ENV['NOTIFY_EMAIL'] ?? null;
        if (!$to) {
            return;
        }

        $name    = $_POST['name'] ?? 'N/A';
        $email   = $_POST['email'] ?? '';
        $message = $_POST['message'] ?? '';

        $fullSubject = 'New Neverball Addon Submission: ' . $zip['addonName'];
        $url         = BASE_URL . '/uploads/' . basename($storagePath);
        $approveUrl  = BASE_URL . '/addon-tool/approve?token=' . $approvalToken;

        $body = "A new addon has been submitted.\n\n"
            . "Submitter Name: $name\n"
            . "Submitter Email: $email\n"
            . "Addon Name: " . $zip['addonName'] . "\n"
            . "ID: " . $zip['id'] . "\n\n"
            . "Message:\n$message\n\n"
            . "Download URL: $url\n\n"
            . "--- Open pull request ---\n"
            . "$approveUrl\n";

        $from = $_ENV['NOTIFY_FROM'] ?? 'neverball-noreply@snth.net';
        $headers = "From: $from\r\nContent-Type: text/plain; charset=UTF-8";
        mail($to, '=?UTF-8?B?' . base64_encode($fullSubject) . '?=', $body, $headers);
    }


    // -------------------------------------------------------------------------
    // Antispam
    // -------------------------------------------------------------------------

    private function checkAntispam(): void
    {
        // Honeypot: hidden text field must be empty
        if (!empty($_POST['website'])) {
            $this->jsonSuccess([]);
        }

        // Time check: form must have been loaded at least 4 seconds ago
        $loaded = (int) ($_POST['form_loaded_at'] ?? 0);
        if ($loaded === 0 || (time() - $loaded) < 4) {
            $this->jsonSuccess([]);
        }
    }

    // -------------------------------------------------------------------------
    // Email validation
    // -------------------------------------------------------------------------

    private function validateEmail(): void
    {
        $email = $_POST['email'] ?? '';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->jsonError('Invalid email address.');
        }
    }

    private function validateTextFields(): void
    {
        $name    = trim($_POST['name'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if ($name === '' || mb_strlen($name) > 200) {
            $this->jsonError('Name is required and must be under 200 characters.');
        }

        if ($message === '' || mb_strlen($message) > 5000) {
            $this->jsonError('Message is required and must be under 5000 characters.');
        }
    }

    // -------------------------------------------------------------------------
    // Upload validation
    // -------------------------------------------------------------------------

    private function validateUpload(): string
    {
        $file = $_FILES['zip'] ?? null;

        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            $err = $file['error'] ?? 'no file';
            error_log("neverball-addon-tool: upload failed with error $err");
            $this->jsonError('Upload failed. Please try again.');
        }

        if ($file['size'] > self::MAX_ZIP_SIZE) {
            $this->jsonError('File too large. Maximum size is 20 MB.');
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime  = $finfo->file($file['tmp_name']);

        if (!in_array($mime, ['application/zip', 'application/x-zip-compressed', 'application/octet-stream'], true)) {
            $this->jsonError('Invalid file type. Please upload a ZIP file.');
        }

        return $file['tmp_name'];
    }

    // -------------------------------------------------------------------------
    // ZIP extraction
    // -------------------------------------------------------------------------

    private function extractZip(string $tmpPath): array
    {
        try {
            $zipFile = new \PhpZip\ZipFile();
            $zipFile->openFile($tmpPath);

            if ($zipFile->count() > self::MAX_FILE_COUNT) {
                $zipFile->close();
                $this->jsonError('ZIP contains too many files (max ' . self::MAX_FILE_COUNT . ').');
            }

            $uncompressedSize = 0;
            foreach ($zipFile->getEntries() as $entry) {
                $uncompressedSize += $entry->getUncompressedSize();
            }
            if ($uncompressedSize > 200 * 1024 * 1024) {
                $zipFile->close();
                $this->jsonError('ZIP uncompressed size is too large (max 200 MB).');
            }

            // Detect all set-*.txt files at ZIP root
            $setFiles = [];

            foreach ($zipFile->getListFiles() as $name) {
                if (preg_match('/^set-([a-zA-Z0-9_-]+)\.txt$/', $name, $m)) {
                    $setFiles[] = ['path' => $name, 'slug' => $m[1]];
                }
            }

            if (empty($setFiles)) {
                $zipFile->close();
                $this->jsonError('Cannot determine addon type: no set-*.txt file found at ZIP root.');
            }

            // Use first set for the archive ID; collect names from all sets
            $firstSet   = $setFiles[0];
            $id         = 'set-' . $firstSet['slug'];
            $addonNames = [];

            foreach ($setFiles as $sf) {
                $setContent   = $zipFile->getEntryContents($sf['path']);
                $lines        = explode("\n", $setContent);
                $addonNames[] = trim($lines[0] ?? '') ?: ('set-' . $sf['slug']);
            }

            $addonName = implode(', ', $addonNames);

            // Extract all files, guarding against path traversal
            foreach ($zipFile->getListFiles() as $name) {
                if (str_contains($name, '..') || str_starts_with($name, '/') || str_starts_with($name, '\\')) {
                    $zipFile->close();
                    $this->jsonError('ZIP contains invalid file paths.');
                }
            }

            $zipFile->close();

            return compact('id', 'addonName');
        } catch (\Nelexa\Zip\Exception\ZipException $e) {
            error_log("neverball-addon-tool: ZIP error " . $e->getMessage());
            $this->jsonError('Could not open ZIP file.');
        }
    }

    // -------------------------------------------------------------------------
    // Duplicate detection
    // -------------------------------------------------------------------------

    private function hashDir(): string
    {
        return $this->uploadDir . '/.hashes';
    }

    private function checkDuplicate(string $tmpPath): void
    {
        $hash    = hash_file('sha256', $tmpPath);
        $hashDir = $this->hashDir();

        if (is_file($hashDir . '/' . $hash)) {
            $this->jsonError('This file has already been submitted.');
        }
    }

    // -------------------------------------------------------------------------
    // ZIP storage
    // -------------------------------------------------------------------------

    private function storeZip(string $tmpPath, string $id): string
    {
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }

        // Add a random 16-character suffix for security
        $randomSuffix = bin2hex(random_bytes(8));
        $filename     = $id . '-' . $randomSuffix . '.zip';
        $dest         = $this->uploadDir . '/' . $filename;

        if (!copy($tmpPath, $dest)) {
            throw new \RuntimeException('Failed to store ZIP.');
        }

        // Record hash to prevent duplicate submissions
        $hashDir = $this->hashDir();
        if (!is_dir($hashDir)) {
            mkdir($hashDir, 0755, true);
        }
        file_put_contents($hashDir . '/' . hash_file('sha256', $tmpPath), '');

        return $dest;
    }

    // -------------------------------------------------------------------------
    // Approval token
    // -------------------------------------------------------------------------

    public function generateApprovalToken(array $zip, string $storagePath): string
    {
        $tokenDir = $this->uploadDir . '/.tokens';
        if (!is_dir($tokenDir)) {
            mkdir($tokenDir, 0755, true);
        }

        $token = bin2hex(random_bytes(16));
        $data  = json_encode([
            'zip'       => basename($storagePath),
            'id'        => $zip['id'],
            'addonName' => $zip['addonName'],
        ]);
        file_put_contents($tokenDir . '/' . $token, $data);

        return $token;
    }

    public static function peekToken(string $uploadDir, string $token): ?array
    {
        if (!preg_match('/^[0-9a-f]{32}$/', $token)) {
            return null;
        }

        $path = $uploadDir . '/.tokens/' . $token;
        if (!is_file($path)) {
            return null;
        }

        $data = json_decode(file_get_contents($path), true);
        return $data ?: null;
    }

    public static function consumeToken(string $uploadDir, string $token): ?array
    {
        if (!preg_match('/^[0-9a-f]{32}$/', $token)) {
            return null;
        }

        $path = $uploadDir . '/.tokens/' . $token;
        if (!is_file($path)) {
            return null;
        }

        $data = json_decode(file_get_contents($path), true);
        unlink($path); // one-time use

        return $data ?: null;
    }


    // -------------------------------------------------------------------------
    // Response helpers
    // -------------------------------------------------------------------------

    /** @return never */
    private function jsonError(string $msg): void
    {
        echo json_encode(['success' => false, 'error' => $msg]);
        exit;
    }

    /** @return never */
    private function jsonSuccess(array $data): void
    {
        echo json_encode(['success' => true] + $data);
        exit;
    }
}

// ---------------------------------------------------------------------------
// Route dispatch
// ---------------------------------------------------------------------------

if (isset($_GET['token']) && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $token = $_GET['token'];
    $data  = AddonTool::peekToken(BASE_DIR . '/uploads', $token);

    header('Content-Type: text/html; charset=UTF-8');
    echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Addon Approval – Neverball</title>
    <link rel="icon" href="/images/favicon-modern.svg">
    ' . $GLOBALS['vite']->createTags('resources/js/addon-tool.js')->css . '
</head>
<body class="min-h-screen bg-orange-50 text-gray-900 font-sans">
<div class="max-w-2xl mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold text-orange-600 mb-6">Addon Approval</h1>';

    if ($data) {
        echo '<div class="bg-white p-6 rounded-lg shadow-sm border border-orange-100">';
        echo '<p class="text-base text-gray-700 mb-6">Are you sure you want to approve the addon <strong>' . htmlspecialchars($data['addonName']) . '</strong>?</p>';
        echo '<form method="POST" action="?token=' . htmlspecialchars($token) . '">';
        echo '<button type="submit" class="px-6 py-3 rounded-md bg-orange-500 text-white text-base font-semibold hover:bg-orange-600 transition-colors">Approve Addon</button>';
        echo '</form>';
        echo '</div>';
    } else {
        echo '<div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">';
        echo '<span class="font-medium">Error:</span> Invalid or expired approval link.';
        echo '</div>';
    }

    echo '</div></body></html>';
    exit;
}

if (isset($_GET['token']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_GET['token'];
    $data  = AddonTool::consumeToken(BASE_DIR . '/uploads', $token);

    $ok  = false;
    $msg = 'Invalid or expired approval link.';

    if ($data) {
        $repo  = $_ENV['GITHUB_PACKAGES_REPO'] ?? null;
        $pat   = $_ENV['GITHUB_DISPATCH_TOKEN'] ?? null;

        if (!$repo || !$pat) {
            $msg = 'GitHub dispatch not configured on this server.';
        } else {
            $payload = json_encode([
                'event_type'     => 'addon-submission',
                'client_payload' => [
                    'zip_url'    => BASE_URL . '/uploads/' . $data['zip'],
                    'assets_url' => BASE_URL . '/neverball-assets.json',
                    'addon_id'   => $data['id'],
                    'addon_name' => $data['addonName'],
                ],
            ]);

            $ch = curl_init("https://api.github.com/repos/$repo/dispatches");
            curl_setopt_array($ch, [
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => $payload,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER     => [
                    'Accept: application/vnd.github+json',
                    'Authorization: Bearer ' . $pat,
                    'Content-Type: application/json',
                    'User-Agent: neverball-website',
                ],
            ]);
            curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 204) {
                $ok  = true;
                $msg = 'Pull request workflow triggered for <strong>' . htmlspecialchars($data['addonName']) . '</strong>. Check GitHub Actions for progress.';
            } else {
                $msg = 'GitHub API returned HTTP ' . $httpCode . '. Check server configuration.';
            }
        }
    }

    header('Content-Type: text/html; charset=UTF-8');
    echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Addon Approval – Neverball</title>
    <link rel="icon" href="/images/favicon-modern.svg">
    ' . $GLOBALS['vite']->createTags('resources/js/addon-tool.js')->css . '
</head>
<body class="min-h-screen bg-orange-50 text-gray-900 font-sans">
<div class="max-w-2xl mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold text-orange-600 mb-6">Addon Approval</h1>';

    if ($ok) {
        echo '<div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">';
        echo '<span class="font-medium">Success:</span> ' . $msg;
        echo '</div>';
    } else {
        echo '<div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">';
        echo '<span class="font-medium">Error:</span> ' . $msg;
        echo '</div>';
    }

    echo '</div></body></html>';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    // Catch any unhandled errors and return JSON instead of raw PHP output
    set_error_handler(function ($errno, $errstr, $errfile, $errline) {
        // Ignore deprecation notices
        if ($errno === E_DEPRECATED || $errno === E_USER_DEPRECATED) {
            return;
        }
        error_log("neverball-addon-tool: PHP error $errstr in $errfile:$errline");
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'An internal error occurred.']);
        exit;
    });

    try {
        (new \Neverball\AddonTool())->handlePost();
    } catch (\Throwable $e) {
        error_log("neverball-addon-tool: Exception " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine());
        echo json_encode(['success' => false, 'error' => 'An internal error occurred.']);
    }
    exit;
}

// GET: render the page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Addon Tool – Neverball</title>
    <link rel="icon" href="/images/favicon-modern.svg">
    <?= $GLOBALS['vite']->createTags('resources/js/addon-tool.js')->css ?>
</head>
<body class="min-h-screen bg-orange-50 text-gray-900 font-sans">
<div class="max-w-2xl mx-auto px-4 py-12">

    <h1 class="text-3xl font-bold text-orange-600 mb-2">Addon Tool</h1>
    <p class="text-gray-500 mb-8 text-base leading-relaxed">
        Check your Neverball level set for errors, then (optionally) submit it for inclusion
        in the in-game downloads. Your submission will be reviewed before being accepted.
    </p>

    <noscript>
        <p class="text-orange-600 font-semibold mb-4">JavaScript is required to use this form.</p>
    </noscript>

    <form id="submit-form" novalidate class="flex flex-col gap-6">

        <!-- Honeypot: must remain empty -->
        <input type="text" name="website" id="website"
               style="display:none" tabindex="-1" aria-hidden="true" autocomplete="off">
        <input type="hidden" name="form_loaded_at" id="form_loaded_at">

        <!-- Step 1: ZIP upload -->
        <div id="upload-section">
            <label for="zip-file" class="block text-base font-semibold mb-2">ZIP file</label>
            <input type="file" id="zip-file" name="zip" accept=".zip" required autocomplete="off"
                   class="block text-base text-gray-500 cursor-pointer
                          file:mr-4 file:py-2.5 file:px-5 file:rounded-md file:border-0
                          file:text-base file:font-semibold
                          file:bg-orange-500 file:text-white hover:file:bg-orange-600">
        </div>

        <!-- Step 2: Validation result (shown by JS) -->
        <div id="validation-result" style="display:none"
             class="rounded-xl border border-orange-200 bg-white p-5 flex flex-col gap-4 shadow-sm">

            <div id="validation-errors" style="display:none">
                <p class="text-base font-semibold text-red-600 mb-2">
                    Validation failed. Fix the errors below and try again:
                </p>
                <div id="error-list" class="text-base text-gray-700"></div>
            </div>

            <div id="validation-pass" style="display:none" class="flex flex-col gap-4">
                <p class="text-base font-semibold text-emerald-600 text-center uppercase tracking-wide">
                    Validation passed
                </p>
                <dl id="addon-meta" class="grid grid-cols-[auto_1fr] gap-x-4 gap-y-1 text-base"></dl>
                <div>
                    <p class="text-base font-semibold mb-1">Files included</p>
                    <ul id="file-list"
                        class="font-mono text-sm bg-orange-50 border border-orange-200
                               rounded p-3 max-h-48 overflow-y-auto list-none"></ul>
                </div>
            </div>
        </div>

        <!-- Step 3: Submission form (shown by JS after validation passes) -->
        <div id="submission-section" style="display:none"
             class="rounded-xl border border-orange-200 bg-white p-5 flex flex-col gap-4 shadow-sm">

            <h2 class="text-lg font-semibold text-gray-900">Submit for Inclusion</h2>
            <p class="text-base text-gray-500">
                Provide your details below. Your addon will be reviewed before
                being added to the in-game addons repository.
            </p>

            <div class="flex flex-col gap-1">
                <label for="submitter-name" class="text-base font-semibold">Your name</label>
                <input type="text" id="submitter-name" name="name" required
                       class="w-full rounded-md border border-gray-300 bg-white text-gray-900 px-4 py-3 text-base
                              focus:outline-none focus:ring-2 focus:ring-orange-500">
            </div>

            <div class="flex flex-col gap-1">
                <label for="submitter-email" class="text-base font-semibold">Email</label>
                <input type="email" id="submitter-email" name="email" required
                       class="w-full rounded-md border border-gray-300 bg-white text-gray-900 px-4 py-3 text-base
                              focus:outline-none focus:ring-2 focus:ring-orange-500">
            </div>

            <div class="flex flex-col gap-1">
                <label for="submit-message" class="text-base font-semibold">Message</label>
                <textarea id="submit-message" name="message" rows="5" required
                          class="w-full rounded-md border border-gray-300 bg-white text-gray-900 px-4 py-3 text-base
                                 focus:outline-none focus:ring-2 focus:ring-orange-500 resize-y"></textarea>
            </div>

            <div class="flex items-start gap-3">
                <input type="checkbox" id="auth-confirm" required
                       class="mt-1 h-5 w-5 accent-orange-500 cursor-pointer flex-shrink-0">
                <label for="auth-confirm" class="text-base text-gray-500 cursor-pointer leading-relaxed">
                    I certify that I am the author of this addon or that I have the
                    explicit permission of the original authors to submit this version.
                </label>
            </div>

            <div>
                <button type="submit" id="submit-btn" disabled
                        class="px-6 py-3 rounded-md bg-orange-500 text-white text-base
                               font-semibold hover:bg-orange-600 transition-colors
                               disabled:opacity-40 disabled:cursor-not-allowed">
                    Submit for Inclusion
                </button>
            </div>
        </div>

        <!-- Step 4: Status message (shown by JS) -->
        <div id="submit-status" style="display:none"
             class="text-base px-4 py-3 rounded-md bg-white border border-orange-200 shadow-sm">
        </div>

    </form>
</div>

<?= $GLOBALS['vite']->createTags('resources/js/addon-tool.js')->js ?>
<script>
(function () {
    'use strict';

    document.getElementById('form_loaded_at').value = Math.floor(Date.now() / 1000);

    const fileInput    = document.getElementById('zip-file');
    const result       = document.getElementById('validation-result');
    const errorsBox    = document.getElementById('validation-errors');
    const errorList    = document.getElementById('error-list');
    const passBox      = document.getElementById('validation-pass');
    const addonMeta    = document.getElementById('addon-meta');
    const submitBtn    = document.getElementById('submit-btn');
    const authConfirm  = document.getElementById('auth-confirm');
    const statusBox    = document.getElementById('submit-status');
    const form         = document.getElementById('submit-form');

    authConfirm.addEventListener('change', function() {
        submitBtn.disabled = !authConfirm.checked;
    });

    const submitSection = document.getElementById('submission-section');

    function reset() {
        result.style.display        = 'none';
        errorsBox.style.display     = 'none';
        passBox.style.display       = 'none';
        submitSection.style.display = 'none';
        statusBox.style.display     = 'none';
        errorList.innerHTML         = '';
        addonMeta.innerHTML         = '';
        authConfirm.checked         = false;
        submitBtn.disabled          = true;
    }

    function showErrors(sets, topErrors) {
        result.style.display    = '';
        errorsBox.style.display = '';

        function makeCode(text) {
            var el = document.createElement('code');
            el.className = 'font-mono bg-orange-100 text-orange-800 px-1 rounded text-xs ring-1 ring-orange-200';
            el.textContent = text;
            return el;
        }

        // Top-level errors (e.g. no set file found) have no set context
        if (topErrors.length) {
            var ul = document.createElement('ul');
            ul.className = 'list-disc pl-5 space-y-1 text-base';
            topErrors.forEach(function(e) {
                var li = document.createElement('li');
                li.textContent = e.message || JSON.stringify(e);
                if (e.found !== undefined) {
                    var found = document.createElement('div');
                    found.className = 'mt-1 text-gray-500';
                    found.textContent = e.found.length
                        ? 'Found at root: ' + e.found.join(', ') + (e.foundMore ? '…' : '')
                        : 'Nothing found at root — the ZIP may have a top-level folder.';
                    li.appendChild(found);
                }
                ul.appendChild(li);
            });
            errorList.appendChild(ul);
        }

        sets.filter(s => !s.valid).forEach(function(s) {
            var setFile = s.id + '.txt';

            var setLabel = document.createElement('p');
            setLabel.className = 'text-base font-semibold mt-3 mb-1';
            setLabel.textContent = setFile;
            errorList.appendChild(setLabel);

            // Group errors by parent, sorted by filename
            var groups = new Map();
            s.errors.forEach(function(e) {
                var key = e.parent || '';
                if (!groups.has(key)) groups.set(key, []);
                groups.get(key).push(e);
            });
            groups = new Map([...groups].sort(([a], [b]) => a.localeCompare(b)));

            var parentsUl = document.createElement('ul');
            parentsUl.className = 'list-disc pl-5 space-y-2 text-base';

            groups.forEach(function(errors, parent) {
                var parentLi = document.createElement('li');

                // Show the intermediate parent file unless it IS the set file
                if (parent && parent !== setFile) {
                    parentLi.appendChild(makeCode(parent));
                }

                var assetsUl = document.createElement('ul');
                assetsUl.className = 'list-disc pl-5 mt-1 space-y-0.5';
                errors.forEach(function(e) {
                    var assetLi = document.createElement('li');
                    if (e.path) {
                        assetLi.appendChild(document.createTextNode('Missing ' + e.type + ': '));
                        assetLi.appendChild(makeCode(e.path));
                    } else {
                        assetLi.textContent = e.message || JSON.stringify(e);
                    }
                    assetsUl.appendChild(assetLi);
                });
                parentLi.appendChild(assetsUl);
                parentsUl.appendChild(parentLi);
            });

            errorList.appendChild(parentsUl);
        });
    }

    function showMetadata(sets, fileList) {
        result.style.display        = '';
        passBox.style.display       = '';
        submitSection.style.display = '';

        const list = document.getElementById('file-list');
        list.innerHTML = '';
        fileList.forEach(file => {
            const li = document.createElement('li');
            li.textContent = file;
            list.appendChild(li);
        });

        sets.forEach(function (meta) {
            [
                ['ID',          meta.id],
                ['Name',        meta.name],
                ['Description', meta.description],
            ].forEach(function (pair) {
                if (!pair[1]) return;
                var dt = document.createElement('dt');
                dt.className = 'font-semibold text-gray-700';
                dt.textContent = pair[0];
                var dd = document.createElement('dd');
                dd.className = 'text-gray-500';
                if (pair[0] === 'Description') {
                    dd.innerHTML = escapeHtml(pair[1]).replace(/\\/g, '<br>');
                } else {
                    dd.textContent = pair[1];
                }
                addonMeta.appendChild(dt);
                addonMeta.appendChild(dd);
            });
        });
    }

    function showStatus(html, isError) {
        statusBox.style.display = '';
        statusBox.innerHTML = html;
        statusBox.style.color = isError ? '#dc2626' : '';
    }

    fileInput.addEventListener('change', async function () {
        reset();
        var file = fileInput.files[0];
        if (!file) return;

        showStatus('Validating…', false);

        let res;
        try {
            res = await AddonTool.validate(file);
        } catch (e) {
            showStatus('Validation error: ' + e.message, true);
            return;
        }

        statusBox.style.display = 'none';

        if (!res.valid) {
            showErrors(res.sets, res.errors);
        } else {
            showMetadata(res.sets, res.files.sort());
        }
    });

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        submitBtn.disabled = true;
        showStatus('Submitting…', false);

        var fd = new FormData();
        fd.append('zip',            fileInput.files[0]);
        fd.append('form_loaded_at', document.getElementById('form_loaded_at').value);
        fd.append('website',        '');
        fd.append('name',           document.getElementById('submitter-name').value);
        fd.append('email',          document.getElementById('submitter-email').value);
        fd.append('message',        document.getElementById('submit-message').value);

        try {
            var res  = await fetch('/addon-tool', { method: 'POST', body: fd });
            var data = await res.json();
        } catch (e) {
            showStatus('Network error. Please try again.', true);
            submitBtn.disabled = false;
            return;
        }

        if (data.success) {
            showStatus('Submitted!', false);
            submitBtn.style.display = 'none';
        } else {
            showStatus('Error: ' + escapeHtml(data.error || 'Unknown error'), true);
            submitBtn.disabled = false;
        }
    });

    function escapeHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }
}());
</script>

</body>
</html>
