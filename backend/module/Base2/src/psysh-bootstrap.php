<?php
/**
 * Bootstrap Zend Framework 2 for psysh
 */
echo "- Setting up application environment" . PHP_EOL;
include('environment.php');

echo "- Application environment is '" . APP_ENV . "'" . PHP_EOL;

echo "- Setting up autoloader" . PHP_EOL;
include('init_autoloader.php');

echo "- Initializing the ZF2 application" . PHP_EOL;
Zend\Mvc\Application::init(require 'config/application.config.php');

echo "- Ready" . PHP_EOL;

echo PHP_EOL;

