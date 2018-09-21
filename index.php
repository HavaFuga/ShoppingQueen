<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
    require_once dirname(__FILE__).'/core/MainController.php';
    $mainController = new \core\MainController();
    $mainController->lookWhereToGo($_SERVER['REQUEST_URI']);

