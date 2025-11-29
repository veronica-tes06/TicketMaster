<?php

// Define testing mode to prevent exits in controllers
define('TESTING_MODE', true);

// Load Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Load configuration constants (if needed by controllers)
require_once __DIR__ . '/../app/config/config.php';
