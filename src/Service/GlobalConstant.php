<?php
namespace Nosfair\Blogpost\Service;


class GlobalConstant
{

    public static function issetPost(string $key)
    {
        return isset($_POST[$key]);
    }

    public static function issetGet(string $key)
    {
        return isset($_GET[$key]);
    }

    public static function Post(string $key)
    {
        return ($_POST[$key]);
    }

    public static function notEmptyPost()
    {
        return !empty($_POST);
    }

    public static function Env(string $key)
    {
        return ($_ENV[$key]);
    }

}