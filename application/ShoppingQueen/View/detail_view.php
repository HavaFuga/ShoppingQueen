<?php
/**
 * Created by PhpStorm.
 * User: hava
 * Date: 12.09.18
 * Time: 14:07
 */
namespace application\ShoppingQueen\View;
include_once $_SERVER['DOCUMENT_ROOT'] . '/application/ShoppingQueen/Controller/ShoppinglistController.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/application/ShoppingQueen/Controller/ProductController.php';
$shoppinglistController = new \application\ShoppingQueen\Controller\ShoppinglistController();
$productController = new \application\ShoppingQueen\Controller\ProductController();

ob_start();
include $_SERVER['DOCUMENT_ROOT'] . '/themes/header.php';

//get detail info from shoppinglist
$sid = $_GET['sid'];
$return =$shoppinglistController->getAll($sid);

//get products
$products = $productController->getAll();


echo '<h1 class="d-none title-take">Login</h1>
    <div class="col-sm boxes">
        <div class="color-boxes">
        <h2>' . $return[0][1]; if (!empty($return[0][3])){ echo ' - CHF' . $return[0][3];}
        echo '</h2></div>' .
            $return[0][2] . ', ' . $return[0][4] .'<br>';
        foreach ($products as $product){
            echo $product[0] . '<br>';
        }
    echo '</div>';

include $_SERVER['DOCUMENT_ROOT'] . '/themes/footer.php';
ob_end_flush();