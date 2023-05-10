<?php
require_once dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "config.php";

require_once dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . "App.php";

require_once dirname(__DIR__, 1) . 'vendor' . DIRECTORY_SEPARATOR . 'predis' . DIRECTORY_SEPARATOR . 'predis' . DIRECTORY_SEPARATOR . 'autoload.php';

Predis\Autoloader::register();
exit(0);