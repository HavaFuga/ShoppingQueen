<?php
/**
 * Created by PhpStorm.
 * User: hava
 * Date: 10.09.18
 * Time: 08:24
 */
namespace core;
use application\ShoppingQueen\Controller\ShoppinglistController;
use application\ShoppingQueen\View\ShoppinglistView;
use application\ShoppingQueen\Controller\ProductController;
use application\ShoppingQueen\View\ProductView;
use core\Access\Controller\UserController;
use core\Access\View\UserView;


include 'Controller/SuperController.php';
include_once '/var/www/html/application/ShoppingQueen/Controller/ShoppinglistController.php';
include_once '/var/www/html/application/ShoppingQueen/View/ShoppinglistView.php';
include_once '/var/www/html/application/ShoppingQueen/Controller/ProductController.php';
include_once '/var/www/html/application/ShoppingQueen/View/ProductView.php';
include_once '/var/www/html/core/Access/Controller/UserController.php';
include_once '/var/www/html/core/Access/View/UserView.php';

class MainController extends Controller\SuperController
{
    protected $shoppinglistController;
    protected $shoppinglistView;
    protected $productController;
    protected $productView;
    protected $userController;
    protected $userView;


    function __construct()
    {
        $this->shoppinglistController = new ShoppinglistController();
        $this->shoppinglistView = new ShoppinglistView();
        $this->productController = new ProductController();
        $this->productView = new ProductView();
        $this->userController = new UserController();
        $this->userView = new UserView();
    }


    function lookWhereToGo($link){
        parse_str($link, $output);
        //echo print_r($output, TRUE);

        if (isset($output['/?link'])) {
            $site = $output['/?link'];
        }else{
            $site = '';
        }

        //var_dump($site);die();
        if (isset($output['act'])) {
            $action = $output['act'];
        }else{
            $action = 'overview';
        }

        if (isset($output['id'])) {
            $id = $output['id'];
        }else{
            $id = 0;
        }

        if ($site == ''){
            $this->goToSite('/var/www/html/themes/home.html');
        }elseif ($site == 'shoppinglists'){
            $this->lookWhereShoppinglist($action, $id);
        }elseif ($site == 'products'){
            $this->lookWhereProducts($action);
        }elseif ($site == 'user'){
            $this->lookWhereUsers($action);
        }
    }

    function lookWhereShoppinglist($action, $id){
        $shoppinglistController = $this->shoppinglistController;

        if ($action == 'detail'){
            $shoppinglistController->detail($id);

        }elseif ($action == 'create'){
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $shoppinglistController->create();
            }else{
                $shoppinglistController->goToSite('/var/www/html/application/ShoppingQueen/View/create_view.html');
            }

        }elseif ($action == 'edit'){
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $name = $_POST['name'];
                $cost = $_POST['cost'];
                $shoppinglistController->edit($id ,$name, $cost);
            }else{
                $shoppinglistController->editview($id);
            }

        }elseif ($action == 'delete'){
            $shoppinglistController->delete($id);

        }elseif ($action == 'overview'){
            $shoppinglistController->overview();
        }
    }

    function lookWhereProducts($action){
        $productController = $this->productController;
        if ($action == 'detail'){
            $productController->detail();
        }else{
            $productController->overview();
        }
    }

    function lookWhereUsers()
    {

        $userController = $this->userController;
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
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $userController->register();
            }else{
                $userController->goToSite('/var/www/html/core/Access/View/register_view.html');
            }
        }
    }
}


