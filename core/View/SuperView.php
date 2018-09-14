<?php
/**
 * Created by PhpStorm.
 * User: hava
 * Date: 07.09.18
 * Time: 14:18
 */
namespace core\View;
include_once __DIR__ . '/../Model/SuperModel.php';
include_once __DIR__ . '/../Controller/SuperController.php';


$model = new \core\Model\SuperModel();
$controller = new \core\Controller\SuperController();


class SuperView
{
    protected $index;
    //Fills Site Content
    function render($link) {
        $this->index = file_get_contents('/var/www/html/themes/index.html');
        $contentTemplate = file_get_contents($link);
        $this->index = str_replace('{CONTENT_HTML}', $contentTemplate, $this->index);
        //header('LOCATION: /themes/index.html');
        echo $this->index;
    }
}