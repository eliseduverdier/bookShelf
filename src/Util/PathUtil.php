<?php

namespace App\Util;

class PathUtil
{
    // hack to upload the site on a subfolder in my web host
    public static function getRootPath(): string
    {
        if (!array_key_exists('SERVER_NAME', $_SERVER)) {
            return '/';
        }

        if (in_array($_SERVER['SERVER_NAME'], ['127.0.0.1', 'localhost'])) {
            return '/';
        } else {
            return '/bookshelf/';
        }
    }
}
