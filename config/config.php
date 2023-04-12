<?php
require_once dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR . "autoload.php";

define('PROJECT_DIR', dirname(__DIR__));
const DATA_FOLDER =  PROJECT_DIR . DIRECTORY_SEPARATOR . "data";
const USER_DATA_PATH = DATA_FOLDER . DIRECTORY_SEPARATOR. 'userCollection.json';
const USER_TASKS_PATH = DATA_FOLDER . DIRECTORY_SEPARATOR. 'tasksForUsers.json';
const USER_AVAILABLE_PATH = DATA_FOLDER . DIRECTORY_SEPARATOR. 'availableUsers.json';
const PET_BREEDS_PATH = DATA_FOLDER . DIRECTORY_SEPARATOR. 'breeds.json';
const TASK_DEFAULT_DATA = DATA_FOLDER . DIRECTORY_SEPARATOR. 'taskItems.json';