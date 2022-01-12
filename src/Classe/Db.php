<?php
namespace Nosfair\Blogpost\Classe;
use PDO;
use Nosfair\Blogpost\Classe\Config;
use PDOException;

class Db extends PDO
{
    private static $instance;

    // Informations de connexion
    private const DBHOST = 'localhost';
    private const DBUSER = 'root';
    private const DBPASS = '';
    private const DBNAME = 'postblog';

    private function __construct()
    {
        // DSN of connexion
        $_dsn = 'mysql:dbname='. self::DBNAME . ';host=' . self::DBHOST;

        // Call PDO class constructor
        try{
            parent::__construct($_dsn, self::DBUSER, self::DBPASS);

            $this->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES utf8');
            $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e){
            die($e->getMessage());
        }
    }


    public static function getInstance():self
    {
        if(self::$instance === null){
            self::$instance = new self();
        }
        return self::$instance;
    }
}