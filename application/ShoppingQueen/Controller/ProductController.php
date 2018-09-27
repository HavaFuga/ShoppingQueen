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
        $this->product = new \application\ShoppingQueen\Model\Product();
    }


    /**
     * navigates to action from product
     * @param string $action
     * @param int $id
     */
    function navigate(string $action, int $id){
        $product = $this->product;

        switch ($action){
            case ('detail'):
                $this->detail($id);
                break;

            case ('edit'):
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $product->edit($id);
                }else{
                    $this->editview($id);
                }
                break;

            case ('delete'):
                $product->delete($id);
                break;

            case ('add'):
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $product->add();
                }else{
                    $this->goToSite('/var/www/html/application/ShoppingQueen/View/create_product_view.html' ,'', '');
                }
                break;

            case ('overview'):
                $this->overview();
                break;
        }
    }

    //creates overview

    /**
     * creates overview with products
     */
    function overview(){
        $product = $this->product;
        $productView = $this->productView;
        $allProducts = $product->getAll();
        $viewAll = $productView->viewAll($allProducts);
        $this->printAll($viewAll);
    }

    //prints all products

    /**
     * prints the overview with all products
     */
    function printAll(String $viewAll) {
        //render products in overview
        $this->overview = file_get_contents('/var/www/html/application/ShoppingQueen/View/overview_products_view.html');
        $this->overview = str_replace('{OVERVIEW_PRODUCTS}', $viewAll, $this->overview);
        //render overview in index
        $this->goToSite($this->overview, '', '');
    }

    /**
     * prints one product for editing
     * @param String $viewEdit
     */
    function printOneEdit(String $viewEdit){
        $this->overview = file_get_contents('/var/www/html/application/ShoppingQueen/View/edit_product_view.html');
        $this->overview = str_replace('{EDIT_CONTENT}', $viewEdit, $this->overview);
        //render Shoppinglist in index
        $this->goToSite($this->overview, '', '');
    }

    //gets the view for edit

    /**
     * gets the view for editing a product
     * @param int $id
     */
    function editview(int $id){
        $product = $this->product;
        $productView = $this->productView;
        $prod = $product->getOne($id);
        $editProductView = $productView->viewEditProducts($prod);

        $this->printOneEdit($editProductView);
    }


}
