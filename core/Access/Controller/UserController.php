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
        $this->user = new \core\Access\Model\User();
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
            $user->checkInputLogin($email, $password);
        }
    }


}


