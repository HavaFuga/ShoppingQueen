<?php

namespace core\Access\View;
require_once __DIR__ . '/../Controller/UserController.php';
ob_start();
include $_SERVER['DOCUMENT_ROOT'] . '/themes/header.php';

if (isset($_SESSION['user'])) {
    // already logged in
    echo 'You\'re already logged in';
} else {// not logged in

    //login form
    echo '    <h1 class="d-none title-take">Login</h1>
              <form method="post" action="">
              E-Mail:<br>
              <input type="text" name="email" placeholder="harry@mail.com">
              <br>
              Password:<br>
              <input type="password" name="password" value="">
              <br><br>
              <input type="submit" value="Submit">
            </form> ';
}
    $input_email = $_POST['email'];
    $input_password = $_POST['password'];

echo ($_SESSION['user']);
    if (isset($input_email) && isset($input_password)){

        //check Email
        if (!filter_var($input_email, FILTER_VALIDATE_EMAIL)) {
            echo ('Invalid email format');
        }else{
            $userController = new \application\ShoppingQueen\Controller\UserController();
            $userController->checkInput($input_email, $input_password);
        }
    }

$_SESSION['user']='harry';
    echo $_SESSION['user'];





include $_SERVER['DOCUMENT_ROOT'] . '/themes/footer.php';
ob_end_flush();