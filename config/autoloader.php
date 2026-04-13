<?php
/**
 * Class Autoloader for Vadrouille & Bourlingue
 */
spl_autoload_register(function($className) {
    // Paths from root directory to look for class files
    $paths = [
        __DIR__ . '/../app/Controllers/' . $className . '.php',
        __DIR__ . '/../app/Models/' . $className . '.php',
        __DIR__ . '/../app/Core/' . $className . '.php',
        __DIR__ . '/../app/Entities/' . $className . '.php',
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});
