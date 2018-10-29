<?php
/**
 * Created by PhpStorm.
 * User: hava
 * Date: 17.09.18
 * Time: 16:18
 */

namespace application\ShoppingQueen\View;

include_once '/var/www/html/core/View/SuperView.php';

/**
 * Class ProductView
 *
 * This class generates all views for the products
 * @package application\ShoppingQueen\View
 */
class ProductView extends \core\View\SuperView
{
    /**
     * prints the overview with all products
     */
    function printAll(array $products) {
        //render products in overview
        $this->overview = file_get_contents('/var/www/html/application/ShoppingQueen/View/overview_products_view.html');

        $product_view = '';
        foreach ($products as $product) {
            preg_match('/<!--BEGIN PRODUCTS-->.*?<!--END PRODUCTS-->/s', $this->overview, $matches);
            $product_placeholder = $matches[0];

            $product_placeholder = str_replace('{PRODUCT_ID}', $product->id, $product_placeholder);
            $product_placeholder = str_replace('{PRODUCT_NAME}', $product->name, $product_placeholder);
            $product_view .= $product_placeholder;
        }

        $this->overview = preg_replace('/<!--BEGIN PRODUCTS-->.*?<!--END PRODUCTS-->/s', '',  $this->overview);
        $this->overview = str_replace('{PRODUCTS}', $product_view, $this->overview);
        //render overview in index
        $this->goToSite($this->overview, '', '');
    }


    /**
     * prints one product for editing
     * @param String $viewEdit
     */
    function printOneEdit(Object $product){
        $this->overview = file_get_contents('/var/www/html/application/ShoppingQueen/View/edit_product_view.html');
        $this->overview = str_replace('{PRODUCT_NAME}', $product->name, $this->overview);
        //render Shoppinglist in index
        $this->goToSite($this->overview, '', '');
    }


    /**
     * gets the view for editing a product
     * @param int $id
     */
    function editview(Object $product){
        $this->printOneEdit($product);
    }
}