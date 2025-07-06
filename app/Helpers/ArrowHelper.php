<?php
namespace App\Helpers;

class ArrowHelper
{
    public static function findArrow($age, $highest): string
    {
        if($age<=14){
            return $arrow= match (true) {
                $highest >= 63 && $highest <= 72 => "<span style='color: green;'>▲</span>",
                $highest >= 43 && $highest < 63 => "<span style='color: gray;'>►</span>",
                $highest >= 34 && $highest < 43 => "<span style='color: red;'>▼</span>",
                default => "<span style='color: red;'>▼</span>",//"<span style=\'color: gray;\'>►</span>",
            };
        }
        else if($age>=15 && $age<=16){
            return $arrow= match (true) {
                $highest >= 68 && $highest <= 76 => "<span style='color: green;'>▲</span>",
                $highest >= 49 && $highest < 68 => "<span style='color: gray;'>►</span>",
                $highest >= 40 && $highest < 49 => "<span style='color: red;'>▼</span>",
                default => '',//"<span style=\'color: gray;\'>►</span>",
            };
        }

        else if($age>=17 && $age<=18){
            return $arrow= match (true) {
                $highest >= 72 && $highest <= 79 => "<span style='color: green;'>▲</span>",
                $highest >= 56 && $highest < 72 => "<span style='color: gray;'>►</span>",
                $highest >= 49 && $highest < 56 => "<span style='color: red;'>▼</span>",
                default => '',//"<span style=\'color: gray;\'>►</span>",
            };
        }
        else{ //if($age>=19 && $age<=25){
            return $arrow= match (true) {
                $highest >= 73 && $highest <= 79 => "<span style='color: green;'>▲</span>",
                $highest >= 59 && $highest < 73 => "<span style='color: gray;'>►</span>",
                $highest >= 52 && $highest < 59 => "<span style='color: red;'>▼</span>",
                default => '',//"<span style=\'color: gray;\'>►</span>",
            };
        }

        //return $arrow;
    }


}
