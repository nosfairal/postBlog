<?php
namespace Nosfair\Blogpost\Service;


class GlobalConstant
{

    public static function issetPost(string $key)
    {
        return isset($_POST[$key]);
    }

}