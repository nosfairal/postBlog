<?php
namespace Nosfair\Blogpost\Classe;
use PDO;
use Nosfair\Blogpost\Classe\Config;

class DatabaseConnexion
{
	/**
	 * Connection to DB
	 *
	 * @return PDO
	 */
	public static function dbConnect(): PDO
	{
		return new PDO("mysql:host=" . Config::DB_HOST . ";
		port=" . Config::DB_PORT . ";dbname=" . Config::DB_NAME . ";
		charset=utf8", Config::DB_USERNAME, Config::DB_PASSWORD);
	}
}