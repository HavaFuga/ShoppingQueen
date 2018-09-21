<?php
/**
 * Created by PhpStorm.
 * User: hava
 * Date: 07.09.18
 * Time: 14:18
 */
namespace core\Controller;

use PDO;

include_once __DIR__ . '/../View/SuperView.php';
include_once __DIR__ . '/../Model/SuperModel.php';



$model = new \core\Model\SuperModel();

class SuperController
{


    function goToSite($link){
        $view = new \core\View\SuperView();
        $view->render($link);
    }
}