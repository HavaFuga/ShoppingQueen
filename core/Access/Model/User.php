<?php
/**
 * Created by PhpStorm.
 * User: hava
 * Date: 10.09.18
 * Time: 11:43
 */

namespace core\Access\Model;

include_once '/var/www/html/core/Access/Controller/UserController.php';
include_once __DIR__ . '/../../../core/Model/SuperModel.php';
use core\Model\SuperModel;
use core\Access\Controller\UserController;

class User extends SuperModel
{
    protected $userController;

    //Check if User exists and if password is correct
    function checkInputLogin($email, $password){
        $userController = new UserController();

        //get attributes form DB
        if (!$this->connectToDB()){
            die('DB Connection error. UserController.php');
        }else {
            try{
                $conn = $this->connectToDB();
                $stmt = $conn->prepare('SELECT id, name, email, password, isAdmin FROM User WHERE email = "' . $email . '";');
                $stmt->execute();
                // set the resulting array to associative
                $result = $stmt->fetchAll();
                $conn = null;
            }
            catch(PDOException $e){
                echo 'Connection failed: ' . $e->getMessage();
            }
        }
        //compare with attributes from DB
        $db_email = $result[0][2];
        $db_password = $result[0][3];
        $isAdmin = $result[0][4];
        if ($db_email != $email || $db_password != sha1($password)){
            $userController->goToSite('/var/www/html/core/Access/View/login_view.html' ,'E-Mail or PW not correct', false);
        }elseif ($isAdmin == 0){
            $userController->goToSite('/var/www/html/core/Access/View/login_view.html' , 'Sorry, you\'re not an admin. Please report it to the developer.', false);
        }else{
            //Starts Login Session
            session_start();
            $_SESSION['user'] = $result[0][0];
            header('Location: /');
        }
    }

    //Check if email exists, id passwords are the same
    function checkInputRegister($email, $password, $password2){
        $userController = new UserController();

        //get Emails form DB
        if (!$this->connectToDB()){
            die('DB Connection error. UserController.php');
        }else {
            try{
                $conn = $this->connectToDB();
                $stmt = $conn->prepare('SELECT email FROM User;');
                $stmt->execute();

                // set the resulting array to associative
                $result = $stmt->fetchAll();
                $conn = null;
            }
            catch(PDOException $e){
                echo 'Connection failed: ' . $e->getMessage();
            }
        }

        //compares email with attributes from DB
        foreach ($result as $db_email){
            if ($email == $db_email[0]){
                $isEmailTaken = false;
                break;
            }else {
                $isEmailTaken = true;
            }
        }

        //checks if passwords are the same
        if ($isEmailTaken == false){
            $userController->goToSite('/var/www/html/core/Access/View/register_view.html', 'Sorry, this E-Mail is already taken!', false);
            return false;

        }elseif ($password != $password2){
            $userController->goToSite('/var/www/html/core/Access/View/register_view.html', 'Passwords are not the same!', false);
            return false;
        }else{
            sha1($password);
            return true;
        }
    }

    //register a new user
    function register(){
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $password = $_POST['password'];
        $password2 = $_POST['password2'];
        $userController = new UserController();

        //check Email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $userController->goToSite('/var/www/html/core/Access/View/register_view.html','Invalid email format', false);
        }else{
            if ($this->checkInputRegister($email, $password, $password2) == true){
                //registers new user
                if (!$this->connectToDB()){
                    die('DB Connection error. UserController.php');
                }else {
                    try{
                        $conn = $this->connectToDB();
                        $stmt = 'INSERT INTO User(name, email, password)
                                      VALUES ("' . $name . '", "' . $email .'", "' . $password . '");';
                        $conn->exec($stmt);
                        $userController->goToSite('/var/www/html/core/Access/View/register_view.html', 'Registration completed successfully!' , true);
                    }
                    catch(PDOException $e){
                        echo 'Connection failed: ' . $e->getMessage();
                    }
                    $conn = null;
                }
            }
        }

    }
}