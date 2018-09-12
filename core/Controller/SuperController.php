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
$view = new \core\View\SuperView();
$link = string;

class SuperController
{

    function goToSite($link){
            header('LOCATION: ' . $link);
    }

    function connectToDB(){
        $servername = 'db';
        $username = 'shoppingQueen';
        $password = 'Hallo123';
        $dbname = 'ShoppingQueen';


        try {
            $conn = new PDO('mysql:host=' . $servername .';dbname=' . $dbname, $username , $password,
                array("[PDO::MYSQL_ATTR_MULTI_STATEMENTS => false]"));
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo 'Connected successfully';
            $connection = $conn;
            return $conn;
        }
        catch(PDOException $e)
        {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }
}