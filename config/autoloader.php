<?php

/**
 * Class Autoloader for Vadrouille & Bourlingue
 */
spl_autoload_register(static function ($className) {
    // Paths from root directory to look for class files
    $paths = [
        __DIR__ . '/../app/controllers/' . $className . '.php',
        __DIR__ . '/../app/models/' . $className . '.php',
        __DIR__ . '/../app/core/' . $className . '.php',
        __DIR__ . '/../app/entities/' . $className . '.php',
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});
