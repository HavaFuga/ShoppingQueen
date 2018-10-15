<?php
/**
 * Created by PhpStorm.
 * User: hava
 * Date: 10.09.18
 * Time: 11:43
 */

namespace core\Access\Model;

include_once '/var/www/html/core/Access/Controller/UserController.php';
include_once __DIR__ . '/../../../core/Model/SuperModel.php';

/**
 * Class User
 *
 * This class has all the statements for the table 'User' form the DB
 * @package core\Access\Model
 */
class User extends \core\Model\SuperModel
{
    /**
     * @var \ore\Access\Controller\UserController
     */
    protected $userController;
    protected $id;
    protected $name;
    protected $email;
    protected $password;
    protected $isAdmin;

    function __construct(int $id, string $name, string $email, string $password, bool $isAdmin)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->isAdmin = $isAdmin;
    }

    /**
     * Checks if email exists and if both passwords are the same
     * @param String $email
     * @param String $password
     * @param String $password2
     * @return bool
     * @throws PDOException
     */
    public function checkInputRegister(String $email, String $password, String $password2) {
        $userController = $this->userController;

        //get Emails form DB
        if (!$this->connectToDB()) {
            die('DB Connection error. UserController.php');
        } else {
            try {
                $conn = $this->connectToDB();
                $stmt = $conn->prepare('SELECT `email` FROM `User`;');
                $stmt->execute();

                // set the resulting array to associative
                $result = $stmt->fetchAll();
                $conn = null;
            }
            catch (\PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
            }
        }

        //compares email with attributes from DB
        foreach ($result as $db_email) {
            if ($email == $db_email[0]) {
                $isEmailTaken = false;
                break;
            } else {
                $isEmailTaken = true;
            }
        }

        //checks if passwords are the same
        if ($isEmailTaken == false) {
            $userController->goToSite('/var/www/html/core/Access/View/register_view.html', 'Sorry, this E-Mail is already taken!', false);
            return false;
        } elseif ($password != $password2) {
            $userController->goToSite('/var/www/html/core/Access/View/register_view.html', 'Passwords are not the same!', false);
            return false;
        } else {
            return true;
        }
    }


    /**
     * creates a new user
     * @throws PDOException
     */
    public function register() {
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $password = $_POST['password'];
        $password2 = $_POST['password2'];
        $userController = new UserController();

        //check Email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $userController->goToSite('/var/www/html/core/Access/View/register_view.html','Invalid email format', false);
        } else {
            if ($this->checkInputRegister($email, $password, $password2) == true) {
                //registers new user
                if (!$this->connectToDB()) {
                    die('DB Connection error. UserController.php');
                } else {
                    try {
                        $conn = $this->connectToDB();
                        $stmt = 'INSERT INTO `User`(`name`, `email`, `password`)
                                      VALUES ("' . $name . '", "' . $email .'", "' . sha1($password) . '");';
                        $conn->exec($stmt);
                        $userController->goToSite('/var/www/html/core/Access/View/register_view.html', 'Registration completed successfully!' , true);
                    }
                    catch (\PDOException $e) {
                        echo 'Connection failed: ' . $e->getMessage();
                    }
                    $conn = null;
                }
            }
        }

    }

    /**
     * gets all users that are admin
     * @return array with all the admins
     * @throws PDOException
     */
    public function admins() {
        if (!$this->connectToDB()) {
            die('DB Connection error. ProductController.php');
        } else {
            try {
                $conn = $this->connectToDB();
                $stmt = $conn->prepare('SELECT `id`, `name`, `email` FROM `User`
                                                  WHERE `isadmin` = 1;');
                $stmt->execute();

                // set the resulting array to associative
                $admins = $stmt->fetchAll();
                $conn = null;
            }
            catch (\PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
            }
            return $admins;
        }
    }
}