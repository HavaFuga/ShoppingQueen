<?php
/**
 * Created by PhpStorm.
 * User: hava
 * Date: 12.09.18
 * Time: 15:41
 */

namespace application\ShoppingQueen\Controller;

use application\ShoppingQueen\View\ProductView;
use application\ShoppingQueen\Model\Product;

include_once __DIR__ . '/../../../core/Controller/SuperController.php';
include_once __DIR__ . '/../View/ProductView.php';
include_once __DIR__ . '/../Model/Product.php';


class ProductController extends \core\Controller\SuperController
{
    protected $productView;
    protected $product;

    function __construct()
    {
        $this->productView = new ProductView();
        $this->product = new Product();
    }


    //navigates to action from product
    function navigate($action, $id){
        $productController = $this->productController;
        $product = $this->product;
        if ($action == 'detail'){
            $productController->detail($id);
        }elseif ($action == 'edit'){
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $product->edit($id);
            }else{
                $productController->editview($id);
            }
        }elseif ($action == 'delete'){
            $product->delete($id);
        }elseif ($action == 'add'){
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $product->add();
            }else{
                $productController->goToSite('/var/www/html/application/ShoppingQueen/View/create_product_view.html' ,'', '');
            }
        }
        else{
            $productController->overview();
        }
    }

    //creates overview
    function overview(){
        $product = $this->product;
        $productView = $this->productView;
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
        $this->goToSite($this->overview, '', '');
    }

    //prints one products for editing
    function printOneEdit($viewEdit){
        $this->overview = file_get_contents('/var/www/html/application/ShoppingQueen/View/edit_product_view.html');
        $this->overview = str_replace('{EDIT_CONTENT}', $viewEdit, $this->overview);
        //render Shoppinglist in index
        $this->goToSite($this->overview, '', '');
    }

    //gets the view for edit
    function editview($id){
        $product = $this->product;
        $productView = $this->productView;
        $prod = $product->getOne($id);
        $editProductView = $productView->viewEditProducts($prod);

        $this->printOneEdit($editProductView);
    }


}
