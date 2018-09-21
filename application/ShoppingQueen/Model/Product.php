<?php
/**
 * Created by PhpStorm.
 * User: hava
 * Date: 10.09.18
 * Time: 16:18
 */

namespace application\ShoppingQueen\Model;

include_once __DIR__ . '/../../../core/Model/SuperModel.php';
use core\Model\SuperModel;

class Product extends SuperModel
{
    //gets all Products from Shoppinglist with id
    function getAllFromShoppinglist($id){
        if (!$this->connectToDB()){
            die('DB Connection error. ProductController.php');
        }else {
            try{
                $conn = $this->connectToDB();
                $stmt = $conn->prepare('SELECT p.id, p.name FROM Product AS p, Shoppinglist_Product AS sp 
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

    //gets all Products
    function getAll(){
        if (!$this->connectToDB()){
            die('DB Connection error. ProductController.php');
        }else {
            try{
                $conn = $this->connectToDB();
                $stmt = $conn->prepare('SELECT id, name FROM Product;');
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

    //gets one product
    function getOne($id){
        if (!$this->connectToDB()){
            die('DB Connection error. ProductController.php');
        }else {
            try{
                $conn = $this->connectToDB();
                $stmt = $conn->prepare('SELECT name FROM Product WHERE id =' . $id . ';');
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


    //get all products except the already selected ones
    function getAllOthers($id){
        if (!$this->connectToDB()){
            die('DB Connection error. ProductController.php');
        }else {
            try{
                $conn = $this->connectToDB();
                $stmt = $conn->prepare('SELECT `id`, `name` FROM `Product` WHERE `id` NOT IN 
                                                    (SELECT pid FROM Shoppinglist_Product WHERE sid = ' . $id . ');');
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

    //edits the product
    function edit($id){
        $name = $_POST['name'];

        //update product
        if (!$this->connectToDB()){
            die('DB Connection error. ShoppinglistController.php');
        }else {
            try{
                $conn = $this->connectToDB();
                $stmt = 'UPDATE Shoppinglist SET name="' . $name . '" WHERE id ='. $id .';';
                $conn->exec($stmt);
                //echo 'List edited successfully!';
            }
            catch(PDOException $e){
                echo 'Connection failed: ' . $e->getMessage();
            }
            $conn = null;
        }
        header('Location: ?link=products');
    }

    //deletes the selected product
    function delete($id){
        if (!$this->connectToDB()){
            die('DB Connection error. ShoppinglistController.php');
        }else {
            try{
                $conn = $this->connectToDB();
                $stmt = 'DELETE FROM Product WHERE id ='. $id .';';
                $conn->exec($stmt);
                //echo 'List deleted successfully!';
            }
            catch(PDOException $e){
                echo 'Connection failed: ' . $e->getMessage();
            }
            $conn = null;
        }
        header('Location: ?link=products');
    }


    //adds a new product
    function add(){
        $name = $_POST['name'];

        //create new product
        if (!$this->connectToDB()){
            die('DB Connection error. ShoppinglistController.php');
        }else {
            try{
                $conn = $this->connectToDB();
                $stmt = 'INSERT INTO Product(`name`)
                                      VALUES ("' . $name . '");';
                $stmt = $conn->prepare($stmt);
                $stmt->execute();

                //echo 'Product added successfully!';
            }
            catch(\PDOException $e){
                echo 'Connection failed: ' . $e->getMessage();
            }
        }

        header('Location: /?link=products');
    }

}