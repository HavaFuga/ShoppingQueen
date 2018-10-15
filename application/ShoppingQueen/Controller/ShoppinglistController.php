<?php
/**
 * Created by PhpStorm.
 * User: hava
 * Date: 10.09.18
 * Time: 16:44
 */
namespace application\ShoppingQueen\Controller;



use application\ShoppingQueen\Model\Shoppinglist;

include_once __DIR__ . '/../../../core/Controller/SuperController.php';
include_once '/var/www/html/application/ShoppingQueen/View/ShoppinglistView.php';
include_once '/var/www/html/application/ShoppingQueen/Controller/ShoppinglistController.php';
include_once '/var/www/html/application/ShoppingQueen/View/ProductView.php';
include_once '/var/www/html/application/ShoppingQueen/Model/Shoppinglist.php';
include_once '/var/www/html/application/ShoppingQueen/Model/Product.php';
include_once '/var/www/html/core/Access/Model/User.php';

/**
 * Class ShoppinglistController
 *
 * This Class navigates to method with the param 'act'
 * and controlls all the actions that a shoppinglist can do
 * @package application\ShoppingQueen\Controller
 */
class ShoppinglistController extends \core\Controller\SuperController
{
    /**
     * @var \application\ShoppingQueen\View\ShoppinglistView
     * @var \application\ShoppingQueen\View\ProductView
     * @var \application\ShoppingQueen\Controller\ProductController
     * @var \application\ShoppingQueen\Model\Shoppinglist
     * @var \application\ShoppingQueen\Model\Product
     * @var \core\Access\Model\User\User
     * @var overview
     */
    protected $shoppinglistView;
    protected $productView;
    protected $productController;
    protected $shoppinglist;
    protected $product;
    protected $user;
    protected $overview;

    /**
     * ShoppinglistController constructor.
     */
    public function __construct()
    {
        $this->shoppinglistView = new \application\ShoppingQueen\View\ShoppinglistView();
        $this->productView = new \application\ShoppingQueen\View\ProductView();
        //$this->shoppinglist = new
        //$this->product = new \application\ShoppingQueen\Model\Product();
        $this->productController = new \application\ShoppingQueen\Controller\ProductController();
        //$this->user = new \core\Access\Model\User();
    }


