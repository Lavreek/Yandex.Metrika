<?php
define('ROOT_PATH', __DIR__);

require_once ROOT_PATH . "/src/push/Push.php";

$options = getopt("f:a:m:s:");
$push = new Push($options);

if (isset($options['f'])) {
    $push->pushFile();
    die();
} elseif (isset($options['a'])) {
    $push->pushDirectory($options['s']);
    die();
}

throw new Exception("Use -f or -a param for configure filepath.");
