<?php
/**
 * Created by PhpStorm.
 * User: hava
 * Date: 10.09.18
 * Time: 16:44
 */
namespace application\ShoppingQueen\Controller;

use application\ShoppingQueen\View\ShoppinglistView;

include_once __DIR__ . '/../../../core/Controller/SuperController.php';
include_once __DIR__ . '/../View/ShoppinglistView.php';
include_once __DIR__ . '/../Controller/ProductController.php';
include_once __DIR__ . '/../View/ProductView.php';

class ShoppinglistController extends \core\Controller\SuperController
{
    //Get all Shoppinglists from DB
    function getAll(){
        if (!$this->connectToDB()){
            die('DB Connection error. ShoppinglistController.php');
        }else {
            try{
                $conn = $this->connectToDB();
                $stmt = $conn->prepare('SELECT s.id, s.name, s.date, s.cost, u.name FROM Shoppinglist AS s, User AS u WHERE s.uid = u.id;');
                $stmt->execute();

                // set the resulting array to associative
                $result = $stmt->fetchAll();
                $conn = null;
            }
            catch(PDOException $e){
                echo 'Connection failed: ' . $e->getMessage();
            }
            return $result;
        }
    }

    //Get one Shoppinglist from DB
    function getOne($id){
        if (!$this->connectToDB()){
            die('DB Connection error. ShoppinglistController.php');
        }else {
            try{
                $conn = $this->connectToDB();
                $stmt = $conn->prepare('SELECT s.id, s.name, s.date, s.cost, u.name 
                                                  FROM Shoppinglist AS s, User AS u 
                                                  WHERE s.uid = u.id AND s.id = ' . $id .';');
                $stmt->execute();

                // set the resulting array to associative
                $result = $stmt->fetch();
                $conn = null;
            }
            catch(PDOException $e){
                echo 'Connection failed: ' . $e->getMessage();
            }
            return $result;
        }
    }

    //creates overview
    function overview(){

        $shoppinglistView = new \application\ShoppingQueen\View\ShoppinglistView();
        $allShoppinglists = $this->getAll();
        $viewAll = $shoppinglistView->viewAll($allShoppinglists);
        $this->printAll($viewAll);

    }

    //prints all Shoppinglists
    protected $overview;
    function printAll($viewAll) {
        //render Shoppinglists in overview
        $this->overview = file_get_contents('/var/www/html/application/ShoppingQueen/View/overview_view.html');
        $this->overview = str_replace('{OVERVIEW_SHOPPINGLISTS}', $viewAll, $this->overview);
        //render overview in index
        $this->goToSite($this->overview);
    }

    //creates detailview
    function detail($id){
        $shoppinglistView = new \application\ShoppingQueen\View\ShoppinglistView();
        $productController = new \application\ShoppingQueen\Controller\ProductController();
        $productView = new \application\ShoppingQueen\View\ProductView();

        //gets products
        $products = $productController->getAllFromShoppinglist($id);
        $allProducts = $productView->viewAllFromShoppinglist($products);

        $oneShoppinglist = $this->getOne($id);
        $viewOne = $shoppinglistView->viewOne($oneShoppinglist);
        $this->printOne($viewOne, $allProducts);
    }

    //prints detailview from Shoppinglist with Products
    function printOne($viewOne, $allProducts){
        //render detail from Shoppinglist
        $this->overview = file_get_contents('/var/www/html/application/ShoppingQueen/View/detail_view.html');
        $this->overview = str_replace('{DETAIL_CONTENT}', $viewOne, $this->overview);
        $this->overview = str_replace('{DETAIL_PRODUCTS}', $allProducts, $this->overview);
        //render Shoppinglist in index
        $this->goToSite($this->overview);
    }

    //prints informations from Shoppinglist for editing
    function printOneEdit($viewEdit, $editProductView){
        //render detail from Shoppinglist
        $this->overview = file_get_contents('/var/www/html/application/ShoppingQueen/View/edit_view.html');
        $this->overview = str_replace('{EDIT_CONTENT}', $viewEdit, $this->overview);
        $this->overview = str_replace('{EDIT_PRODUCTS}', $editProductView, $this->overview);
        //render Shoppinglist in index
        $this->goToSite($this->overview);
    }

    //create a new Shoppinglist
    function create(){
        session_start();
        $name = $_POST['name'];
        $cost = $_POST['cost'];
        $id = $_SESSION['user'];
        $date = date("d.M.Y");

        //create new shoppinglist
        if (!$this->connectToDB()){
            die('DB Connection error. ShoppinglistController.php');
        }else {
            try{
                $conn = $this->connectToDB();
                $stmt = 'INSERT INTO Shoppinglist(`name`, `date`, `cost`, `uid`)
                                      VALUES ("' . $name . '", "' . $date . '", "' . $cost .'", ' . $id . ');';
                $stmt = $conn->prepare($stmt);
                $stmt->execute();

                //echo 'List added successfully!';
            }
            catch(\PDOException $e){
                echo 'Connection failed: ' . $e->getMessage();
            }
        }

        header('Location: /application/ShoppingQueen/Controller/ShoppinglistController.php');
    }

    //create a new Shoppinglist
    function edit($id, $name, $cost){

        //edit shoppinglist
        if (!$this->connectToDB()){
            die('DB Connection error. ShoppinglistController.php');
        }else {
            try{
                $conn = $this->connectToDB();
                $stmt = 'UPDATE Shoppinglist SET name="' . $name . '", cost="' . $cost . '" WHERE id ='. $id .';';
                $conn->exec($stmt);
                //echo 'List edited successfully!';
            }
            catch(PDOException $e){
                echo 'Connection failed: ' . $e->getMessage();
            }
            $conn = null;
        }
        header('Location: ?link=shoppinglists&act=detail&id=' . $id);
    }

    //gets informations from shoppinglist and opens edit site
    function editview($id){
        $shoppinglistView = new \application\ShoppingQueen\View\ShoppinglistView();
        $productView = new \application\ShoppingQueen\View\ProductView();
        $productController = new \application\ShoppingQueen\Controller\ProductController();

        $shoppinglist = $this->getOne($id);
        $editListView = $shoppinglistView->viewOneEdit($shoppinglist);
        $products = $productController->getAllFromShoppinglist($id);
        $editProductView = $productView->viewEditProductsFromList($products);

        $this->printOneEdit($editListView, $editProductView);
    }

    //deletes the selected list
    function delete($id){
        if (!$this->connectToDB()){
            die('DB Connection error. ShoppinglistController.php');
        }else {
            try{
                $conn = $this->connectToDB();
                $stmt = 'DELETE FROM Shoppinglist WHERE id ='. $id .';';
                $conn->exec($stmt);
                //echo 'List deleted successfully!';
            }
            catch(PDOException $e){
                echo 'Connection failed: ' . $e->getMessage();
            }
            $conn = null;
        }
        header('Location: ?link=shoppinglists');
    }
}