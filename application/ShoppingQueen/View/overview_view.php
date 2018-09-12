<?php
/**
 * Created by PhpStorm.
 * User: hava
 * Date: 10.09.18
 * Time: 16:18
 */
namespace application\ShoppingQueen\View;
include $_SERVER['DOCUMENT_ROOT'] . '/application/ShoppingQueen/Controller/ShoppinglistController.php';
$shoppinglistController = new \application\ShoppingQueen\Controller\ShoppinglistController();

ob_start();
include $_SERVER['DOCUMENT_ROOT'] . '/themes/header.php';
$return =$shoppinglistController->getAll();

echo '    <h1 class="d-none title-take">Shoppinglists</h1>';
echo '<div class="row">';
foreach ($return as $list) {
    echo '<div class="col-sm"><a href="/application/ShoppingQueen/View/detail_view.php?sid=' . $list[0] .'"><div class="col-sm boxes">
        <div class="color-boxes">
        <h2>' . $list[1]; if (!empty($list[3])){ echo ' - CHF' . $list[3];}
        echo '</h2></div>' .
        $list[2] . ', ' . $list[4] .'
        </div></a></div>';
}
echo '</div>';
include $_SERVER['DOCUMENT_ROOT'] . '/themes/footer.php';



ob_end_flush();