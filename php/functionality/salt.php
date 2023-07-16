<?php

function salt($value, $salt){
    $c = 0;
    foreach (mb_str_split($salt) as $chr){
        $p2 = (1 - ($c % 2)*2);
        $p = $c * $p2;
        $p = $p % strlen($value);
        if ($p >= 0)
            $value = substr($value,0,$p).$chr.substr($value,$p,strlen($value) - $p);
        else
            $value = substr($value,0, strlen($value) + $p).$chr.substr($value,strlen($value) + $p,strlen($value) - $p);
        $c++;
    }
    return $value;
}