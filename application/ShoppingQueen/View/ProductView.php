<?php
/**
 * Created by PhpStorm.
 * User: hava
 * Date: 17.09.18
 * Time: 16:18
 */

namespace application\ShoppingQueen\View;


class ProductView
{
    //generates View from the products for detail shoppinglist
    function viewAllFromShoppinglist($products){
        $viewAll = array();
        $result = '';
        session_start();
        if (isset($_SESSION['user'])){
            foreach ($products as $product) {
                array_push($viewAll, '<a href="/application/ShoppingQueen/Controller/ProductController.php?act=detail&id=' . $product[0] . '">
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
                array_push($viewAll, '<a class="a_editProducts" href="/application/ShoppingQueen/Controller/ShoppinglistController.php?act=edit&sid=' . $product[0] . '&do=delete">
                                                <li class="li_editProducts"><img src="/themes/images/icons/Orion_close.svg">' . $product[1] . '</li>
                                           </a>');
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
        foreach ($products as $product) {
            array_push($viewAll, '<a href="/application/ShoppingQueen/Controller/ProductController.php?act=detail&id="' . $product[0] . '">
                                                <li><img src="/themes/images/icons/Orion_angle-right_pink.png">' . $product[1] . '</li>
                                           </a>');
        }
        foreach ($viewAll as $view) {
            $result .= $view;
        }
        return $result;
    }
}