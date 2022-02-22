<?php
namespace Nosfair\Blogpost\Service;
class Session
{

	protected $flash = [];
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

	public function get(string $key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }

	/**
	 * 
	 */
	public function getFlashMessage(string $key, $default = null) {
        if (!isset($this->flash[$key])) {
            $message = $this->get($key, $default);
            unset($_SESSION[$key]);
            $this->flash[$key] = $message;
        }

        return $this->flash[$key];
    }
	public static function redirect(string $url) {
        header('location: '.$url);
    }

	/**
	 * Obtain $_SESSION
	 */
	public static function getGlobalSession()
	{
		return $_SESSION;
	}

	public static function issetSession(string $key)
	{
		return isset($_SESSION[$key]);
	}

	public static function notEmptySession(string $key)
    {
        return !empty($_SESSION[$key]);
    }
}