<?php
/**
 * Created by PhpStorm.
 * User: hava
 * Date: 12.09.18
 * Time: 15:41
 */

namespace application\ShoppingQueen\Controller;


use application\ShoppingQueen\View\ProductView;

include_once __DIR__ . '/../../../core/Controller/SuperController.php';
include_once __DIR__ . '/../View/ProductView.php';
include_once __DIR__ . '/../Model/Product.php';

/**
 * Class ProductController
 *
 * This Class navigates to method with the param 'act'
 * and controlls all the actions that a product can do
 *
 * @package application\ShoppingQueen\Controller
 */
class ProductController extends \core\Controller\SuperController
{
    /**
     * @var \application\ShoppingQueen\View\ProductView
     * @var \application\ShoppingQueen\Model\Product
     * @var overview
     */
    protected $productView;
    protected $product;
    protected $overview;


    function __construct()
    {
        $this->productView = new \application\ShoppingQueen\View\ProductView();
        //$this->product = new \application\ShoppingQueen\Model\Product();
    }


    /**
     * navigates to action from product
     * @param string $action
     * @param int $id
     */
    function navigate(string $action, int $id){

        if ($id != 0){
            $p = $this->getOne($id);
            $product = new \application\ShoppingQueen\Model\Product($p->id, $p->name);
            $productView = $this->productView;
        }
        session_start();

        switch ($action){
            case ('detail'):
                $this->detail($id);
                break;

            case ('edit'):
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $product->edit($id);
                }else{
                    $product = $this->getOne($id);
                    $productView->editview($product);
                }
                break;

            case ('delete'):
                $product->delete($id);
                break;

            case ('add'):
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $product->add();
                    $this->overview();
                } else {
                    $this->goToSite('/var/www/html/application/ShoppingQueen/View/create_product_view.html', '', '');
                }
                break;

            case ('overview'):
                $this->overview();
                break;
        }
    }


    /**
     * creates overview with products
     */
    function overview(){
        $productView = $this->productView;
        $allProducts = $this->getAll();
        //$viewAll = $productView->viewAll($allProducts);
        $productView->printAll($allProducts);
    }


    /**
     * gets one product
     * @param int $id
     * @return array with one product
     * @throws PDOException
     */
    public function getOne(int $id){
        if (!$this->connectToDB()){
            die('DB Connection error. ProductController.php');
        } else {
            try{
                $conn = $this->connectToDB();
                $stmt = $conn->prepare('SELECT `id`, `name` FROM `Product` WHERE `id` =' . $id . ';');
                $stmt->execute();

                // set the resulting array to associative
                $product = $stmt->fetchObject();
                $conn = null;
            }
            catch(\PDOException $e){
                echo 'Connection failed: ' . $e->getMessage();
            }
            return $product;
        }
    }


    /**
     * gets all products from shoppinglist with id
     * @param int $id
     * @return dimensional array with all products from a shoppinglist
     * @throws PDOException
     */
    public function getAllFromShoppinglist(int $id){
        if (!$this->connectToDB()){
            die('DB Connection error. ProductController.php');
        } else {
            try{
                $conn = $this->connectToDB();
                $stmt = $conn->prepare('SELECT p.`id`, p.`name` FROM `Product` AS p, `Shoppinglist_Product` AS sp 
                                                  WHERE sp.`sid` = ' . $id . ' AND p.`id` = sp.`pid`
                                                  ORDER BY p.`name` ASC;');
                $stmt->execute();

                // set the resulting array to associative
                $products = $stmt->fetchAll(\PDO::FETCH_OBJ);
                $conn = null;
            }
            catch(\PDOException $e){
                echo 'Connection failed: ' . $e->getMessage();
            }
            return $products;
        }
    }


    /**
     * gets all products
     * @return dimensional array with all products
     * @throws PDOException
     */
    public function getAll(){
        if (!$this->connectToDB()){
            die('DB Connection error. ProductController.php');
        } else {
            try{
                $conn = $this->connectToDB();
                $stmt = $conn->prepare('SELECT `id`, `name` FROM `Product`
                                                  ORDER BY `name` ASC;');
                $stmt->execute();

                // set the resulting array to associative
                $products = $stmt->fetchAll(\PDO::FETCH_OBJ);
                $conn = null;
            }
            catch(\PDOException $e){
                echo 'Connection failed: ' . $e->getMessage();
            }
            return $products;
        }
    }


    /**
     * gets all products except the already selected ones
     * @param int $id
     * @return dimensional array with all products that are not in the shoppinlist
     * @throws PDOException
     */
    public function getAllOthers(int $id){
        if (!$this->connectToDB()){
            die('DB Connection error. ProductController.php');
        } else {
            try{
                $conn = $this->connectToDB();
                $stmt = $conn->prepare('SELECT `id`, `name` FROM `Product` WHERE `id` NOT IN 
                                                    (SELECT `pid` FROM `Shoppinglist_Product` WHERE `sid` = ' . $id . ')
                                                    ORDER BY `name` ASC;');
                $stmt->execute();

                // set the resulting array to associative
                $products = $stmt->fetchAll(\PDO::FETCH_OBJ);
                $conn = null;
            }
            catch(\PDOException $e){
                echo 'Connection failed: ' . $e->getMessage();
            }
            return $products;
        }
    }

}
