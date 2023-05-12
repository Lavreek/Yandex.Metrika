<?php
define('ROOT_PATH', __DIR__);

require_once ROOT_PATH . "/src/settings/Settings.php";

$options = getopt("l:c:");

$settings = new Settings($options);

if (isset($options['l'])) {
    $settings->createTokenFile();
}

if (isset($options['c'])) {
    $settings->createCounterFile();
}

