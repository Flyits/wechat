<?php

// autoload_psr4.php @generated by Composer

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

return array(
    'think\\' => array($vendorDir . '/topthink/framework/src/think', $vendorDir . '/topthink/think-helper/src', $vendorDir . '/topthink/think-orm/src'),
    'flyits\\wechat\\' => array($baseDir . '/src'),
    'flyits\\tool\\' => array($vendorDir . '/flyits/tools/src'),
    'Psr\\SimpleCache\\' => array($vendorDir . '/psr/simple-cache/src'),
    'Psr\\Log\\' => array($vendorDir . '/psr/log/Psr/Log'),
    'Psr\\Container\\' => array($vendorDir . '/psr/container/src'),
    'Psr\\Cache\\' => array($vendorDir . '/psr/cache/src'),
    'Opis\\Closure\\' => array($vendorDir . '/opis/closure/src'),
    'League\\Flysystem\\Cached\\' => array($vendorDir . '/league/flysystem-cached-adapter/src'),
    'League\\Flysystem\\' => array($vendorDir . '/league/flysystem/src'),
);
