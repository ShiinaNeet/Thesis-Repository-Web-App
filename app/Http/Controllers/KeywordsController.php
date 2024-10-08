<?php

namespace App\Http\Controllers;

use App\Libraries\SharedFunctions;
use App\Models\keywords;
use Illuminate\Http\Request;

class KeywordsController extends Controller
{
    public function delete(Request $request)
    {
        $rs = SharedFunctions::default_msg();
        $query = keywords::find($request->id);
        if ($query->forceDelete()) {
            $rs = SharedFunctions::success_msg("Keyword deleted");
    
        }
        return response()->json($rs);
    }

    public function disable(Request $request)
    {
        $rs = SharedFunctions::default_msg();
        
        $query = keywords::find($request->id);

        if ($query->delete()) {
            $rs = SharedFunctions::success_msg('Keyword disabled');
            
        }

       
        return response()->json($rs);
    }
    public function enable(Request $request)
    {
        $rs = SharedFunctions::default_msg();
        
        keywords::withTrashed()->find($request->id)->restore();

        $rs = SharedFunctions::success_msg('Keyword enabled');
        return response()->json($rs);
    }

    public function get()
    {
        $query = keywords::withTrashed()
            ->orderBy('created_at', 'DESC')
            ->get();

        $rs = SharedFunctions::success_msg('Success');
        $rs['result'] = $query;
        return response()->json($rs);
    }

    public function get_sorted($sort_by)
    {
        $query = keywords::withTrashed()
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
            'keyword' => 'required|max:120'
        ]);
        $new_help = false;
        if (isset($request->id)) $keyword = keywords::find($request->id);
        else { $keyword = new keywords(); $new_help = true; }
        $keyword->keyword = $request->keyword;
    
        if ($keyword->save()) {
            $rs = SharedFunctions::success_msg("Keywords saved");
        }
        return response()->json($rs);
    }
}
