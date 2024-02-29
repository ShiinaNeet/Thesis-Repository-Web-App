<?php

namespace App\Http\Controllers;

use App\Libraries\SharedFunctions;
use App\Models\thesis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ThesisController extends Controller
{
    public function delete(Request $request)
    {
        $rs = SharedFunctions::default_msg();
        $query = thesis::find($request->id);
        if ($query->forceDelete()) {
            $rs = SharedFunctions::success_msg("Thesis deleted");
    
        }
        return response()->json($rs);
    }

    public function disable(Request $request)
    {
        $rs = SharedFunctions::default_msg();
        
        $query = thesis::find($request->id);

        if ($query->delete()) {
            $rs = SharedFunctions::success_msg('Thesis disabled');
            
        }

       
        return response()->json($rs);
    }
    public function enable(Request $request)
    {
        $rs = SharedFunctions::default_msg();
        
        thesis::withTrashed()->find($request->id)->restore();

        $rs = SharedFunctions::success_msg('Thesis enabled');
        return response()->json($rs);
    }

    public function get()
    {
        $query = thesis::withTrashed()
            ->orderBy('created_at', 'DESC')
            ->get();

        $rs = SharedFunctions::success_msg('Success');
        $rs['result'] = $query;
        return response()->json($rs);
    }

    public function get_sorted($sort_by)
    {
        $query = thesis::withTrashed()
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
            'title' => 'required|max:120',
            'abstract' => 'required|max:500',
            'published_at' => 'required|date',
            
            
        ]);
        //'video' => 'required|file|mimes:mp4,mov,avi,wmv',
        //'pdf' => 'required|file|mimes:pdf'
        $new_help = false;
        if (isset($request->id)) $thesis = thesis::find($request->id);
        else { $thesis = new thesis(); $new_help = true; }
        $thesis->title = $request->title;
        $thesis->abstract = $request->abstract;
        $thesis->published_at = $request->published_at;
        
        if ($request->hasFile('video') && $request->file('video')->isValid()) {
            $file = $request->file('video');
            $fileName = $file->getClientOriginalName(); 
            $path = $file->storeAs('videos', $fileName); 
            $thesis->video = $path; 
        }
        if ($request->hasFile('pdf') && $request->file('pdf')->isValid()) {
            $file = $request->file('pdf');
            $fileName = $file->getClientOriginalName(); 
            $path = $file->storeAs('pdf', $fileName); 
            $thesis->pdf = $path; 
        }
    
        if ($thesis->save()) {
            $rs = SharedFunctions::success_msg("Thesis saved");
        }
        return response()->json($rs);
    }

    public function getVideo($videoname)
    {
        $videoPath = 'videos/' . $videoname; // Adjust this path according to your storage configuration
        
        if (Storage::exists($videoPath)) {
            // If the video file exists, return the response
            return response()->file(storage_path('app/' . $videoPath));
        } else {
            // If the video file does not exist, return a 404 response
            return response()->json(['error' => 'Video not found'], 404);
        }
    }
    public function getPdf($pdfName)
    {
        $pdfPath = 'pdf/' . $pdfName; // Adjust this path according to your storage configuration
        
        if (Storage::exists($pdfPath)) {
            // If the video file exists, return the response
            return response()->file(storage_path('app/' . $pdfPath));
        } else {
            // If the video file does not exist, return a 404 response
            return response()->json(['error' => 'pdf not found'], 404);
        }
    }
}
