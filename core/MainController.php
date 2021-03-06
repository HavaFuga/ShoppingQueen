<?php
/**
 * Created by PhpStorm.
 * User: hava
 * Date: 10.09.18
 * Time: 08:24
 */
namespace core;

/**
 * @ignore
 */
include 'Controller/SuperController.php';
include_once '/var/www/html/application/ShoppingQueen/Controller/ShoppinglistController.php';
include_once '/var/www/html/application/ShoppingQueen/Controller/ProductController.php';
include_once '/var/www/html/core/Access/Controller/UserController.php';

/**
 * The Main Class looks where the link leads
 *
 * @copyright Comvation AG Thun
 * @author Hava Fuga <hf@comvation.com>
 * @package core
 * @version 1.0.0
 */
class MainController extends Controller\SuperController
{
    protected $shoppinglistController;
    protected $shoppinglist;
    protected $productController;
    protected $product;
    protected $userController;
    protected $user;

    /**
     * MainController constructor.
     */
    public function __construct()
    {
        $this->shoppinglistController = new \application\ShoppingQueen\Controller\ShoppinglistController();
        $this->productController = new \application\ShoppingQueen\Controller\ProductController();
        $this->userController = new \core\Access\Controller\UserController();
    }


    /**
     * @param String $link
     * gets the param 'link' and navigates to the site
     */
    public function lookWhereToGo(String $link) {
        parse_str($link, $output);

        $site = '';
        $action = 'overview';
        $id = 0;
        $pid = 0;

        $shoppinglistController = $this->shoppinglistController;
        $productController = $this->productController;
        $userController = $this->userController;

        /*$params = array('/?link' => 'site', 'act' => 'action', 'id' => 'id', 'pid' => 'pid');
        foreach ($params as $param => $param_value) {
            if (isset($output[$param])) {
                $param_value = $output[$param];
            }
        }*/

        if (isset($output['/?link'])) {
            $site = $output['/?link'];
        }
        if (isset($output['act'])) {
            $action = $output['act'];
        }
        if (isset($output['id'])) {
            $id = $output['id'];
        }
        if (isset($output['pid'])) {
            $pid = $output['pid'];
        }

        switch ($site) {
            case '':
                session_start();
                $this->goToSite('/var/www/html/themes/home.html', '', '');
                break;
            case 'shoppinglists':
                $shoppinglistController->navigate($action, $id, $pid);
                break;
            case 'products':
                $productController->navigate($action, $id);
                break;
            case 'user':
                $userController->navigate($action);
                break;
        }
    }


}


