<?php
/**
 * Created by PhpStorm.
 * User: hava
 * Date: 10.09.18
 * Time: 16:17
 */

namespace application\ShoppingQueen\Model;

include_once __DIR__ . '/../../../core/Model/SuperModel.php';
use core\Model\SuperModel;

class Shoppinglist extends SuperModel
{
    //gets one Shoppinglist from DB
    function getOne($id){
        if (!$this->connectToDB()){
            die('DB Connection error. Shoppinglist.php');
        }else {
            try{
                $conn = $this->connectToDB();
                $stmt = $conn->prepare('SELECT s.id, s.name, s.date, s.cost, u.name 
                                                  FROM Shoppinglist AS s, User AS u 
                                                  WHERE s.uid = u.id AND s.id = ' . $id .';');
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

    //gets all Shoppinglists from DB
    function getAll(){
        if (!$this->connectToDB()){
            die('DB Connection error. Shoppinglist.php');
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

    //creates a new Shoppinglist
    function create(){
        session_start();
        $name = $_POST['name'];
        $cost = $_POST['cost'];
        $id = $_SESSION['user'];
        $date = date("d.M.Y");

        //create new shoppinglist
        if (!$this->connectToDB()){
            die('DB Connection error. Shoppinglist.php');
        }else {
            try{
                $conn = $this->connectToDB();
                $stmt = 'INSERT INTO Shoppinglist(`name`, `date`, `cost`, `uid`)
                                      VALUES ("' . $name . '", "' . $date . '", "' . $cost .'", ' . $id . ');';
                $stmt = $conn->prepare($stmt);
                $stmt->execute();

                //echo 'List added successfully!';
            }
            catch(\PDOException $e){
                echo 'Connection failed: ' . $e->getMessage();
            }
        }

        header('Location: /application/ShoppingQueen/Controller/ShoppinglistController.php');
    }

    //updates a Shoppinglist
    function edit($id){
        $name = $_POST['name'];
        $cost = $_POST['cost'];

        //edit shoppinglist
        if (!$this->connectToDB()){
            die('DB Connection error. Shoppinglist.php');
        }else {
            try{
                $conn = $this->connectToDB();
                $stmt = 'UPDATE Shoppinglist SET name="' . $name . '", cost="' . $cost . '" WHERE id ='. $id .';';
                $conn->exec($stmt);
                //echo 'List edited successfully!';
            }
            catch(PDOException $e){
                echo 'Connection failed: ' . $e->getMessage();
            }
            $conn = null;
        }
        header('Location: ?link=shoppinglists&act=detail&id=' . $id);
    }


    //deletes the selected list
    function delete($id){
        if (!$this->connectToDB()){
            die('DB Connection error. Shoppinglist.php');
        }else {
            try{
                $conn = $this->connectToDB();
                $stmt = 'DELETE FROM Shoppinglist WHERE id ='. $id .';';
                $conn->exec($stmt);
                //echo 'List deleted successfully!';
            }
            catch(PDOException $e){
                echo 'Connection failed: ' . $e->getMessage();
            }
            $conn = null;
        }
        header('Location: ?link=shoppinglists');
    }

    //add product to the list
    function add($id, $pid){
        if (!$this->connectToDB()){
            die('DB Connection error. Shoppinglist.php');
        }else {
            try{
                $conn = $this->connectToDB();
                $stmt = 'INSERT INTO Shoppinglist_Product(`sid`, `pid`)
                                      VALUES (' . $id . ', ' . $pid . ');';
                $stmt = $conn->prepare($stmt);
                $stmt->execute();

                //echo 'Product added successfully!';
                header('Location: ?link=shoppinglists&act=detail&id=' . $id);
            }
            catch(\PDOException $e){
                echo 'Connection failed: ' . $e->getMessage();
            }
        }
    }

    //removes product from list
    function remove($id, $pid){
        if (!$this->connectToDB()){
            die('DB Connection error. Shoppinglist.php');
        }else {
            try{
                $conn = $this->connectToDB();
                $stmt = 'DELETE FROM Shoppinglist_Product 
                            WHERE `id` = ' . $id . ' AND `pid` = ' . $pid . ';';
                $stmt = $conn->prepare($stmt);
                $stmt->execute();

                //echo 'Product removed successfully!';
                header('Location: ?link=shoppinglists&act=detail&id=' . $id);
            }
            catch(\PDOException $e){
                echo 'Connection failed: ' . $e->getMessage();
            }
        }
    }


}