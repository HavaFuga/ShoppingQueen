<?php
/**
 * Created by PhpStorm.
 * User: hava
 * Date: 17.09.18
 * Time: 08:46
 */

namespace application\ShoppingQueen\View;

use application\ShoppingQueen\Model\Shoppinglist;

include_once '/var/www/html/core/View/SuperView.php';

/**
 * Class ShoppinglistView
 *
 * This class generates all views for shoppinglists
 * @package application\ShoppingQueen\View
 */
class ShoppinglistView extends \core\View\SuperView
{
    /**
     * replaces all placeholder with the shoppinglistinfos
     * prints the overview with all shoppinglists
     * @param String $viewAll
     */
    public function printAll(array $allShoppinglists) {
        $this->overview = file_get_contents('/var/www/html/application/ShoppingQueen/View/overview_view.html');

        //checks if user is logged in
        if (!isset($_SESSION['user'])){
            $this->overview = preg_replace('/<!--BEGIN NAVI-->.*?<!--END NAVI-->/s','',$this->overview );
        }

        //
        $shoppinglist_view = '';
        foreach ($allShoppinglists as $list) {
            preg_match('/<!--BEGIN SHOPPINGLISTS-->(.*?)<!--END SHOPPINGLISTS-->/s', $this->overview, $matches);
            $shoppinglist_placeholder = $matches[0];

            $shoppinglist_placeholder = str_replace('{SHOPPINGLIST_ID}', $list->id, $shoppinglist_placeholder);
            $shoppinglist_placeholder = str_replace('{SHOPPINGLIST_NAME}', $list->name, $shoppinglist_placeholder);
            $shoppinglist_placeholder = str_replace('{SHOPPINGLIST_COST}', $list->cost, $shoppinglist_placeholder);
            $shoppinglist_placeholder = str_replace('{SHOPPINGLIST_DATE}', $newDate = date("d.M.Y", strtotime($list->date)), $shoppinglist_placeholder);
            $shoppinglist_placeholder = str_replace('{USER_NAME}', $list->userName, $shoppinglist_placeholder);
            $shoppinglist_view .= $shoppinglist_placeholder;
        }

        $this->overview = preg_replace('/<!--BEGIN SHOPPINGLISTS-->.*?<!--END SHOPPINGLISTS-->/s', '',  $this->overview);
        $this->overview = str_replace('{SHOPPINGLISTS}', $shoppinglist_view, $this->overview);
        //render overview in index
        $this->goToSite($this->overview, '', '');
    }



    /**
     * replaces all placeholder
     * prints detailview with shoppinglist and it's products
     *
     * @param String $viewOne
     * @param String $allProducts
     * @param String $allOthers
     */
    public function printOne(Object $oneShoppinglists, array $products, String $allOthers){
        //render detail from Shoppinglist
        $this->overview = file_get_contents('/var/www/html/application/ShoppingQueen/View/detail_view.html');

        //checks if user is logged in
        if (!isset($_SESSION['user'])){
            $this->overview = preg_replace('/<!--BEGIN NAVI-->.*?<!--END NAVI-->/s','',$this->overview );
        }

        //replace placeholder with Shoppinglist info
        $this->overview = str_replace('{SHOPPINGLIST_ID}', $oneShoppinglists->id, $this->overview);
        $this->overview = str_replace('{SHOPPINGLIST_NAME}', $oneShoppinglists->name, $this->overview);
        $this->overview = str_replace('{SHOPPINGLIST_COST}', $oneShoppinglists->cost, $this->overview);
        $this->overview = str_replace('{SHOPPINGLIST_DATE}', $newDate = date("d.M.Y", strtotime($oneShoppinglists->date)), $this->overview);
        $this->overview = str_replace('{USER_NAME}', $oneShoppinglists->userName, $this->overview);


        preg_match('/<!--BEGIN PRODUCTS-->(.*?)<!--END PRODUCTS-->/s', $this->overview, $matches);
        $product_place = $matches[0];

        //checks if user is logged in
        if (!isset($_SESSION['user'])){
            $product_place = preg_replace('/<!--BEGIN LOGGEDIN-->.*?<!--END LOGGEDIN-->/s','',$product_place );
        } else {
            $product_place = preg_replace('/<!--BEGIN LOGGEDOUT-->.*?<!--END LOGGEDOUT-->/s','',$product_place );
        }


        $product_view = '';
        foreach ($products as $product) {
            $product_placeholder = $product_place;
            $product_placeholder = str_replace('{PRODUCT_NAME}', $product->name, $product_placeholder);
            $product_view .= $product_placeholder;
        }

        $this->overview = preg_replace('/<!--BEGIN PRODUCTS-->.*?<!--END PRODUCTS-->/s', '',  $this->overview);
        $this->overview = str_replace('{PRODUCTS}', $product_view, $this->overview);


        $this->overview = str_replace('{DETAIL_ADD_PRODUCTS}', $allOthers, $this->overview);

        //render Shoppinglist in index
        $this->goToSite($this->overview, '', '');
    }


    /**
     * replaces all placeholder
     * prints information from shoppinglist for editing
     * @param String $viewEdit
     * @param String $editProductView
     */
    public function printOneEdit(Object $shoppinglist, array $products){
        //render detail from Shoppinglist
        $this->overview = file_get_contents('/var/www/html/application/ShoppingQueen/View/edit_view.html');
        $this->overview = str_replace('{SHOPPINGLIST_NAME}', $shoppinglist->name, $this->overview);
        $this->overview = str_replace('{SHOPPINGLIST_COST}', $shoppinglist->cost, $this->overview);
        $this->overview = str_replace('{SHOPPINGLIST_ID}', $shoppinglist->id, $this->overview);

        preg_match('/<!--BEGIN PRODUCTS-->(.*?)<!--END PRODUCTS-->/s', $this->overview, $matches);
        $product_place = $matches[0];

        //print products
        $product_view = '';
        foreach ($products as $product) {
            $product_placeholder = $product_place;
            $product_placeholder = str_replace('{PRODUCT_ID}', $product->id, $product_placeholder);
            $product_placeholder = str_replace('{PRODUCT_NAME}', $product->name, $product_placeholder);
            $product_view .= $product_placeholder;
        }

        $this->overview = preg_replace('/<!--BEGIN PRODUCTS-->.*?<!--END PRODUCTS-->/s', '',  $this->overview);
        $this->overview = str_replace('{EDIT_PRODUCTS}', $product_view, $this->overview);

        //render Shoppinglist in index
        $this->goToSite($this->overview, '', '');
    }



}