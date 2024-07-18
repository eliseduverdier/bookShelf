<?php

namespace App\Util;

class PathUtil
{
    static public function getRootPath(): string
    {
        if ($_SERVER['SERVER_NAME'] === '127.0.0.1') {
            return '/';
        } else {
            return '/bookshelf/';
        }
    }
}
