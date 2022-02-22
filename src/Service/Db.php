<?php
namespace Nosfair\Blogpost\Service;
use PDO;
use PDOException;
use Symfony\Component\Dotenv\Dotenv;
use Nosfair\Blogpost\Service\GlobalConstant;

$dotenv = new Dotenv();
$dotenv->load(\ROOT.'\blogpost\.env');
class Db extends PDO
{
    private static $instance;



    private function __construct()
    {
        $global = new GlobalConstant;
        // DSN of connexion
        $_dsn = 'mysql:dbname='. $global->Env('DBNAME') . ';host=' . $global->Env('DBHOST');

        // Call PDO class constructor
        try{
            parent::__construct($_dsn, $global->Env('DBUSER'), $global->Env('DBPASS'));

            $this->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES utf8');
            $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e){
            $e->getMessage;
        }
    }


    public static function getInstance():self
    {
        if(self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
