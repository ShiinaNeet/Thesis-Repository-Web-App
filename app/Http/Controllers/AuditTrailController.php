<?php

namespace App\Http\Controllers;

use App\Libraries\SharedFunctions;
use App\Models\AuditTrail;
use Illuminate\Http\Request;

class AuditTrailController extends Controller
{
    public function get()
    {
        $query = AuditTrail::leftJoin('users', 'audit_trails.action_user_id', '=', 'users.id')
            ->select('users.userID','users.user_type')
            ->addSelect('audit_trails.module', 'audit_trails.action_type', 'audit_trails.created_at')
            ->orderBy('audit_trails.created_at', 'DESC')->get();
        $rs = SharedFunctions::success_msg('Success');
        $rs['result'] = $query;
        return response()->json($rs);
    }
}
