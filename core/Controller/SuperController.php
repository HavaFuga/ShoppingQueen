<?php
/**
 * Created by PhpStorm.
 * User: hava
 * Date: 07.09.18
 * Time: 14:18
 */
namespace core\Controller;

use PDO;

use core\Model\SuperModel;
use core\View\SuperView;
include_once '/var/www/html/core/View/SuperView.php';

class SuperController
{
    protected $superView;
    protected $superModel;

    protected $connection;

    function __construct()
    {
        $this->superView = new SuperView();
    }

    //connect to DB
    function connectToDB() {
        if (!is_null($this->connection)) {
            return $this->connection;
        }
        $servername = 'db';
        $username = 'shoppingQueen';
        $password = 'Hallo123';
        $dbname = 'ShoppingQueen';
        try {
            $connection = new PDO('mysql:host=' . $servername .';dbname=' . $dbname, $username , $password,
                array("[PDO::MYSQL_ATTR_MULTI_STATEMENTS => false]"));
            // set the PDO error mode to exception
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo 'Connected successfully';
            $this->connection = $connection;
            return $this->connection;
        }
        catch(PDOException $e)
        {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    //sends an alert
    function sendAlert($alert_message){
        $view = new SuperView();
        $view->alert($alert_message);
    }

    //go to site with link
    function goToSite($link, $alert_message, $isTrue){
        $view = new SuperView();
        $view->render($link ,$alert_message, $isTrue);
    }
}