<?php
require_once dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR . "autoload.php";

use Dotenv\Dotenv;

try {
    $dotenv = Dotenv::createImmutable(dirname(__DIR__, 1));
    $dotenv->load();
} catch (Exception $e) {
    printf("Ошибка при подключении окружения: %s в файле %s(%d)", $e->getMessage(), $e->getFile(), $e->getLine());
    exit(1);
}

define('PROJECT_DIR', dirname(__DIR__));

define('API_DOMAIN', $_ENV['API_DOMAIN']);
define('API_KEY', $_ENV['API_KEY']);
const DATA_FOLDER = PROJECT_DIR . DIRECTORY_SEPARATOR . "data";
const USER_DATA_PATH = DATA_FOLDER . DIRECTORY_SEPARATOR . 'userCollection.json';
const USER_TASKS_PATH = DATA_FOLDER . DIRECTORY_SEPARATOR . 'tasksForUsers.json';
const USER_AVAILABLE_PATH = DATA_FOLDER . DIRECTORY_SEPARATOR . 'availableUsers.json';
const PET_BREEDS_PATH = DATA_FOLDER . DIRECTORY_SEPARATOR . 'breeds.json';
const TASK_DEFAULT_DATA = DATA_FOLDER . DIRECTORY_SEPARATOR . 'taskItems.json';