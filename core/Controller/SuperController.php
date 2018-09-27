<?php
/**
 * Created by PhpStorm.
 * User: hava
 * Date: 07.09.18
 * Time: 14:18
 */
namespace core\Controller;

include_once '/var/www/html/core/View/SuperView.php';

/**
 * Class SuperController
 *
 * This class creates a connection to the DB
 * sends an alert message
 * and renders to pages with a link
 * @package core\Controller
 */
class SuperController
{
    /**
     * @var \core\View\SuperView
     * @var \core\Model\SuperModel
     * @var connection
     */
    protected $superView;
    protected $superModel;
    protected $connection;

    /**
     * SuperController constructor.
     */
    public function __construct()
    {
        $this->superView = new \core\View\SuperView();
    }


    /**
     * sends an alert
     * @param String $alert_message
     */
    public function sendAlert(String $alert_message) {
        $view = new \core\View\SuperView(); //$this->superview;
        $view->alert($alert_message);
    }


    /**
     * goes to the site with link
     * @param String $link
     * @param String $alert_message
     * @param String $isTrue
     */
    public function goToSite(String $link, String $alert_message, String $isTrue) {
        $view = new \core\View\SuperView(); //$this->superview;
        $view->render($link ,$alert_message, $isTrue);
    }
}