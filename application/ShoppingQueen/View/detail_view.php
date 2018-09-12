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
$return =$shoppinglistController->getOne($sid);
echo $return[0][1];

//get products
$products = $productController->getAll($sid);


echo '<h1 class="d-none title-take">' . $return[1] . '</h1>';
if (isset($return[3])){ echo '<h2>CHF ' . $return[3] . '</h2>';}
        echo '<h3>' . $return[2] . ', ' . $return[4] .' </h3>
        <ul class="products">';
        foreach ($products as $product){
            echo '<li><img src="/themes/images/icons/Orion_angle-right_pink.png">' . $product[0] . '</li>';
        }
        echo '</ul>';
include $_SERVER['DOCUMENT_ROOT'] . '/themes/footer.php';
ob_end_flush();