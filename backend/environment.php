<?php
/**
 * This file is part of Skelsus.
 * 
 * Sets up application environment from file.
 */

$envFile = ".environment";

if (file_exists(__DIR__ . '/' . $envFile)) {
    $env = trim(file_get_contents($envFile));
} else {
    $env = 'development';
}

if (!defined('APP_ENV')) {
    define('APP_ENV', $env);
}

