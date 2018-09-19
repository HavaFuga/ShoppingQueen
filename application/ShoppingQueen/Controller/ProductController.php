<?php
/**
 * Created by PhpStorm.
 * User: hava
 * Date: 12.09.18
 * Time: 15:41
 */

namespace application\ShoppingQueen\Controller;

include_once __DIR__ . '/../../../core/Controller/SuperController.php';
include_once __DIR__ . '/../View/ProductView.php';

class ProductController extends \core\Controller\SuperController
{
    //gets all Products from Shoppinglist with id
    function getAllFromShoppinglist($id){
        if (!$this->connectToDB()){
            die('DB Connection error. ProductController.php');
        }else {
            try{
                $conn = $this->connectToDB();
                $stmt = $conn->prepare('SELECT p.id, p.name FROM Product AS p, Shoppinglist_Product AS sp 
                                                  WHERE sp.sid = ' . $id . ' AND p.id = sp.pid;');
                $stmt->execute();

                // set the resulting array to associative
                $products = $stmt->fetchAll();
                $conn = null;
            }
            catch(PDOException $e){
                echo 'Connection failed: ' . $e->getMessage();
            }
            return $products;
        }
    }

    //gets all Products
    function getAll(){
        if (!$this->connectToDB()){
            die('DB Connection error. ProductController.php');
        }else {
            try{
                $conn = $this->connectToDB();
                $stmt = $conn->prepare('SELECT id, name FROM Product;');
                $stmt->execute();

                // set the resulting array to associative
                $products = $stmt->fetchAll();
                $conn = null;
            }
            catch(PDOException $e){
                echo 'Connection failed: ' . $e->getMessage();
            }
            return $products;
        }
    }

    //creates overview
    function overview(){
        session_start();
        $productView = new \application\ShoppingQueen\View\ProductView();
        $allProducts = $this->getAll();
        $viewAll = $productView->viewAll($allProducts);
        $this->printAll($viewAll);
    }

    protected $overview;
    function printAll($viewAll) {
        //render products in overview
        $this->overview = file_get_contents('/var/www/html/application/ShoppingQueen/View/overview_products_view.html');
        $this->overview = str_replace('{OVERVIEW_PRODUCTS}', $viewAll, $this->overview);
        //render overview in index
        $this->goToSite($this->overview);
    }
}
