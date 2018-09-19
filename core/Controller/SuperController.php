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

    protected $connection;

    function goToSite($link){
        $view = new \core\View\SuperView();
        $view->render($link);
    }

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
}