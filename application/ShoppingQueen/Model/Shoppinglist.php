<?php
/**
 * Created by PhpStorm.
 * User: hava
 * Date: 10.09.18
 * Time: 16:17
 */

namespace application\ShoppingQueen\Model;

include_once __DIR__ . '/../../../core/Model/SuperModel.php';

/**
 * Class Shoppinglist
 *
 * Has all statements for the table 'Shoppinlist' in the table
 * @package application\ShoppingQueen\Model
 */
class Shoppinglist extends \core\Model\SuperModel
{

    /**
     * gets one shoppinglist from DB
     * @param int $id
     * @return array from shoppinglist
     * @throws PDOException
     */
    public function getOne(int $id) {
        if (!$this->connectToDB()) {
            die('DB Connection error. Shoppinglist.php');
        } else {
            try {
                $conn = $this->connectToDB();
                $stmt = $conn->prepare('SELECT s.`id`, s.`name`, s.`date`, s.`cost`, u.`name` 
                                                  FROM `Shoppinglist` AS s, `User` AS u 
                                                  WHERE s.`uid` = u.`id` AND s.`id` = ' . $id .';');
                $stmt->execute();

                // set the resulting array to associative
                $result = $stmt->fetch();
                $conn = null;
            }
            catch(\PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
            }
            return $result;
        }
    }


    /**
     * gets all shoppinglists from DB
     * @return array with all shoppinglists
     * @throws PDOException
     */
    public function getAll() {
        if (!$this->connectToDB()) {
            die('DB Connection error. Shoppinglist.php');
        } else {
            try {
                $conn = $this->connectToDB();
                $stmt = $conn->prepare('SELECT s.`id`, s.`name`, s.`date`, s.`cost`, u.`name` FROM `Shoppinglist` AS s, `User` AS u 
                                                  WHERE s.`uid` = u.`id`
                                                  ORDER BY s.`date` ASC;');
                $stmt->execute();

                // set the resulting array to associative
                $result = $stmt->fetchAll();
                $conn = null;
            }
            catch(\PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
            }
            return $result;
        }
    }


    /**
     * creates a new shoppinglist
     * @throws PDOException
     */
    public function create() {
        session_start();
        $name = htmlspecialchars($_POST['name']);
        $cost = htmlspecialchars($_POST['cost']);
        $id = $_SESSION['user'];
        $date = date("Y-m-d");

        if (!$this->connectToDB()) {
            die('DB Connection error. Shoppinglist.php');
        } else {
            try {
                $conn = $this->connectToDB();
                $stmt = 'INSERT INTO `Shoppinglist`(`name`, `date`, `cost`, `uid`)
                                      VALUES ("' . $name . '", "' . $date . '", "' . $cost .'", ' . $id . ');';
                $stmt = $conn->prepare($stmt);
                $stmt->execute();

                //echo 'List added successfully!';
            }
            catch(\PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
            }
        }
        header('Location: ?link=shoppinglists');
    }


    /**
     * updates a shoppinglist
     * @param int $id
     * @throws PDOException
     */
    public function edit(int $id) {
        $name = htmlspecialchars($_POST['name']);
        $cost = ($_POST['cost']);

        if (!$this->connectToDB()) {
            die('DB Connection error. Shoppinglist.php');
        } else {
            try {
                $conn = $this->connectToDB();
                $stmt = 'UPDATE `Shoppinglist` SET `name`="' . $name . '", `cost`="' . $cost . '" WHERE `id` ='. $id .';';
                $conn->exec($stmt);
            }
            catch(\PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
            }
            $conn = null;
        }
        header('Location: ?link=shoppinglists&act=detail&id=' . $id);
    }


    /**
     * deletes a shoppinglist
     * @param int $id
     * @throws PDOException
     */
    public function delete(int $id) {
        if (!$this->connectToDB()) {
            die('DB Connection error. Shoppinglist.php');
        } else {
            try {
                $conn = $this->connectToDB();
                $stmt = 'DELETE FROM `Shoppinglist` WHERE `id` ='. $id .';';
                $conn->exec($stmt);
                //echo 'List deleted successfully!';
            }
            catch(\PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
            }
            $conn = null;
        }
        header('Location: ?link=shoppinglists');
    }


    /**
     * adds a products to a shoppinglist
     * @param int $id
     * @param int $pid
     * @throws PDOException
     */
    public function add(int $id, int $pid) {
        if (!$this->connectToDB()) {
            die('DB Connection error. Shoppinglist.php');
        } else {
            try {
                $conn = $this->connectToDB();
                $stmt = 'INSERT INTO `Shoppinglist_Product`(`sid`, `pid`)
                                      VALUES (' . $id . ', ' . $pid . ');';
                $stmt = $conn->prepare($stmt);
                $stmt->execute();
            }
            catch(\PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
            }
        }
    }


    /**
     * removes product from shoppinglist
     * @param int $id
     * @param int $pid
     * @throws PDOException
     */
    public function remove(int $id, int $pid) {
        if (!$this->connectToDB()) {
            die('DB Connection error. Shoppinglist.php');
        } else {
            try {
                $conn = $this->connectToDB();
                $stmt = 'DELETE FROM `Shoppinglist_Product` 
                            WHERE `sid` = ' . $id . ' AND `pid` = ' . $pid . ';';
                $stmt = $conn->prepare($stmt);
                $stmt->execute();

                //echo 'Product removed successfully!';
                header('Location: ?link=shoppinglists&act=edit&id=' . $id);
            }
            catch(\PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
            }
        }
    }


}