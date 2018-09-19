<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
    require_once dirname(__FILE__).'/core/MainController.php';
    if (!isset($_GET['link'])){
        $link = '';
    }else{
        $link = $_GET['link'];
    }
    $mainController = new \core\MainController();
    $mainController->lookWhereToGo($link);

