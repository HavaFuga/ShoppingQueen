<?php
/**
 * Created by PhpStorm.
 * User: hava
 * Date: 12.09.18
 * Time: 15:41
 */

namespace core\Access\Controller;

include_once $_SERVER['DOCUMENT_ROOT'] . '/core/Controller/SuperController.php';
namespace application\ShoppingQueen\Controller;


class ProductController extends \core\Controller\SuperController
{
    function getAll($id){
        if (!$this->connectToDB()){
            die('DB Connection error. ProductController.php');
        }else {
            try{
                $conn = $this->connectToDB();
                $stmt = $conn->prepare('SELECT p.name FROM Product AS p, Shoppinglist_Product AS sp 
                                                  WHERE sp.sid = ' . $id . ' AND p.id = sp.pid;');
                $stmt->execute();

                // set the resulting array to associative
                $products = $stmt->fetchAll();
                $conn = null;
            }
            catch(PDOException $e){
                echo 'Connection failed: ' . $e->getMessage();
            }
            return $products;
        }
    }

}