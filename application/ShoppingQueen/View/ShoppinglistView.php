<?php
/**
 * Created by PhpStorm.
 * User: hava
 * Date: 17.09.18
 * Time: 08:46
 */

namespace application\ShoppingQueen\View;

use core\View\SuperView;
include_once '/var/www/html/core/View/SuperView.php';

class ShoppinglistView extends SuperView
{
    //generates View from all Shoppinglists
    function viewAll($allShoppinglists){
        $viewAll = array();
        $result = '';

        //create icon for adding new list
        session_start();
        if (isset($_SESSION['user'])) {
            $result .= '<nav id="clx-dropdown-navigation" class="add_new">
            <ul style="">
                <li class="level-1" style="">
                    <div class="c7n-icon" onclick="location.href=\'?link=shoppinglists&act=create\'">
                        <div class="shadow add_new_shadow"><img class="fa" src="/themes/images/icons/Orion_plus.svg"></div>
                    </div>
                </li>
            </ul>
        </nav>';
        }
        //create view for all shoppinglists
        foreach ($allShoppinglists as $list) {
            $viewAll_1 = '<div class="col-sm-4 boxes">
                 <a href="?link=shoppinglists&act=detail&id=' . $list[0] .'">
            <div class="color-boxes">
                <h2>' . $list[1];
            if (!empty($list[3])) {
                $viewAll_2 = ' - CHF' . $list[3];
            }
            $viewAll_3 = '</h2>' . $list[2] . ', ' . $list[4] .'</div>
                  </a>
            </div>';
            array_push($viewAll, $viewAll_1 . $viewAll_2 . $viewAll_3);
        }
        foreach ($viewAll as $view) {
           $result .= $view;
        }
        return $result;

    }


    //generates View from one Shoppinglists with the products
    function viewOne($oneShoppinglists){
        $result='';

        //create icon for adding new list
        //session_start();
        if (isset($_SESSION['user'])) {
            $result .= '<nav id="clx-dropdown-navigation" class="add_new">
            <ul style="">
                <li class="level-1" style="">
                    <div class="c7n-icon" onclick="location.href=\'?link=shoppinglists&act=edit&id='. $oneShoppinglists[0] .'\'">
                        <div class="shadow add_new_shadow"><img class="fa" src="/themes/images/icons/Orion_setting.svg"></div>
                    </div>
                </li>
                <li class="level-1" style="">
                    <div class="c7n-icon delete" onclick="location.href=\'?link=shoppinglists&act=delete&id='. $oneShoppinglists[0] .'\'">
                        <div class="shadow add_new_shadow"><img class="fa" src="/themes/images/icons/Orion_bin.svg"></div>
                    </div>
                </li>
            </ul>
        </nav>';
        }
        $viewAll_1 = '<h1 class="d-none title-take">' . $oneShoppinglists[1] . '</h1>';
        if (isset($oneShoppinglists[3])){ $viewAll_2 = '<h2>CHF ' . $oneShoppinglists[3] . '</h2>';}else $viewAll_2 = '';
        $viewAll_3 = '<h3>' . $oneShoppinglists[2] . ', ' . $oneShoppinglists[4] .' </h3>
        <ul class="products">{DETAIL_PRODUCTS}</ul>
        <form method="post" action="" >
            <select name="products" id="products">
                <option value="">-</option>
                {DETAIL_ADD_PRODUCTS}
            </select> 
            <input type="submit" name="submit" value="Add" >
        </form>';

        $result .= $viewAll_1 . $viewAll_2 . $viewAll_3;
        return $result;
    }


    //creates view for editing list
    function viewOneEdit($shoppinglist){
        $result = '<input type="text" name="name" placeholder="name" value="' . $shoppinglist[1] . '" required><br>
            Cost:<br>
            CHF <input type="text" name="cost" placeholder="" value="' . $shoppinglist[3] . '"><br>';
        return $result;
    }



}