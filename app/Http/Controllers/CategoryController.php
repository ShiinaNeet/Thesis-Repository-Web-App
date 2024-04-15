<?php

namespace App\Http\Controllers;

use App\Libraries\SharedFunctions;
use App\Models\AuditTrail;
use App\Models\category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function delete(Request $request)
    {
        $rs = SharedFunctions::default_msg();
        $query = category::find($request->id);
        if ($query->forceDelete()) {
            $rs = SharedFunctions::success_msg("Category deleted");
            SharedFunctions::create_audit_log(
                AuditTrail::MODULE_CATEGORY, AuditTrail::ACTION_DELETE,
            );
        }
        return response()->json($rs);
    }

    public function disable(Request $request)
    {
        $rs = SharedFunctions::default_msg();
        
        $query = category::find($request->id);

        if ($query->delete()) {
            $rs = SharedFunctions::success_msg('Category disabled');
            SharedFunctions::create_audit_log(
                AuditTrail::MODULE_CATEGORY, AuditTrail::ACTION_DISABLE,
            );
        }

       
        return response()->json($rs);
    }
    public function enable(Request $request)
    {
        $rs = SharedFunctions::default_msg();
        
        category::withTrashed()->find($request->id)->restore();

        $rs = SharedFunctions::success_msg('Category enabled');
        SharedFunctions::create_audit_log(
            AuditTrail::MODULE_CATEGORY, AuditTrail::ACTION_ENABLE,
        );
        return response()->json($rs);
    }

    public function get()
    {
        $query = category::withTrashed()
            ->orderBy('created_at', 'DESC')
            ->get();

        $rs = SharedFunctions::success_msg('Success');
        $rs['result'] = $query;
        return response()->json($rs);
    }

    public function get_sorted($sort_by)
    {
        $query = category::withTrashed()
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
            'category' => 'required|max:120',
        ]);
        $new_help = false;
        if (isset($request->id)) $category = category::find($request->id);
        else { $category = new category(); $new_help = true; }
        $category->category = $request->category;
    
        if ($category->save()) {
            $rs = SharedFunctions::success_msg("Category saved");
            SharedFunctions::create_audit_log(
                AuditTrail::MODULE_CATEGORY,  $new_help == true ? AuditTrail::ACTION_CREATE : AuditTrail::ACTION_UPDATE,
            );
        }
        return response()->json($rs);
    }
}
