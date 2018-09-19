<?php
/**
 * Created by PhpStorm.
 * User: hava
 * Date: 10.09.18
 * Time: 11:02
 */
namespace core\Access\Controller;

include_once __DIR__ . '/../../Controller/SuperController.php';
include_once __DIR__ . '/../Model/User.php';

class UserController extends \core\Controller\SuperController
{
    //Check if User exists and if password is correct
    function checkInputLogin($input_email, $input_password){
        $email = $input_email;
        $password = $input_password;

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

        if ($db_email != $email || $db_password != $password){
            $this->goToSite('/var/www/html/core/Access/View/login_view.html');
            echo ('E-Mail or PW not correct');
        }elseif ($isAdmin == 0){
            $this->goToSite('/var/www/html/core/Access/View/login_view.html');
            echo 'Sorry, you\'re not an admin. Please report it to the developer.';
        }else{
            //Starts Login Session
            session_start();
            $_SESSION['user'] = $result[0][0];
            $this->goToSite('/var/www/html/themes/home.html');
        }
    }


    function checkInputRegister($input_name, $input_email, $input_password, $input_password2){
        $name = $input_name;
        $email = $input_email;
        $password = $input_password;
        $password2 = $input_password2;

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
            }else{
                $isEmailTaken = true;
            }
        }

        //checks if passwords are the same
        if ($password != $password2){
            echo ('Passwords are not the same!');
            return false;
        }elseif ($isEmailTaken == false){
            echo 'Sorry, this E-Mail is already taken!';
            return false;
        }else{
            return true;
        }
    }


    function logout(){
        session_start();
        if (isset($_SESSION['user']))
        {
            session_unset();
            session_destroy();
            $this->goToSite('/var/www/html/themes/home.html');
        }
    }

    function login(){
        $email = $_POST['email'];
        $password = $_POST['password'];

        //check Email and Input
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo ('Invalid email format');
        }else{
            $this->checkInputLogin($email, $password);
        }
    }

    function register(){
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $password2 = $_POST['password2'];

        //check Email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo ('Invalid E-Mail format!');
        }else{
            if ($this->checkInputRegister($name, $email, $password, $password2) == true){
                //registers new user
                if (!$this->connectToDB()){
                    die('DB Connection error. UserController.php');
                }else {
                    try{
                        $conn = $this->connectToDB();
                        $stmt = 'INSERT INTO User(name, email, password)
                                      VALUES ("' . $name . '", "' . $email .'", "' . $password . '");';
                        $conn->exec($stmt);
                        echo 'Registration completed successfully!';
                    }
                    catch(PDOException $e){
                        echo 'Connection failed: ' . $e->getMessage();
                    }
                    $conn = null;
                }
            }
        }
        $this->goToSite('/var/www/html/core/Access/View/register_view.html');
    }
}


