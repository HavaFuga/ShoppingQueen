<?php
/**
 * Created by PhpStorm.
 * User: hava
 * Date: 17.09.18
 * Time: 16:18
 */

namespace application\ShoppingQueen\View;

use core\View\SuperView;
include_once '/var/www/html/core/View/SuperView.php';

class ProductView extends SuperView
{
    //generates View from the products for detail shoppinglist
    function viewAllFromShoppinglist($products){
        $viewAll = array();
        $result = '';
        session_start();
        if (isset($_SESSION['user'])){
            foreach ($products as $product) {
                array_push($viewAll, '<a href="?link=products">
                                                <li><img src="/themes/images/icons/Orion_angle-right_pink.png">' . $product[1] . '</li>
                                           </a>');
            }
        }else{
            foreach ($products as $product) {
                array_push($viewAll, '<li><img src="/themes/images/icons/Orion_angle-right_pink.png">' . $product[1] . '</li>');
            }
        }
        foreach ($viewAll as $view) {
            $result .= $view;
        }
        return $result;
    }

    //generates View from the products for detail shoppinglist
    function viewEditProductsFromList($products){
        $viewAll = array();
        $result = '';
        session_start();
        if (isset($_SESSION['user'])){
            foreach ($products as $product) {
                array_push($viewAll, '<a class="a_editProducts" href="?link=shoppinglists&act=remove&sid=' . $product[0] . '">
                                                    <img src="/themes/images/icons/Orion_bin_red.svg">
                                                </a><li class="li_editProducts">' . $product[1] . '</li>');
            }
        }
        foreach ($viewAll as $view) {
            $result .= $view;
        }
        return $result;
    }

    //generates View from the products
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
                                                <h2>' . $product[1] . '</h2>
                                                    <a class="" href="?link=products&act=edit&id=' . $product[0] . '">
                                                        <img src="/themes/images/icons/Orion_setting_black.svg">
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


    //view for editing product
    function viewEditProducts($product){
        $result = '<input type="text" name="name" placeholder="name" value="' . $product[0][0] . '" required>';
        return $result;
    }

    //view for selecting products
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