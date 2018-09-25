<?php
/**
 * Created by PhpStorm.
 * User: hava
 * Date: 10.09.18
 * Time: 16:44
 */
namespace application\ShoppingQueen\Controller;

use application\ShoppingQueen\Model\Product;
use application\ShoppingQueen\Model\Shoppinglist;
use application\ShoppingQueen\View\ShoppinglistView;
use application\ShoppingQueen\View\ProductView;

include_once __DIR__ . '/../../../core/Controller/SuperController.php';
include_once '/var/www/html/application/ShoppingQueen/View/ShoppinglistView.php';
include_once '/var/www/html/application/ShoppingQueen/Model/Shoppinglist.php';
include_once '/var/www/html/application/ShoppingQueen/Controller/ProductController.php';
include_once '/var/www/html/application/ShoppingQueen/View/ProductView.php';
include_once '/var/www/html/application/ShoppingQueen/Model/Product.php';

class ShoppinglistController extends \core\Controller\SuperController
{
    protected $shoppinglistView;
    protected $shoppinglist;
    protected $productController;
    protected $productView;
    protected $product;

    function __construct()
    {
        $this->shoppinglistView = new ShoppinglistView();
        $this->shoppinglist = new Shoppinglist();
        $this->productController = new ProductController();
        $this->productView = new ProductView();
        $this->product = new Product();
    }


    //creates overview
    function overview(){
        $shoppinglist = $this->shoppinglist;
        $shoppinglistView = $this->shoppinglistView;
        $allShoppinglists = $shoppinglist->getAll();
        $viewAll = $shoppinglistView->viewAll($allShoppinglists);
        $this->printAll($viewAll);
    }

    //creates detailview
    function detail($id){
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

    //creates view for editing informations from shoppinglist
    function editview($id){
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

    //prints all Shoppinglists
    protected $overview;
    function printAll($viewAll) {
        //render Shoppinglists in overview
        $this->overview = file_get_contents('/var/www/html/application/ShoppingQueen/View/overview_view.html');
        $this->overview = str_replace('{OVERVIEW_SHOPPINGLISTS}', $viewAll, $this->overview);
        //render overview in index
        $this->goToSite($this->overview, '', '');
    }

    //prints detailview from Shoppinglist with Products
    function printOne($viewOne, $allProducts, $allOthers){
        //render detail from Shoppinglist
        $this->overview = file_get_contents('/var/www/html/application/ShoppingQueen/View/detail_view.html');
        $this->overview = str_replace('{DETAIL_CONTENT}', $viewOne, $this->overview);
        $this->overview = str_replace('{DETAIL_PRODUCTS}', $allProducts, $this->overview);
        $this->overview = str_replace('{DETAIL_ADD_PRODUCTS}', $allOthers, $this->overview);
        //render Shoppinglist in index
        $this->goToSite($this->overview, '', '');
    }

    //prints informations from Shoppinglist for editing
    function printOneEdit($viewEdit, $editProductView){
        //render detail from Shoppinglist
        $this->overview = file_get_contents('/var/www/html/application/ShoppingQueen/View/edit_view.html');
        $this->overview = str_replace('{EDIT_CONTENT}', $viewEdit, $this->overview);
        $this->overview = str_replace('{EDIT_PRODUCTS}', $editProductView, $this->overview);
        //render Shoppinglist in index
        $this->goToSite($this->overview, '', '');
    }

}