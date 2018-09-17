<?php
/**
 * Created by PhpStorm.
 * User: hava
 * Date: 10.09.18
 * Time: 16:44
 */
namespace application\ShoppingQueen\Controller;

include_once __DIR__ . '/../../../core/Controller/SuperController.php';
include_once __DIR__ . '/../View/ShoppinglistView.php';

class ShoppinglistController extends \core\Controller\SuperController
{
    //Get all Shoppinglists from DB
    function getAll(){
        if (!$this->connectToDB()){
            die('DB Connection error. ShoppinglistController.php');
        }else {
            try{
                $conn = $this->connectToDB();
                $stmt = $conn->prepare('SELECT s.id, s.name, s.date, s.cost, u.name FROM Shoppinglist AS s, User AS u WHERE s.uid = u.id;');
                $stmt->execute();

                // set the resulting array to associative
                $result = $stmt->fetchAll();
                $conn = null;
            }
            catch(PDOException $e){
                echo 'Connection failed: ' . $e->getMessage();
            }
            return $result;
        }
    }

    //Get one Shoppinglist from DB
    function getOne($id){
        if (!$this->connectToDB()){
            die('DB Connection error. ShoppinglistController.php');
        }else {
            try{
                $conn = $this->connectToDB();
                $stmt = $conn->prepare('SELECT s.id, s.name, s.date, s.cost, u.name 
                                                  FROM Shoppinglist AS s, User AS u 
                                                  WHERE s.uid = u.id AND s.id = ' . $id .';');
                $stmt->execute();

                // set the resulting array to associative
                $result = $stmt->fetch();
                $conn = null;
            }
            catch(PDOException $e){
                echo 'Connection failed: ' . $e->getMessage();
            }
            return $result;
        }
    }

    //prints all Shoppinglists
    protected $overview;
    function printAll($viewAll) {
        //render Shoppinglists in overview
        $this->overview = file_get_contents('/var/www/html/application/ShoppingQueen/View/overview_view.html');
        $this->overview = str_replace('{OVERVIEW_SHOPPINGLISTS}', $viewAll, $this->overview);
        //render overview in index
        $this->goToSite($this->overview);
    }
    /*protected $overview;
    protected $overview2;
    function printAll($viewAll) {
        //render Shoppinglists in overview
        $this->overview = file_get_contents('/var/www/html/application/ShoppingQueen/View/overview_view.html');
        $this->overview = str_replace('{OVERVIEW_SHOPPINGLISTS}', $viewAll, $this->overview);
        $this->overview2 = file_put_contents($this->overview2, $this->overview);
        //render overview in index
        $this->goToSite($this->overview2);
    }*/
}
$shoppinglistController = new ShoppinglistController();
$allShoppinglists = $shoppinglistController->getAll();
$shoppinglistView = new \application\ShoppingQueen\View\ShoppinglistView();
$viewAll = $shoppinglistView->viewAll($allShoppinglists);
$shoppinglistController->printAll($viewAll);

