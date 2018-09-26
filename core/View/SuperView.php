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

class SuperView
{
    protected $index;

    //Fills Site Content
    public function render(String $link , String $alert_message, String $isTrue) : void {
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

                if ($isTrue == 'true'){
                    $this->index = str_replace('{ALERT_TYPE}', 'success', $this->index);
                }elseif($isTrue == 'false'){
                    $this->index = str_replace('{ALERT_TYPE}', 'danger', $this->index);
                }else{
                    $this->index = str_replace('{ALERT_TYPE}', 'primary', $this->index);
                }
        }else{
            $this->index = str_replace('{ALERT}', '', $this->index);
        }

        $this->index = str_replace('{HEADER_HTML}', $contentHeader, $this->index);
        $this->index = str_replace('{CONTENT_HTML}', $contentTemplate, $this->index);
        echo $this->index;
    }

    protected function getContent(String $link){
        $contentTemplate = file_get_contents($link);
        return $contentTemplate;
    }
}