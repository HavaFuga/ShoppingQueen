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
        if (!headers_sent()) {
            //session_start();
        }
        //Checks if User is logged in
        //echo 'session: ' . $_SESSION['user'];
        if (!isset($_SESSION['user'])){
            $contentHeader = file_get_contents('/var/www/html/themes/header_public.html');
        }else{
            $contentHeader = file_get_contents('/var/www/html/themes/header_member.html');
        }

        if (is_file($link) == true){
            $contentTemplate = $this->getContent($link);
        }else {
            $contentTemplate = $link;
        }

        $this->index = str_replace('{HEADER_HTML}', $contentHeader, $this->index);
        $this->index = str_replace('{CONTENT_HTML}', $contentTemplate, $this->index);
        echo $this->index;
    }

    function getContent($link){
        $contentTemplate = file_get_contents($link);
        return $contentTemplate;
    }
}