<?php
/**
 * Created by PhpStorm.
 * User: hava
 * Date: 14.09.18
 * Time: 08:20
 */

namespace core\Access\View;
require_once __DIR__ . '/../Controller/UserController.php';
ob_start();
include $_SERVER['DOCUMENT_ROOT'] . '/themes/header.php';


    // register form
echo '    <h1 class="d-none title-take">Login</h1>
          <form method="post" action="">
          Name:<br>
          <input type="text" name="name" placeholder="harry">
          <br>
          E-Mail:<br>
          <input type="text" name="email" placeholder="harry@mail.com">
          <br>
          Password:<br>
          <input type="password" name="password" value="">
          <br>
          Repeat password:<br>
          <input type="password" name="password2" value="">
          <br><br>
          <input type="submit" value="Submit">
          </form> ';

$input_name = $_POST['name'];
$input_email = $_POST['email'];
$input_password = $_POST['password'];
$input_password2 = $_POST['password2'];

$userController = new \application\ShoppingQueen\Controller\UserController();

include $_SERVER['DOCUMENT_ROOT'] . '/themes/footer.php';
ob_end_flush();