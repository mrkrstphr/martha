<?php

/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

// Composer autoloading
if (file_exists('vendor/autoload.php')) {
    $loader = include 'vendor/autoload.php';
} else {
    throw new RuntimeException('Unable to load required libraries. Please run: composer install');
}

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();
