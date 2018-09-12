<?php
/**
 * Created by PhpStorm.
 * User: hava
 * Date: 10.09.18
 * Time: 16:44
 */
namespace application\ShoppingQueen\Controller;
include_once $_SERVER['DOCUMENT_ROOT'] . '/core/Controller/SuperController.php';

class ShoppinglistController extends \core\Controller\SuperController
{
    function getAll(){
        if (!$this->connectToDB()){
            die('DB Connection error. ShoppinglistController.php');
        }else {
            try{
                $conn = $this->connectToDB();
                $stmt = $conn->prepare('SELECT s.id, s.name, s.date, s.cost, u.name FROM Shoppinglist AS s, User AS u WHERE s.uid = u.id;');
                $stmt->execute();

                // set the resulting array to associative
                $result = $stmt->fetchAll();
                $conn = null;
            }
            catch(PDOException $e){
                echo 'Connection failed: ' . $e->getMessage();
            }
            return $result;
        }
    }

    function getOne($id){
        if (!$this->connectToDB()){
            die('DB Connection error. ShoppinglistController.php');
        }else {
            try{
                $conn = $this->connectToDB();
                $stmt = $conn->prepare('SELECT s.id, s.name, s.date, s.cost, u.name 
                                                  FROM Shoppinglist AS s, User AS u 
                                                  WHERE s.uid = u.id AND s.id =' . $id .';');
                $stmt->execute();

                // set the resulting array to associative
                $result = $stmt->fetch();
                $conn = null;
            }
            catch(PDOException $e){
                echo 'Connection failed: ' . $e->getMessage();
            }
            return $result;
        }
    }

}