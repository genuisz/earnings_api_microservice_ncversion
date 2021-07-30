<?php
use Illuminate\Support\Str;
use ReverseRegex\Lexer;
use ReverseRegex\Random\SimpleRandom;
use ReverseRegex\Parser;
use ReverseRegex\Generator\Scope;

function generateRandomStringDigit($charLength , $length){
    
    $lexer = new  Lexer('[a-z]{'.$charLength.'}\d{'.$length.'}');
    $result ='';
    $parser = new Parser($lexer,new Scope(),new Scope());
    $parser->parse()->getResult()->generate($result,1);
    dd($result);
   
}

  function quickRandom($length = 16)
{
    $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
}


function quickRandomMultiple($length = 16,$num)
{
    $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $array = array();
    foreach($num as $index){
      array_push($array,substr(str_shuffle(str_repeat($pool, 5)), 0, $length));
    }
    return $array;
}



