<?php
/**
 * Created by PhpStorm.
 * User: hava
 * Date: 17.09.18
 * Time: 08:46
 */

namespace application\ShoppingQueen\View;


use core\View\SuperView;

class ShoppinglistView extends SuperView
{
    //generates View from all Shoppinglists
    function viewAll($allShoppinglists){
        $viewAll = array();
        $result = '';
        foreach ($allShoppinglists as $list) {
            $viewAll_1 = '<div class="col-sm">
                 <a href="/application/ShoppingQueen/Controller/ShoppinglistController.php?act=detail&sid=' . $list[0] .'">
              <div class="col-sm boxes">
              <div class="color-boxes">
                <h2>' . $list[1];
            if (!empty($list[3])) {
                $viewAll_2 = ' - CHF' . $list[3];
            }
            $viewAll_3 = '</h2>
                </div>' . $list[2] . ', ' . $list[4] .'</div>
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
        $viewAll_1 = '<h1 class="d-none title-take">' . $oneShoppinglists[1] . '</h1>';
        if (isset($oneShoppinglists[3])){ $viewAll_2 = '<h2>CHF ' . $oneShoppinglists[3] . '</h2>';}
        $viewAll_3 = '<h3>' . $oneShoppinglists[2] . ', ' . $oneShoppinglists[4] .' </h3>
        <ul class="products">{DETAIL_PRODUCTS}</ul>';

        $result = $viewAll_1 . $viewAll_2 . $viewAll_3;
        return $result;
    }



}