<?php

namespace App\Http\Controllers;

use App\Libraries\SharedFunctions;
use App\Models\AuditTrail;
use App\Models\authors;
use Illuminate\Http\Request;

class AuthorsController extends Controller
{
    public function delete(Request $request)
    {
        $rs = SharedFunctions::default_msg();
        $query = authors::find($request->id);
        if ($query->forceDelete()) {
            $rs = SharedFunctions::success_msg("Authors deleted");
            SharedFunctions::create_audit_log(
                AuditTrail::MODULE_AUTHORS, AuditTrail::ACTION_DELETE,
            );
        }
        return response()->json($rs);
    }

    public function disable(Request $request)
    {
        $rs = SharedFunctions::default_msg();
        
        $query = authors::find($request->id);

        if ($query->delete()) {
            $rs = SharedFunctions::success_msg('Authors disabled');
            SharedFunctions::create_audit_log(
                AuditTrail::MODULE_AUTHORS, AuditTrail::ACTION_DISABLE,
            );
        }

       
        return response()->json($rs);
    }
    public function enable(Request $request)
    {
        $rs = SharedFunctions::default_msg();
        
        authors::withTrashed()->find($request->id)->restore();

        $rs = SharedFunctions::success_msg('Authors enabled');
        SharedFunctions::create_audit_log(
            AuditTrail::MODULE_AUTHORS, AuditTrail::ACTION_ENABLE,
        );

        return response()->json($rs);
    }

    public function get()
    {
        $query = authors::withTrashed()
            ->orderBy('created_at', 'DESC')
            ->get();

        $rs = SharedFunctions::success_msg('Success');
        $rs['result'] = $query;
        return response()->json($rs);
    }

    public function get_sorted($sort_by)
    {
        $query = authors::withTrashed()
            ->orderBy('created_at', 'DESC')
            ->get();
       
        $rs = SharedFunctions::success_msg('Success');
        $rs['result'] = $query;
        return response()->json($rs);
    }

    public function save(Request $request)
    {
        $rs = SharedFunctions::default_msg();
        

        $this->validate($request, [
            'name' => 'required|max:120',
        ]);
        $new_help = false;
        if (isset($request->id)) $author = authors::find($request->id);
        else { $author = new authors(); $new_help = true; }
        $author->name = $request->name;
    
        if ($author->save()) {
            $rs = SharedFunctions::success_msg("Authors saved");
            SharedFunctions::create_audit_log(
                AuditTrail::MODULE_AUTHORS,  $new_help == true ? AuditTrail::ACTION_CREATE : AuditTrail::ACTION_UPDATE,
            );
        }
        return response()->json($rs);
    }
}
