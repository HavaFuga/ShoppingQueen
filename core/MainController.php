<?php
/**
 * Created by PhpStorm.
 * User: hava
 * Date: 10.09.18
 * Time: 08:24
 */
namespace core;

include 'Controller/SuperController.php';



use application\ShoppingQueen\Controller\ShoppinglistController;
use application\ShoppingQueen\Model\Product;
use application\ShoppingQueen\Model\Shoppinglist;
use application\ShoppingQueen\View\ShoppinglistView;
use application\ShoppingQueen\Controller\ProductController;
use application\ShoppingQueen\View\ProductView;
use core\Access\Controller\UserController;
use core\Access\Model\User;
use core\Access\View\UserView;

include_once '/var/www/html/application/ShoppingQueen/Controller/ShoppinglistController.php';
include_once '/var/www/html/application/ShoppingQueen/View/ShoppinglistView.php';
include_once '/var/www/html/application/ShoppingQueen/Model/Shoppinglist.php';
include_once '/var/www/html/application/ShoppingQueen/Controller/ProductController.php';
include_once '/var/www/html/application/ShoppingQueen/View/ProductView.php';
include_once '/var/www/html/application/ShoppingQueen/Model/Product.php';
include_once '/var/www/html/core/Access/Controller/UserController.php';
include_once '/var/www/html/core/Access/View/UserView.php';
include_once '/var/www/html/core/Access/Model/User.php';



class MainController extends Controller\SuperController
{
    protected $shoppinglistController;
    protected $shoppinglist;
    protected $productController;
    protected $product;
    protected $userController;
    protected $user;

    function __construct()
    {
        $this->shoppinglistController = new ShoppinglistController();
        $this->shoppinglist = new Shoppinglist();
        $this->productController = new ProductController();
        $this->product = new Product();
        $this->userController = new UserController();
        $this->user = new User();
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
        }else{$action = 'overview';}

        if (isset($output['id'])) {
            $id = $output['id'];
        }else{ $id = 0; }

        if (isset($output['pid'])) {
            $pid = $output['pid'];
        }else{ $pid = 0; }

        if ($site == ''){
            $this->goToSite('/var/www/html/themes/home.html');
        }elseif ($site == 'shoppinglists'){
            $this->lookWhereShoppinglist($action, $id, $pid);
        }elseif ($site == 'products'){
            $this->lookWhereProducts($action, $id);
        }elseif ($site == 'user'){
            $this->lookWhereUsers($action);
        }
    }

    function lookWhereShoppinglist($action, $id, $pid){
        $shoppinglistController = $this->shoppinglistController;
        $shoppinglist = $this->shoppinglist;

        if ($action == 'detail'){
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $pid = $_POST['products'];
                if ($pid == ''){
                    header('Location: ?link=shoppinglists&act=detail&id=' . $id);
                }else{
                    $shoppinglist->add($id, $pid);
                    echo 'product successfully added';
                }
            }else{
                $shoppinglistController->detail($id);
            }

        }elseif ($action == 'create'){
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $shoppinglist->create();
            }else{
                $shoppinglistController->goToSite('/var/www/html/application/ShoppingQueen/View/create_view.html');
            }

        }elseif ($action == 'edit'){
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $shoppinglist->edit($id);
            }else{
                $shoppinglistController->editview($id);
            }

        }elseif ($action == 'delete'){
            $shoppinglist->delete($id);

        }elseif ($action == 'overview'){
            $shoppinglistController->overview();

        }elseif ($action == 'add'){
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $shoppinglist->add($id);
            }echo 'asdf';

        }elseif ($action == 'remove'){
            $shoppinglist->remove();
        }
    }

    function lookWhereProducts($action, $id){
        $productController = $this->productController;
        $product = $this->product;
        if ($action == 'detail'){
            $productController->detail($id);
        }elseif ($action == 'edit'){
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $product->edit($id);
            }else{
                $productController->editview($id);
            }
        }elseif ($action == 'delete'){
            $product->delete($id);
        }elseif ($action == 'add'){
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $product->add();
            }else{
                $productController->goToSite('/var/www/html/application/ShoppingQueen/View/create_product_view.html');
            }
        }
        else{
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


