<?php
/**
 * Created by PhpStorm.
 * User: hava
 * Date: 17.09.18
 * Time: 08:46
 */

namespace application\ShoppingQueen\View;

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
     * generates View with all shoppinglists
     * @param $allShoppinglists
     * @return string view with all shoppinglists
     * @throws PDOException
     */
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
                 <a href="?link=shoppinglists&act=detail&id=' . $list->id .'">
            <div class="color-boxes">
                <h2>' . $list->name;
            if (!empty($list->cost)) {
                $viewAll_2 = ' - CHF' . $list->cost;
            }
            $viewAll_3 = '</h2>' . $newDate = date("d.M.Y", strtotime($list->date)) . ', ' . $list->userName .'</div>
                  </a>
            </div>';
            array_push($viewAll, $viewAll_1 . $viewAll_2 . $viewAll_3);
        }
        foreach ($viewAll as $view) {
           $result .= $view;
        }
        return $result;

    }


    /**
     * generates detailview for one shoppinglists with the products
     * @param $oneShoppinglists
     * @return string
     * @throws PDOException
     */
    function viewOne($oneShoppinglists){
        $result='';
        //session_start();
        //create icon for editing and deleting list
        if (isset($_SESSION['user'])) {
            $result .= '<nav id="clx-dropdown-navigation" class="add_new">
            <ul style="">
                <li class="level-1" style="">
                    <div class="c7n-icon" onclick="location.href=\'?link=shoppinglists&act=edit&id='. $oneShoppinglists->id .'\'">
                        <div class="shadow add_new_shadow"><img class="fa" src="/themes/images/icons/Orion_setting.svg"></div>
                    </div>
                </li>
                <li class="level-1" style="">
                    <div class="c7n-icon delete" onclick="location.href=\'?link=shoppinglists&act=delete&id='. $oneShoppinglists->id .'\'">
                        <div class="shadow add_new_shadow"><img class="fa" src="/themes/images/icons/Orion_bin.svg"></div>
                    </div>
                </li>
            </ul>
        </nav>';
        }
        $viewAll_1 = '<h1 class="d-none title-take">' . $oneShoppinglists->name . '</h1>';
        if (isset($oneShoppinglists->cost)){ $viewAll_2 = '<h2>CHF ' . $oneShoppinglists->cost . '</h2>';}else $viewAll_2 = '';
        $viewAll_3 = '<h3>' . $newDate = date("d.M.Y", strtotime($oneShoppinglists->date)) . ', ' . $oneShoppinglists->userName .' </h3>
        <ul class="products">{DETAIL_PRODUCTS}</ul>
        <form method="post" action="" >
            <select name="products" id="products">
                <option value="">-</option>
                {DETAIL_ADD_PRODUCTS}
            </select> 
            <input type="submit" name="submit" value="Add" ><br><br>
            <a class="notExist" href="?link=shoppinglists&act=missing">There\'s a product missing? Click here!</a>
        </form>';

        $result .= $viewAll_1 . $viewAll_2 . $viewAll_3;
        return $result;
    }


    /**
     * genereates view for editing a shoppinglist
     * @param $shoppinglist
     * @return string
     * @throws PDOException
     */
    function viewOneEdit($shoppinglist){
        $result = '<input type="text" name="name" placeholder="name" value="' . $shoppinglist->name . '" required><br>
            Cost:<br>
            CHF <input type="text" name="cost" placeholder="" value="' . $shoppinglist->cost . '"><br>';
        return $result;
    }




    /**
     * prints the overview with all shoppinglists
     * @param String $viewAll
     */
    public function printAll(String $viewAll) {
        //render Shoppinglists in overview
        $this->overview = file_get_contents('/var/www/html/application/ShoppingQueen/View/overview_view.html');
        $this->overview = str_replace('{OVERVIEW_SHOPPINGLISTS}', $viewAll, $this->overview);
        //render overview in index
        $this->goToSite($this->overview, '', '');
    }

    /**
     * prints detailview with shoppinglist and it's products
     *
     * @param String $viewOne
     * @param String $allProducts
     * @param String $allOthers
     */
    public function printOne(String $viewOne, String $allProducts, String $allOthers){
        //render detail from Shoppinglist
        $this->overview = file_get_contents('/var/www/html/application/ShoppingQueen/View/detail_view.html');
        $this->overview = str_replace('{DETAIL_CONTENT}', $viewOne, $this->overview);
        $this->overview = str_replace('{DETAIL_PRODUCTS}', $allProducts, $this->overview);
        $this->overview = str_replace('{DETAIL_ADD_PRODUCTS}', $allOthers, $this->overview);
        //render Shoppinglist in index
        $this->goToSite($this->overview, '', '');
    }

    /**
     * prints information from shoppinglist for editing
     * @param String $viewEdit
     * @param String $editProductView
     */
    public function printOneEdit(String $viewEdit, String $editProductView){
        //render detail from Shoppinglist
        $this->overview = file_get_contents('/var/www/html/application/ShoppingQueen/View/edit_view.html');
        $this->overview = str_replace('{EDIT_CONTENT}', $viewEdit, $this->overview);
        $this->overview = str_replace('{EDIT_PRODUCTS}', $editProductView, $this->overview);
        //render Shoppinglist in index
        $this->goToSite($this->overview, '', '');
    }



}