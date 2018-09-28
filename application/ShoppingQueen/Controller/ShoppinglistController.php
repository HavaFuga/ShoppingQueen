<?php
/**
 * Created by PhpStorm.
 * User: hava
 * Date: 10.09.18
 * Time: 16:44
 */
namespace application\ShoppingQueen\Controller;



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
        $this->shoppinglist = new \application\ShoppingQueen\Model\Shoppinglist();
        $this->product = new \application\ShoppingQueen\Model\Product();
        $this->productController = new \application\ShoppingQueen\Controller\ProductController();
        $this->user = new \core\Access\Model\User();
    }


    /**
     * navigates to action from shoppinglist
     *
     * @param String $action
     * @param int    $id
     * @param int    $pid
     */
    public function navigate(String $action, int $id, int $pid){
        $shoppinglist = $this->shoppinglist;

        switch ($action) {
            case 'detail':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $pid = htmlspecialchars($_POST['products']);
                    if ($pid == ''){
                        header('Location: ?link=shoppinglists&act=detail&id=' . $id);
                    } else {
                        $shoppinglist->add($id, $pid);

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
                    $shoppinglist->create();
                } else {
                    $this->goToSite('/var/www/html/application/ShoppingQueen/View/create_view.html' , '', '');
                }
                break;
            case 'edit':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $shoppinglist->edit($id);
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
     * creates overview with all shoppinglists
     */
    protected function overview(){
        $shoppinglist = $this->shoppinglist;
        $shoppinglistView = $this->shoppinglistView;
        $allShoppinglists = $shoppinglist->getAll();
        $viewAll = $shoppinglistView->viewAll($allShoppinglists);
        $this->printAll($viewAll);
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
        $products = $product->getAllFromShoppinglist($id);
        $allProducts = $productView->viewAllFromShoppinglist($products);
        $oneShoppinglist = $shoppinglist->getOne($id);
        $viewOne = $shoppinglistView->viewOne($oneShoppinglist);
        $allOtherProducts = $product->getAllOthers($id);
        $allOthers = $productView->viewSelect($allOtherProducts);
        $this->printOne($viewOne, $allProducts, $allOthers);
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

        $list = $shoppinglist->getOne($id);
        $editListView = $shoppinglistView->viewOneEdit($list);
        $products = $product->getAllFromShoppinglist($id);
        $editProductView = $productView->viewEditProductsFromList($products, $id);

        $this->printOneEdit($editListView, $editProductView);
    }


    /**
     * prints the overview with all shoppinglists
     * @param String $viewAll
     */
    protected function printAll(String $viewAll) {
        //render Shoppinglists in overview
        $this->overview = file_get_contents('/var/www/html/application/ShoppingQueen/View/overview_view.html');
        $this->overview = str_replace('{OVERVIEW_SHOPPINGLISTS}', $viewAll, $this->overview);
        //render overview in index
        $this->goToSite($this->overview, '', '');
    }


    /**
     * prints detailview with shoppinglist and it's products
     *
     * @param String $viewOne
     * @param String $allProducts
     * @param String $allOthers
     */
    protected function printOne(String $viewOne, String $allProducts, String $allOthers){
        //render detail from Shoppinglist
        $this->overview = file_get_contents('/var/www/html/application/ShoppingQueen/View/detail_view.html');
        $this->overview = str_replace('{DETAIL_CONTENT}', $viewOne, $this->overview);
        $this->overview = str_replace('{DETAIL_PRODUCTS}', $allProducts, $this->overview);
        $this->overview = str_replace('{DETAIL_ADD_PRODUCTS}', $allOthers, $this->overview);
        //render Shoppinglist in index
        $this->goToSite($this->overview, '', '');
    }


    /**
     * prints information from shoppinglist for editing
     * @param String $viewEdit
     * @param String $editProductView
     */
    protected function printOneEdit(String $viewEdit, String $editProductView){
        //render detail from Shoppinglist
        $this->overview = file_get_contents('/var/www/html/application/ShoppingQueen/View/edit_view.html');
        $this->overview = str_replace('{EDIT_CONTENT}', $viewEdit, $this->overview);
        $this->overview = str_replace('{EDIT_PRODUCTS}', $editProductView, $this->overview);
        //render Shoppinglist in index
        $this->goToSite($this->overview, '', '');
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

}