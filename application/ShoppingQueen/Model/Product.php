<?php
/**
 * Created by PhpStorm.
 * User: hava
 * Date: 10.09.18
 * Time: 16:18
 */

namespace application\ShoppingQueen\Model;

include_once __DIR__ . '/../../../core/Model/SuperModel.php';

/**
 * Class Product
 *
 * This class has all statements for the table 'Product' in the DB
 * @package application\ShoppingQueen\Model
 */
class Product extends \core\Model\SuperModel
{
    /**
     * gets all products from shoppinglist with id
     * @param int $id
     * @return dimensional array with all products from a shoppinglist
     * @throws PDOException
     */
    public function getAllFromShoppinglist(int $id){
        if (!$this->connectToDB()){
            die('DB Connection error. ProductController.php');
        } else {
            try{
                $conn = $this->connectToDB();
                $stmt = $conn->prepare('SELECT p.`id`, p.`name` FROM `Product` AS p, `Shoppinglist_Product` AS sp 
                                                  WHERE sp.`sid` = ' . $id . ' AND p.`id` = sp.`pid`
                                                  ORDER BY p.`name` ASC;');
                $stmt->execute();

                // set the resulting array to associative
                $products = $stmt->fetchAll();
                $conn = null;
            }
            catch(\PDOException $e){
                echo 'Connection failed: ' . $e->getMessage();
            }
            return $products;
        }
    }


    /**
     * gets all products
     * @return dimensional array with all products
     * @throws PDOException
     */
    public function getAll(){
        if (!$this->connectToDB()){
            die('DB Connection error. ProductController.php');
        } else {
            try{
                $conn = $this->connectToDB();
                $stmt = $conn->prepare('SELECT `id`, `name` FROM `Product`
                                                  ORDER BY `name` ASC;');
                $stmt->execute();

                // set the resulting array to associative
                $products = $stmt->fetchAll();
                $conn = null;
            }
            catch(\PDOException $e){
                echo 'Connection failed: ' . $e->getMessage();
            }
            return $products;
        }
    }


    /**
     * gets one product
     * @param int $id
     * @return array with one product
     * @throws PDOException
     */
    public function getOne(int $id){
        if (!$this->connectToDB()){
            die('DB Connection error. ProductController.php');
        } else {
            try{
                $conn = $this->connectToDB();
                $stmt = $conn->prepare('SELECT `name` FROM `Product` WHERE `id` =' . $id . ';');
                $stmt->execute();

                // set the resulting array to associative
                $products = $stmt->fetchAll();
                $conn = null;
            }
            catch(\PDOException $e){
                echo 'Connection failed: ' . $e->getMessage();
            }
            return $products;
        }
    }


    /**
     * gets all products except the already selected ones
     * @param int $id
     * @return dimensional array with all products that are not in the shoppinlist
     * @throws PDOException
     */
    public function getAllOthers(int $id){
        if (!$this->connectToDB()){
            die('DB Connection error. ProductController.php');
        } else {
            try{
                $conn = $this->connectToDB();
                $stmt = $conn->prepare('SELECT `id`, `name` FROM `Product` WHERE `id` NOT IN 
                                                    (SELECT `pid` FROM `Shoppinglist_Product` WHERE `sid` = ' . $id . ')
                                                    ORDER BY `name` ASC;');
                $stmt->execute();

                // set the resulting array to associative
                $products = $stmt->fetchAll();
                $conn = null;
            }
            catch(\PDOException $e){
                echo 'Connection failed: ' . $e->getMessage();
            }
            return $products;
        }
    }


    /**
     * edits the product
     * @param int $id
     * @throws PDOException
     */
    public function edit(int $id){
        $name = $_POST['name'];

        //update product
        if (!$this->connectToDB()){
            die('DB Connection error. ShoppinglistController.php');
        } else {
            try{
                $conn = $this->connectToDB();
                $stmt = 'UPDATE `Product` SET `name`="' . $name . '" WHERE `id` ='. $id .';';
                $conn->exec($stmt);
                //echo 'List edited successfully!';
            }
            catch(\PDOException $e){
                echo 'Connection failed: ' . $e->getMessage();
            }
            $conn = null;
        }
        header('Location: ?link=products');
    }


    /**
     * deletes the selected product
     * @param int $id
     * @throws PDOException
     *
     */
    public function delete(int $id){
        if (!$this->connectToDB()){
            die('DB Connection error. ShoppinglistController.php');
        } else {
            try{
                $conn = $this->connectToDB();
                $stmt = 'DELETE FROM `Product` WHERE `id` ='. $id .';';
                $conn->exec($stmt);
                //echo 'List deleted successfully!';
            }
            catch(\PDOException $e){
                echo 'Connection failed: ' . $e->getMessage();
            }
            $conn = null;
        }
        header('Location: ?link=products');
    }


    /**
     * adds a new product
     * @throws PDOException
     */
    public function add(){
        $name = $_POST['name'];

        if (!$this->connectToDB()){
            die('DB Connection error. ShoppinglistController.php');
        } else {
            try{
                $conn = $this->connectToDB();
                $stmt = 'INSERT INTO `Product`(`name`)
                                      VALUES ("' . $name . '");';
                $stmt = $conn->prepare($stmt);
                $stmt->execute();
            }
            catch(\PDOException $e){
                echo 'Connection failed: ' . $e->getMessage();
            }
        }

        header('Location: /?link=products');
    }

}