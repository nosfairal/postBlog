<?php
namespace Nosfair\Blogpost\Service;
class Session
{
    /**
	 * Affect a value to asked SuperGlobal $_SESSION
	 *
	 * @param string $key
	 * @param $value
	 *
	 */
	public static function put(string $key, $value)
	{
		return $_SESSION[$key] = $value;
	}

    /**
	 * Unset asked SuperGlobal $_SESSION
	 *
	 * @param string $key
	 *
	 * @return void
	 */
	public static function forget(string $key): void
	{
		unset($_SESSION[$key]);
	}
}