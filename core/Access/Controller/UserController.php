<?php
/**
 * Created by PhpStorm.
 * User: hava
 * Date: 10.09.18
 * Time: 11:02
 */
namespace application\ShoppingQueen\Controller;

include_once __DIR__ . '/../../Controller/SuperController.php';
include_once __DIR__ . '/../Model/User.php';

class UserController extends \core\Controller\SuperController
{
    //Check if User exists and if password is correct
    function checkInput($input_email, $input_password){
        $email = $input_email;
        $password = $input_password;

        //get attributes form DB
        if (!$this->connectToDB()){
            die('DB Connection error. UserController.php');
        }else {
            try{
                $conn = $this->connectToDB();
                $stmt = $conn->prepare('SELECT id, name, email, password FROM User WHERE email = "' . $email . '";');
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

        if ($db_email != $email || $db_password != $password){
            echo ('E-Mail or PW not correct');
        }else{
            //Starts Login Session
            session_start();
            $_SESSION['user'] = $result[0][0];
            $this->goToSite('/var/www/html/themes/home.html');
        }
    }


    function logout(){
        if (isset($_SESSION))
        {
            session_destroy();
        }
        $this->goToSite('/var/www/html/themes/home.html');
    }

    function login(){
        $email = $_POST['email'];
        $password = $_POST['password'];

        //check Email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo ('Invalid email format');
        }else{
            $this->checkInput($email, $password);
        }
    }
}

$userController = new UserController();
$action = $_GET['act'];
if ($action == 'login'){
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userController->login();
    }else{
        $userController->goToSite('/var/www/html/core/Access/View/login_view.html');
    }
}elseif($action == 'logout'){
    $userController->logout();
}elseif ($action == 'register'){
    $userController->goToSite('/var/www/html/core/Access/View/register_view.html');
}

