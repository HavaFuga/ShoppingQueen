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
     * creates a connection to the DB
     * @return \PDO connection
     * @throws PDOException
     */
    protected function connectToDB() {

        if (!is_null($this->connection)) {
            return $this->connection;
        }

        $servername = 'db';
        $username = 'shoppingQueen';
        $password = 'Hallo123';
        $dbname = 'ShoppingQueen';

        try {
            $connection = new \PDO('mysql:host=' . $servername .';dbname=' . $dbname, $username , $password,
                array(\PDO::MYSQL_ATTR_MULTI_STATEMENTS  => false));
            // set the PDO error mode to exception
            $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            //echo 'Connected successfully';
            $this->connection = $connection;
            return $this->connection;
        }
        catch (\PDOException $e)
        {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    /**
     * goes to the site with link
     * @param String $link
     * @param String $alert_message
     * @param String $isTrue
     */
    public function goToSite(String $link, String $alert_message, String $isTrue) {
        //session_start();
        $view = new \core\View\SuperView(); //$this->superview;
        $view->render($link ,$alert_message, $isTrue);
    }

    /**
     * sends an alert
     * @param String $alert_message
     */
    public function sendAlert(String $alert_message) {
        $view = new \core\View\SuperView(); //$this->superview;
        $view->alert($alert_message);
    }
}