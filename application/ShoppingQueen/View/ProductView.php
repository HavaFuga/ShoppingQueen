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
    //generates View from the products
    function viewAll($products){
        $viewAll = array();
        $result = '';
        foreach ($products as $product){
            array_push($viewAll,'<li><img src="/themes/images/icons/Orion_angle-right_pink.png">' . $product[0] . '</li>');
        }
        foreach ($viewAll as $view) {
            $result .= $view;
        }
        return $result;
    }
}