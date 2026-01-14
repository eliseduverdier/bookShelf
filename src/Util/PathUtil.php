<?php

namespace App\Util;

class PathUtil
{
    static public function getRootPath(): string
    {
        // hack to upload the site on a subfolder in my web host
        if (in_array($_SERVER['SERVER_NAME'], ['127.0.0.1', 'localhost'])) {
            return '/';
        } else {
            return '/bookshelf/';
        }
    }
}
