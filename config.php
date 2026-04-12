<?php

defined('IN_APP') || exit;

require_once __DIR__ . '/vendor/autoload.php';
(Dotenv\Dotenv::createImmutable(__DIR__))->safeLoad();

define('BASE_URL', $_ENV['BASE_URL'] ?? 'https://neverball.org');
const BASE_DIR = __DIR__;
const CONTROLLER_DIR = BASE_DIR . '/src';

$vite = new mindplay\vite\Manifest(
    dev:           ($_ENV['APP_ENV'] ?? 'production') === 'development',
    manifest_path: BASE_DIR . '/dist/.vite/manifest.json',
    base_path:     '/dist/',
);