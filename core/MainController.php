<?php
/**
 * Created by PhpStorm.
 * User: hava
 * Date: 10.09.18
 * Time: 08:24
 */
namespace core;
include 'Controller/SuperController.php';

class MainController extends Controller\SuperController
{
    function loadIndexSite(){
        $this->goToSite('/themes/home.php');
    }
}

$mainController = new MainController();
$mainController->loadIndexSite();

