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
     * generates view with the prodcuts for detailview of a shoppinglist
     * @param $products
     * @return string view with the listed products
     * @throws PDOException
     */
    function viewAllFromShoppinglist($products){
        $viewAll = array();
        $result = '';
        session_start();
        if (isset($_SESSION['user'])){
            foreach ($products as $product) {
                array_push($viewAll, '<a href="?link=products">
                                                <li><img src="/themes/images/icons/Orion_angle-right_pink.png">' . $product->name . '</li>
                                           </a>');
            }
        }else{
            foreach ($products as $product) {
                array_push($viewAll, '<li><img src="/themes/images/icons/Orion_angle-right_pink.png">' . $product->name . '</li>');
            }
        }
        foreach ($viewAll as $view) {
            $result .= $view;
        }
        return $result;
    }


    /**
     * generates view for editing of the products
     * @param $products
     * @param $sid
     * @return string view of product listed with remove icon
     * @throws PDOException
     */
    function viewEditProductsFromList($products, $sid){
        session_start();
        $viewAll = array();
        $result = '';
        if (isset($sid)){
            foreach ($products as $product) {
                array_push($viewAll, '<a class="a_editProducts" href="?link=shoppinglists&act=remove&pid=' . $product->id . '&id=' . $sid . '">
                                                    <img src="/themes/images/icons/Orion_bin_red.svg">
                                                </a><li class="li_editProducts">' . $product->name . '</li>');
            }
        }
        foreach ($viewAll as $view) {
            $result .= $view;
        }
        return $result;
    }


    /**
     * generates view of all products
     * @param $products
     * @return string view of all pdroducts
     * @throws PDOException
     */
    function viewAll($products){
        $viewAll = array();
        $result = '';
        $result .= '<nav id="clx-dropdown-navigation" class="add_new">
            <ul style="">
                <li class="level-1" style="">
                    <div class="c7n-icon" onclick="location.href=\'?link=products&act=add\'">
                        <div class="shadow add_new_shadow"><img class="fa" src="/themes/images/icons/Orion_plus.svg"></div>
                    </div>
                </li>
            </ul>
        </nav>';
        foreach ($products as $product) {
            array_push($viewAll, '<div class="products_view">
                                                <h3>' . $product[1] . '</h3>
                                                    <a class="" href="?link=products&act=edit&id=' . $product[0] . '">
                                                        <img src="/themes/images/icons/Orion_setting_blue.svg">
                                                    </a> 
                                                    <a class="" href="?link=products&act=delete&id=' . $product[0] . '">
                                                        <img src="/themes/images/icons/Orion_bin_red.svg">
                                                    </a>
                                            </div>');
        }
        foreach ($viewAll as $view) {
            $result .= $view;
        }
        return $result;
    }


    /**
     * generates view for editing a product
     * @param $product
     * @return string view of editing a product
     * @throws PDOException
     */
    function viewEditProducts($product){
        $result = '<input type="text" name="name" placeholder="name" value="' . $product->name . '" required>';
        return $result;
    }


    /**
     * generates view for selecting and adding a product into a shoppinglist
     * @param $allOtherProducts
     * @return string of products in a option for the dropdown
     * @throws PDOException
     */
    function viewSelect($allOtherProducts){
        $allOthers = '';
        $i = 0;
        foreach ($allOtherProducts as $product){
            $allOthers .= '<option value="' . $product[0] . '">' . $product[1] . '</option>';
            $i + 1;
        }
        return $allOthers;
    }
}