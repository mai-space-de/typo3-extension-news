<?php

declare(strict_types=1);

$searchBase = dirname(__DIR__) . '/typo3-extension-search';

spl_autoload_register(static function (string $class) use ($searchBase): void {
    $prefix = 'Maispace\\MaiSearch\\';
    if (strncmp($class, $prefix, strlen($prefix)) === 0) {
        $file = $searchBase . '/Classes/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
        return;
    }
});

spl_autoload_register(static function (string $class) use ($searchBase): void {
    $prefix = 'ApacheSolrForTypo3\\Solr\\';
    if (strncmp($class, $prefix, strlen($prefix)) === 0) {
        $file = $searchBase . '/vendor/apache-solr-for-typo3/solr/Classes/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
        return;
    }
});

spl_autoload_register(static function (string $class) use ($searchBase): void {
    $prefix = 'Solarium\\';
    if (strncmp($class, $prefix, strlen($prefix)) === 0) {
        $file = $searchBase . '/vendor/solarium/solarium/src/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
        return;
    }
});
