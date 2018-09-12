<?php
/**
 * Created by PhpStorm.
 * User: hava
 * Date: 10.09.18
 * Time: 15:51
 */
namespace themes;

echo '<!DOCTYPE html>
            <html>
            
            <head>
              <meta charset="utf-8">
              <title></title>
              <meta name="author" content="">
              <meta name="description" content="">
              <meta name="viewport" content="width=device-width, initial-scale=1">
              
              <!--Favicon-->
              <link rel="shortcut icon" type="image/png" href="/themes/images/favicon.ico"/>
              
              <!--Fonts-->
              <link href="https://fonts.googleapis.com/css?family=Josefin+Sans:300" rel="stylesheet">
              <link href="/themes/css/fonts.css" rel="stylesheet">
              <!--CSS-->
              <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
              <link href="/themes/css/clx-dropdown-navigation.css" rel="stylesheet">
              <link href="/themes/css/style.css" rel="stylesheet">
              

            </head>
            
            <body>
                <header>
                    <div class="container">      
                        <div class="c7n-logo"><a href="/themes/home.php">
                            <img src="/themes/images/logo/logo_white_small.png" /> </a></div>
                            <nav id="clx-dropdown-navigation">
                                <ul>
                                    <li class="level-1">
                                        <a class="level-1" href="/application/ShoppingQueen/View/overview_view.php" target="_self">Shoppinglists</a>
                                        <div class="c7n-icon" onclick="location.href=\'/application/ShoppingQueen/View/overview_view.php\'">
                                            <div class="shadow"><img class="fa" src="/themes/images/icons/Orion_royal.png"></div>
                                        </div>
                                    </li>
                                    <li class="level-1">
                                            <a class="level-1" href="/core/Access/View/login_view.php" target="_self">Login</a>
                                            <div class="c7n-icon" onclick="location.href=\'/core/Access/View/login_view.php\'">
                                                <div class="shadow"><img class="fa" src="/themes/images/icons/Orion_back-arrow.png"></div>
                                            </div>
                                    </li>
                                    <li class="level-1">
                                            <a class="level-1" href="/core/Access/View/login_view.php" target="_self">Logout</a>
                                            <div class="c7n-icon" onclick="location.href=\'/core/Access/View/login_view.php\'">
                                                <div class="shadow"><img class="fa" src="/themes/images/icons/Orion_back-arrow.png"></div>
                                            </div>
                                    </li>
                                </ul>  
                            </nav>   
                    </div>      
                </header>
                <section id="c7n-slider" class="c7n-small">
                    <div class="c7n-slide">  
                        <img src="/themes/images/denim-jeans.jpg" />  
                    </div>
                    <div class="c7n-intro">
                        <h1>Title</h1>  
                    </div>
                </section> 
                <div id="content" class="container">';

