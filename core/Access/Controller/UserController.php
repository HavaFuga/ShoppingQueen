<?php
/**
 * Created by PhpStorm.
 * User: hava
 * Date: 10.09.18
 * Time: 11:02
 */
namespace core\Access\Controller;

use core\Access\Model\User;


include_once '/var/www/html/core/Access/Model/User.php';
include_once __DIR__ . '/../../Controller/SuperController.php';


class UserController extends \core\Controller\SuperController
{
    protected $user;

    function __construct()
    {
        $this->user = new User();
    }


    //navigates to action from User
    function navigate()
    {
        $userController = $this->userController;
        $user = $this->user;
        $action = htmlspecialchars($_GET['act']);
        if ($action == 'login'){
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $userController->login();
            }else{
                $userController->goToSite('/var/www/html/core/Access/View/login_view.html' ,'', '');
            }
        }elseif($action == 'logout'){
            $userController->logout();
        }elseif ($action == 'register'){
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $user->register();
            }else{
                $userController->goToSite('/var/www/html/core/Access/View/register_view.html' ,'', '');
            }
        }
    }

    //logs out the user
    function logout(){
        session_start();
        if (isset($_SESSION['user']))
        {
            session_unset();
            session_destroy();
            header('Location: /');
        }
    }

    //logs in the user
    function login(){
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
        $user = $this->user;

        //check Email and Input
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->goToSite('/var/www/html/core/Access/View/login_view.html', 'Invalid email format', false);
        }else{
            $user->checkInputLogin($email, $password);
        }
    }


}


