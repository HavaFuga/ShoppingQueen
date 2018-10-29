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
    protected $id;
    protected $name;

    function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
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
    }

}