<?php

namespace App\Libraries;


use Illuminate\Support\Facades\Auth;

class SharedFunctions
{
    public static function default_msg()
    {
        return [
            'status'    => 0,
            'title'     => "Oops!",
            'text'      => "Something went wrong",
            'type'      => 'error'
        ];
    }

    public static function success_msg($message = "Success")
    {
        return [
            'status'    => 1,
            'title'     => "Success!",
            'text'      => $message,
            'type'      => 'success'
        ];
    }

    public static function prompt_msg($message = "Invalid")
    {
        return[
            'status' => 0,
            'title' => 'Oops!',
            'text' => $message,
            'type' => 'error'
        ];
    }

    public static function array_contains(array $arr, $str)
    {
        foreach($arr as $a) {
            if (stripos($a, $str) !== false) return true;
        }
        return false;
    }

  
    
}
