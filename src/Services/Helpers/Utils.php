<?php

namespace App\Services\Helpers;

class Utils
{
    static public function slugify(string $str): string
    {
        $str = strtolower($str);
        $str = str_replace(mb_str_split('\'"«»“”‘’  &/()[]{}:_'), '-', $str);
        $str = str_replace( // strotolower doesn’t replace accented capitals
            mb_str_split('àâäÀÂÄéèëêÉÈÊËîïÎÏôöÔÖùûüÙÛÜçÇ'),
            mb_str_split('aaaaaaeeeeeeeeiiiioooouuuuuucc'),
            $str
        );
        return preg_replace('/[^a-z-]+/', '', $str);
    }

}
