<?php

defined('IN_APP') || exit;

umask(0002);

require_once __DIR__ . '/vendor/autoload.php';
(Dotenv\Dotenv::createImmutable(__DIR__))->safeLoad();

define('BASE_URL', $_ENV['BASE_URL'] ?? 'https://neverball.org');
const BASE_DIR = __DIR__;
const CONTROLLER_DIR = BASE_DIR . '/src';
const LOG_DIR = BASE_DIR . '/logs';

// Create secure log directory protected by .htaccess
if (!is_dir(LOG_DIR)) {
    @mkdir(LOG_DIR, 0775, true);
}
if (!file_exists(LOG_DIR . '/.htaccess')) {
    @file_put_contents(LOG_DIR . '/.htaccess', "Options -Indexes\n<FilesMatch \".*\">\n    Deny from all\n</FilesMatch>\n");
}

ini_set('log_errors', '1');
ini_set('error_log', LOG_DIR . '/php_errors.log');

$vite = new mindplay\vite\Manifest(
    dev:           ($_ENV['APP_ENV'] ?? 'production') === 'development',
    manifest_path: BASE_DIR . '/dist/.vite/manifest.json',
    base_path:     '/dist/',
);