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
include_once __DIR__ . '/../Model/Shoppinglist.php';
include_once __DIR__ . '/../Controller/ProductController.php';
include_once __DIR__ . '/../View/ProductView.php';
include_once __DIR__ . '/../Model/Product.php';

class ShoppinglistController extends \core\Controller\SuperController
{
    /*
     * create only one class
     * also probably in superclass
     *
     * protected $shoppinglistController;
    protected $shoppinglistView;
    protected $shoppinglist;
    protected $productController;
    protected $productView;
    protected $product;
    protected $userController;
    protected $userView;
    protected $user;


    function __construct()
    {
        $this->shoppinglistController = new ShoppinglistController();
        $this->shoppinglistView = new ShoppinglistView();
        $this->shoppinglist = new Shoppinglist();
        $this->productController = new ProductController();
        $this->productView = new ProductView();
        $this->product = new Product();
        $this->userController = new UserController();
        $this->userView = new UserView();
        $this->user = new User();

    }*/

    //creates overview
    function overview(){
        $shoppinglist = new \application\ShoppingQueen\Model\Shoppinglist();
        $shoppinglistView = new \application\ShoppingQueen\View\ShoppinglistView();
        $allShoppinglists = $shoppinglist->getAll();
        $viewAll = $shoppinglistView->viewAll($allShoppinglists);
        $this->printAll($viewAll);

    }

    //creates detailview
    function detail($id){
        $shoppinglistView = new \application\ShoppingQueen\View\ShoppinglistView();
        $productController = new \application\ShoppingQueen\Controller\ProductController();
        $productView = new \application\ShoppingQueen\View\ProductView();
        $shoppinglist = new \application\ShoppingQueen\Model\Shoppinglist();
        $product = new \application\ShoppingQueen\Model\Product();

        //gets products
        $products = $product->getAllFromShoppinglist($id);
        $allProducts = $productView->viewAllFromShoppinglist($products);
        $oneShoppinglist = $shoppinglist->getOne($id);
        $viewOne = $shoppinglistView->viewOne($oneShoppinglist);
        $allOtherProducts = $product->getAllOthers($id);
        $allOthers = $productView->viewSelect($allOtherProducts);
        $this->printOne($viewOne, $allProducts, $allOthers);
    }

    //creates view for editing informations from shoppinglist
    function editview($id){
        $shoppinglistView = new \application\ShoppingQueen\View\ShoppinglistView();
        $productView = new \application\ShoppingQueen\View\ProductView();
        $productController = new \application\ShoppingQueen\Controller\ProductController();
        $shoppinglist = new \application\ShoppingQueen\Model\Shoppinglist();
        $product = new \application\ShoppingQueen\Model\Product();

        $list = $shoppinglist->getOne($id);
        $editListView = $shoppinglistView->viewOneEdit($list);
        $products = $product->getAllFromShoppinglist($id);
        $editProductView = $productView->viewEditProductsFromList($products);

        $this->printOneEdit($editListView, $editProductView);
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

    //prints detailview from Shoppinglist with Products
    function printOne($viewOne, $allProducts, $allOthers){
        //render detail from Shoppinglist
        $this->overview = file_get_contents('/var/www/html/application/ShoppingQueen/View/detail_view.html');
        $this->overview = str_replace('{DETAIL_CONTENT}', $viewOne, $this->overview);
        $this->overview = str_replace('{DETAIL_PRODUCTS}', $allProducts, $this->overview);
        $this->overview = str_replace('{DETAIL_ADD_PRODUCTS}', $allOthers, $this->overview);
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

}