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
    function render($link ,$alert_message) {
        $this->index = file_get_contents('/var/www/html/themes/index.html');
        if(!isset($_SESSION))
        {
            session_start();
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

        if ($alert_message != ''){
            $alert_box = file_get_contents('/var/www/html/themes/alert.html');
            $this->index = str_replace('{ALERT}', $alert_box, $this->index);
            $this->index = str_replace('{MESSAGE}', $alert_message, $this->index);
        }else{
            $this->index = str_replace('{ALERT}', '', $this->index);
        }

        $this->index = str_replace('{HEADER_HTML}', $contentHeader, $this->index);
        $this->index = str_replace('{CONTENT_HTML}', $contentTemplate, $this->index);
        echo $this->index;
    }

    function alert($alert_message){

    }

    function getContent($link){
        $contentTemplate = file_get_contents($link);
        return $contentTemplate;
    }
}