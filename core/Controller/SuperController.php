<?php
/**
 * Created by PhpStorm.
 * User: hava
 * Date: 07.09.18
 * Time: 14:18
 */
namespace core\Controller;

include_once '/var/www/html/core/View/SuperView.php';

class SuperController
{
    protected $superView;
    protected $superModel;

    protected $connection;

    public function __construct()
    {
        $this->superView = new \core\View\SuperView();
    }

    //connect to DB
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
                array("[PDO::MYSQL_ATTR_MULTI_STATEMENTS => false]"));
            // set the PDO error mode to exception
            $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            //echo 'Connected successfully';
            $this->connection = $connection;
            return $this->connection;
        }
        catch(\PDOException $e)
        {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    //sends an alert
    public function sendAlert(String $alert_message){
        $view = new \core\View\SuperView(); //$this->superview;
        $view->alert($alert_message);
    }

    //go to site with link
    public function goToSite(String $link, String $alert_message, String $isTrue){
        $view = new \core\View\SuperView(); //$this->superview;
        $view->render($link ,$alert_message, $isTrue);
    }
}