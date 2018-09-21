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
include_once __DIR__ . '/../Model/Product.php';

class ProductController extends \core\Controller\SuperController
{

    //creates overview
    function overview(){
        //session_start();
        $product = new \application\ShoppingQueen\Model\Product();
        $productView = new \application\ShoppingQueen\View\ProductView();
        $allProducts = $product->getAll();
        $viewAll = $productView->viewAll($allProducts);
        $this->printAll($viewAll);
    }

    //prints all products
    protected $overview;
    function printAll($viewAll) {
        //render products in overview
        $this->overview = file_get_contents('/var/www/html/application/ShoppingQueen/View/overview_products_view.html');
        $this->overview = str_replace('{OVERVIEW_PRODUCTS}', $viewAll, $this->overview);
        //render overview in index
        $this->goToSite($this->overview);
    }

    //prints one products for editing
    function printOneEdit($viewEdit){
        $this->overview = file_get_contents('/var/www/html/application/ShoppingQueen/View/edit_product_view.html');
        $this->overview = str_replace('{EDIT_CONTENT}', $viewEdit, $this->overview);
        //render Shoppinglist in index
        $this->goToSite($this->overview);
    }

    //gets the view for edit
    function editview($id){
        $product = new \application\ShoppingQueen\Model\Product();
        $productView = new \application\ShoppingQueen\View\ProductView();
        $prod = $product->getOne($id);
        $editProductView = $productView->viewEditProducts($prod);

        $this->printOneEdit($editProductView);
    }


}
