<?php
namespace Nosfair\Blogpost\Service;


class GlobalConstant
{
    /**
     * Verify existence of a £_POST
     */
    public static function issetPost(string $key)
    {
        return isset($_POST[$key]);
    }

    /**
     * Verify existence of a GET
     */
    public static function issetGet(string $key)
    {
        return isset($_GET[$key]);
    }

    /**
     * Return $_POST and his value
     */
    public static function Post(string $key)
    {
        return ($_POST[$key]);
    }

    /**
     * Return an none empty $_POST
     */
    public static function notEmptyPost()
    {
        return !empty($_POST);
    }

    /**
     * Return $_ENV and his value
     */
    public static function Env(string $key)
    {
        return ($_ENV[$key]);
    }

    /**
     * Return the array $_POST
     */
    public function collectInput(): ?array
    {

    return $_POST;
    }

}