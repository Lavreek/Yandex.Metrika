<?php
define('ROOT_PATH', __DIR__);

require_once ROOT_PATH . "/src/settings/Remove.php";

$options = getopt("l::c::");

$remove = new Remove($options);
