<?php
function generateRandomString($length = 10): string
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

class Combine
{
    public static function path($base, $com = null, $isReal = false)
    {
        if(substr($base, -1)!=DIRECTORY_SEPARATOR) $base.=DIRECTORY_SEPARATOR;
        if($com) $base.=$com;
        $base = preg_replace('/(\/+|\\\\+)/', DIRECTORY_SEPARATOR, $base);
        while(preg_match('/(\/[\w\s_-]+\/\.\.)/', $base)){
            $base = preg_replace('/(\/[\w\s_-]+\/\.\.)/', "", $base);
            if(preg_match('/\/\.\.\//', $base))
                throw new \Exception("Error directory don't have parent folder!", 1);
        }
        if($isReal){
            $base = realpath($base);
            if(is_dir($base)) $base .= DIRECTORY_SEPARATOR;
        }
        return $base;
    }
}

