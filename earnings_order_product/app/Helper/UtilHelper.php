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

 function jsonParaChecker($array,$paraArray,bool $partialKey){
  $keyset = array_values($paraArray);

  $result = true;
  
  foreach($array as $element){
      //dd(count(array_intersect(array_keys($element),$keyset)));
      if(!$partialKey){
          // dump(array_keys($element));
          // dump($keyset);
          if(!count(array_intersect(array_keys($element),$keyset))===count($keyset)){
              $result = false;
          }
      }
      else{
          $elementKey = array_keys($element);
          $interact = array_intersect($elementKey,$keyset);
          if(count($interact)<=count($keyset) && $interact==$elementKey){
              
          }
          else{
              $result = false;
          }
      }

  }
  return $result;
}