    /**
     * navigates to action from shoppinglist
     *
     * @param String $action
     * @param int    $id
     * @param int    $pid
     */
    public function navigate(String $action, int $id, int $pid){
        $productController = $this->productController;
        if ($id != 0){
            $sl = $this->getOne($id);
            $shoppinglist = new \application\ShoppingQueen\Model\Shoppinglist($sl->id, $sl->name, $sl->date, $sl->cost, $sl->userName);
        }
        if ($pid != 0){
            $p = $productController->getOne($pid);
            $product = new \application\ShoppingQueen\Model\Product($p->id, $p->name);
        }



        switch ($action) {
            case 'detail':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $pid = htmlspecialchars($_POST['products']);
                    if ($pid == ''){
                        header('Location: ?link=shoppinglists&act=detail&id=' . $id);
                    } else {
                        $product = $productController->getOne($pid);
                        $shoppinglist->add($product);

                        $this->detail($id);
                        //$this->goToSite('/var/www/html/application/ShoppingQueen/View/detail_view.html?link=shoppinglists&act=detail&id=' . $id, 'Product added successfully!', 'true');
                        header('Location: ?link=shoppinglists&act=detail&id=' . $id);

                    }
                } else {
                    $this->detail($id);
                }
                break;

            case 'create':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $this->create();
                } else {
                    $this->goToSite('/var/www/html/application/ShoppingQueen/View/create_view.html' , '', '');
                }
                break;
            case 'edit':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $shoppinglist->edit($shoppinglist);
                    header('Location: ?link=shoppinglists&act=detail&id=' . $id);

                } else {
                    $this->editview($id);
                }
                break;
            case 'delete':
                $shoppinglist->delete($id);
                break;
            case 'overview':
                $this->overview();
                break;
            case 'remove':
                $shoppinglist->remove($id, $pid);
                break;
            case 'missing':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $this->missing();
                } else {
                    $this->goToSite('/var/www/html/application/ShoppingQueen/View/missing_product_view.html' , '', '');
                }
                break;
        }

    }

    /**
     * gets one shoppinglist from DB
     * @param int $id
     * @return array from shoppinglist
     * @throws PDOException
     */
    public function getOne(int $id) {
        if (!$this->connectToDB()) {
            die('DB Connection error. Shoppinglist.php');
        } else {
            try {
                $conn = $this->connectToDB();
                $stmt = $conn->prepare('SELECT s.`id`, s.`name`, s.`date`, s.`cost`, u.`name` as userName
                                                  FROM `Shoppinglist` AS s, `User` AS u 
                                                  WHERE s.`uid` = u.`id` AND s.`id` = ' . $id .';');
                $stmt->execute();
                $oneShoppinglist = $stmt->fetchObject();
                $conn = null;
                //var_dump($result);die();
            }
            catch(\PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
            }
            return $oneShoppinglist;
        }
    }


    /**
     * gets all shoppinglists from DB
     * @return array with all shoppinglists
     * @throws PDOException
     */
    public function getAll() {
        if (!$this->connectToDB()) {
            die('DB Connection error. Shoppinglist.php');
        } else {
            try {
                $conn = $this->connectToDB();
                $stmt = $conn->prepare('SELECT s.`id`, s.`name`, s.`date`, s.`cost`, u.`name` as userName FROM `Shoppinglist` AS s, `User` AS u 
                                                  WHERE s.`uid` = u.`id`
                                                  ORDER BY s.`date` ASC;');
                $stmt->execute();
                // set the resulting array to associative
                $result = $stmt->fetchAll(\PDO::FETCH_OBJ);
                //var_dump($result);die();
                $conn = null;
            }
            catch(\PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
            }
            return $result;
        }
    }


    /**
     * creates overview with all shoppinglists
     */
    protected function overview(){
        $shoppinglistView = $this->shoppinglistView;
        $allShoppinglists = $this->getAll();
        $viewAll = $shoppinglistView->viewAll($allShoppinglists);
        $shoppinglistView->printAll($viewAll);
    }

    /**
     * creates detailview from one shoppinglist
     * @param int $id
     */
    protected function detail(int $id){
        $shoppinglistView = $this->shoppinglistView;
        $productController = $this->productController;
        $productView = $this->productView;
        $shoppinglist = $this->shoppinglist;
        $product = $this->product;

        //gets products
        $products = $productController->getAllFromShoppinglist($id);
        $allProducts = $productView->viewAllFromShoppinglist($products);
        $oneShoppinglist = $this->getOne($id);
        $viewOne = $shoppinglistView->viewOne($oneShoppinglist);
        $allOtherProducts = $productController->getAllOthers($id);
        $allOthers = $productView->viewSelect($allOtherProducts);
        $shoppinglistView->printOne($viewOne, $allProducts, $allOthers);
    }


    /**
     * creates view for editing shoppinglist
     * @param int $id
     */
    protected function editview(int $id){
        $shoppinglistView = $this->shoppinglistView;
        $productView = $this->productView;
        $productController = $this->productController;
        $shoppinglist = $this->shoppinglist;
        $product = $this->product;

        $list = $this->getOne($id);
        $editListView = $shoppinglistView->viewOneEdit($list);
        $products = $productController->getAllFromShoppinglist($id);
        $editProductView = $productView->viewEditProductsFromList($products, $id);

        $shoppinglistView->printOneEdit($editListView, $editProductView);
    }


    /**
     * sends an Email to all admins
     * in whitch it sais whitch product is missing
     */
    protected function missing(){
        $name = htmlspecialchars($_POST['name']);
        $product = htmlspecialchars($_POST['product']);
        $user = $this->user;
        $allAdmins = '';
        $admins = $user->admins();
        foreach ($admins as $admin){
           $allAdmins .= $admin[2] . ', ';
        }
        $to = $allAdmins;
        $subject = 'Missing Product';
        $message = 'Hello! There\'s a missing product: ' . $product . "\r\n" . 'Pleas create one http://programming.lvh.me/?link=products&act=add';
        $message = wordwrap($message,70);
        $headers = "From: webmaster@example.com" . "\r\n" .
            "CC: somebodyelse@example.com";
        mail($to, $subject, $message, $headers);
        $this->goToSite('/var/www/html/application/ShoppingQueen/View/missing_product_view.html', 'Thank you! An E-Mail was sended to the admins.', 'true');
    }




    /**
     * creates a new shoppinglist
     * @throws PDOException
     */
    public function create() {
        session_start();
        $name = htmlspecialchars($_POST['name']);
        $cost = htmlspecialchars($_POST['cost']);
        $id = $_SESSION['user'];
        $date = date("Y-m-d");

        if (!$this->connectToDB()) {
            die('DB Connection error. Shoppinglist.php');
        } else {
            try {
                $conn = $this->connectToDB();
                $stmt = 'INSERT INTO `Shoppinglist`(`name`, `date`, `cost`, `uid`)
                                      VALUES ("' . $name . '", "' . $date . '", "' . $cost .'", ' . $id . ');';
                $stmt = $conn->prepare($stmt);
                $stmt->execute();

                //echo 'List added successfully!';
            }
            catch(\PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
            }
        }
        header('Location: ?link=shoppinglists');
    }


    /**
     * updates a shoppinglist
     * @param int $id
     * @throws PDOException
     */
    public function edit(int $id) {
        $name = htmlspecialchars($_POST['name']);
        $cost = ($_POST['cost']);

        if (!$this->connectToDB()) {
            die('DB Connection error. Shoppinglist.php');
        } else {
            try {
                $conn = $this->connectToDB();
                $stmt = 'UPDATE `Shoppinglist` SET `name`="' . $name . '", `cost`="' . $cost . '" WHERE `id` ='. $id .';';
                $conn->exec($stmt);
            }
            catch(\PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
            }
            $conn = null;
        }
        header('Location: ?link=shoppinglists&act=detail&id=' . $id);
    }


    /**
     * deletes a shoppinglist
     * @param int $id
     * @throws PDOException
     */
    public function delete(int $id) {
        if (!$this->connectToDB()) {
            die('DB Connection error. Shoppinglist.php');
        } else {
            try {
                $conn = $this->connectToDB();
                $stmt = 'DELETE FROM `Shoppinglist` WHERE `id` ='. $id .';';
                $conn->exec($stmt);
                //echo 'List deleted successfully!';
            }
            catch(\PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
            }
            $conn = null;
        }
        header('Location: ?link=shoppinglists');
    }


    /**
     * adds a products to a shoppinglist
     * @param int $id
     * @param int $pid
     * @throws PDOException
     */
    public function add(int $id, int $pid) {
        if (!$this->connectToDB()) {
            die('DB Connection error. Shoppinglist.php');
        } else {
            try {
                $conn = $this->connectToDB();
                $stmt = 'INSERT INTO `Shoppinglist_Product`(`sid`, `pid`)
                                      VALUES (' . $id . ', ' . $pid . ');';
                $stmt = $conn->prepare($stmt);
                $stmt->execute();
            }
            catch(\PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
            }
        }
    }


    /**
     * removes product from shoppinglist
     * @param int $id
     * @param int $pid
     * @throws PDOException
     */
    public function remove(int $id, int $pid) {
        if (!$this->connectToDB()) {
            die('DB Connection error. Shoppinglist.php');
        } else {
            try {
                $conn = $this->connectToDB();
                $stmt = 'DELETE FROM `Shoppinglist_Product` 
                            WHERE `sid` = ' . $id . ' AND `pid` = ' . $pid . ';';
                $stmt = $conn->prepare($stmt);
                $stmt->execute();

                //echo 'Product removed successfully!';
                header('Location: ?link=shoppinglists&act=edit&id=' . $id);
            }
            catch(\PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
            }
        }
    }

}