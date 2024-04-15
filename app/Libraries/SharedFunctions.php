<?php

namespace App\Libraries;

use App\Models\AuditTrail;
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

    public static function create_audit_log($module, $action_type)
    {
        $query                     = new AuditTrail();
        $query->action_user_id     = Auth::id() ? Auth::id() : AuditTrail::USER_SYSTEM;
        $query->module           = $module;
        $query->action_type        = $action_type;
 
        $query->save();
    }
    
}
