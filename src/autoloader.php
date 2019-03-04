<?php
spl_autoload_register(function ($class) {
    if (0 !== mb_strpos($class, 'Gettext\\')) {
        return;
    }

    $file = __DIR__ . str_replace('\\', DIRECTORY_SEPARATOR, mb_substr($class, mb_strlen('Gettext'))) . '.php';

    if (is_file($file)) {
        require_once $file;
    }
});
