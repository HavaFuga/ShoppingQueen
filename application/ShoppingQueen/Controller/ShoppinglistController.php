<?php
/**
 * Created by PhpStorm.
 * User: hava
 * Date: 10.09.18
 * Time: 16:44
 */
namespace application\ShoppingQueen\Controller;

include_once __DIR__ . '/../../../core/Controller/SuperController.php';
include_once __DIR__ . '/../View/ShoppinglistView.php';
include_once __DIR__ . '/../Controller/ProductController.php';
include_once __DIR__ . '/../View/ProductView.php';


$action = $_GET['act'];

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
    function detail(){
        $shoppinglistView = new \application\ShoppingQueen\View\ShoppinglistView();
        $productController = new \application\ShoppingQueen\Controller\ProductController();
        $productView = new \application\ShoppingQueen\View\ProductView();
        $id = $_GET['sid'];

        //gets products
        $products = $productController->getAll($id);
        $allProducts = $productView->viewAll($products);

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
}

$shoppinglistController = new ShoppinglistController();
if ($action == 'detail'){
    $shoppinglistController->detail();
}else{
    $shoppinglistController->overview();
}

