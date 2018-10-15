<?php
/**
 * Created by PhpStorm.
 * User: hava
 * Date: 10.09.18
 * Time: 11:02
 */
namespace core\Access\Controller;

/**
 * @ignore
 */
include_once '/var/www/html/core/Access/Model/User.php';
include_once __DIR__ . '/../../Controller/SuperController.php';

/**
 * Controller of User
 *
 * @copyright Comvation AG Thun
 * @author Hava Fuga <hf@comvation.com>
 * @package core\Access\Controller
 * @version 1.0.0
 */
class UserController extends \core\Controller\SuperController
{
    /**
     * @var \core\Access\Model\User
     */
    protected $user;

    /**
     * UserController constructor.
     */
    public function __construct()
    {

    }


    /**
     * @param $action
     * @param $id
     *
     * navigates to action from User
     */
    public function navigate($action)
    {
        $user = $this->user;

        if ($action == 'login'){
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->login();
                $this->goToSite('/var/www/html/themes/home.html', '', '');
            }else{
                $this->goToSite('/var/www/html/core/Access/View/login_view.html' ,'', '');
            }
        }elseif($action == 'logout'){
            $this->logout();
        }elseif ($action == 'register'){
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $user->register();
            }else{
                $this->goToSite('/var/www/html/core/Access/View/register_view.html' ,'', '');
            }
        }
    }

    /**
     * logs out the user
     */
    protected function logout(){
        session_start();
        if (isset($_SESSION['user']))
        {
            session_unset();
            session_destroy();
            header('Location: /');
        }
    }

    /**
     * logs in the user
     */
    protected function login(){
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
        $user = $this->user;

        //check Email and Input
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->goToSite('/var/www/html/core/Access/View/login_view.html', 'Invalid email format', 'false');
        }else{
            $this->checkInputLogin($email, $password);
        }
    }



    /**
     * Checks if user exists and if password is correct
     * @param String $email
     * @param String $password
     * @throws PDOException
     */
    public function checkInputLogin(String $email, String $password) {

        //get attributes form DB
        if (!$this->connectToDB()) {
            die('DB Connection error. UserController.php');
        } else {
            try {
                $conn = $this->connectToDB();
                $stmt = $conn->prepare('SELECT `id`, `name`, `email`, `password`, `isAdmin` FROM `User` WHERE `email` = "' . $email . '";');
                $stmt->execute();
                // set the resulting array to associative
                $result = $stmt->fetchAll();
                $conn = null;
            }
            catch (\PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
            }
        }
        //compare with attributes from DB
        $db_email = $result[0][2];
        $db_password = $result[0][3];
        $isAdmin = $result[0][4];
        if ($db_email != $email || $db_password != sha1($password)) {
            $this->goToSite('/var/www/html/core/Access/View/login_view.html' ,'E-Mail or PW not correct', 'false');
        } elseif ($isAdmin == 0) {
            $this->goToSite('/var/www/html/core/Access/View/login_view.html' , 'Sorry, you\'re not an admin. Please report it to the developer.', 'false');
        } else {
            //Starts Login Session
            session_start();
            $_SESSION['user'] = $result[0][0];
        }
    }

}


