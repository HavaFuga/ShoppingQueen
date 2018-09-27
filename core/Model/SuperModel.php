<?php
/**
 * Created by PhpStorm.
 * User: hava
 * Date: 07.09.18
 * Time: 14:18
 */
namespace core\Model;

/**
 * Class SuperModel
 * @package core\Model
 */
class SuperModel
{
    protected $connection;

    /**
     * creates a connection to the DB
     * @return \PDO connection
     * @throws PDOException
     */
    protected function connectToDB() {

        if (!is_null($this->connection)) {
            return $this->connection;
        }

        $servername = 'db';
        $username = 'shoppingQueen';
        $password = 'Hallo123';
        $dbname = 'ShoppingQueen';

        try {
            $connection = new \PDO('mysql:host=' . $servername .';dbname=' . $dbname, $username , $password,
                array(\PDO::MYSQL_ATTR_MULTI_STATEMENTS  => false));
            // set the PDO error mode to exception
            $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            //echo 'Connected successfully';
            $this->connection = $connection;
            return $this->connection;
        }
        catch (\PDOException $e)
        {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

}