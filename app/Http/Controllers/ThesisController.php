<?php

namespace App\Http\Controllers;

use App\Libraries\SharedFunctions;
use App\Models\thesis;
use App\Models\authors;
use App\Models\category;
use App\Models\keywords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class ThesisController extends Controller
{
    public function search(Request $request){
        $thesis = Thesis::withTrashed()
            ->with(['author', 'category', 'keywords'])
            ->where(function($query) use ($request) {
                $query->where('title', 'like', '%' . $request->searchkeywords . '%')
                    ->orWhere('category.name', 'like', '%' . $request->searchkeywords . '%')
                    ->orWhere('author.name', 'like', '%' . $request->searchkeywords . '%')
                    ->orWhere('keywords.keyword', 'like', '%' . $request->searchkeywords . '%');

                // Dynamically add conditions for other columns if needed
                $columns = Schema::getColumnListing('thesis');
                foreach ($columns as $column) {
                    $query->orWhere($column, 'like', '%' . $request->searchkeywords . '%');
                }
            })
            ->get();
        $rs = SharedFunctions::success_msg('Success');
        $rs['result'] = $thesis;
        return response()->json($rs);
    }
    public function getThesisById($id){

        $thesis = thesis::withTrashed()
            ->with(['author', 'category', 'keywords'])
            ->where('id','=',$id)
            ->get();
        $rs = SharedFunctions::success_msg('Success');
        $rs['result'] = $thesis;
        return response()->json($rs);
    }
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
            ->with(['author', 'category', 'keywords'])
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
       
        // dd($request->all());
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
        
        if ($request->has('published_at')) {
            $publishedAt = $request->published_at;
            // Convert date string to a format MySQL understands
            $publishedAt = date('Y-m-d H:i:s', strtotime($publishedAt));
        
            // Check if $publishedAt is not null and is a valid string
            if ($publishedAt !== false) {
                // Assuming $thesis is an instance of your Eloquent model
                $thesis->published_at = $publishedAt;
                // Save the changes to the database
            } else {
                // Handle case where published_at is not in the correct format
                $rs = SharedFunctions::prompt_msg("Published at date is not in the correct format");
            }
        }
        
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
    
        

        // Attach authors to the thesis
        if ($request->has('authors')) {
            $authors = json_decode($request->authors); // Decode JSON string into an array
            if (is_array($authors)) { // Check if it's an array
                DB::table('thesis_author')->where('thesis_id', $thesis->id)->delete();
                foreach ($authors as $authorId) {
                    
                    DB::table('thesis_author')->insert([
                        'thesis_id' => $thesis->id,
                        'author_id' => $authorId
                    ]);
                }
            } else {
                // Handle case where authors is not in the correct format
               
                $rs =   SharedFunctions::prompt_msg("Authors data is not in the correct format");
            }
        }
        

        if ($request->has('keywords')) {
            $keywords = json_decode($request->keywords); // Decode JSON string into an array
            if (is_array($keywords)) { // Check if it's an array
                DB::table('thesis_keyword')->where('thesis_id', $thesis->id)->delete();
                foreach ($keywords as $keywordId) {
                    DB::table('thesis_keyword')->insert([
                        'thesis_id' => $thesis->id,
                        'keyword_id' => $keywordId
                    ]);
                }
            } else {
                // Handle case where keywords is not an array
                // return response()->json(['error' => 'Keywords data is not in the correct format'], 400);
                $rs = SharedFunctions::prompt_msg("Keywords data is not in the correct format");
            }
        }
        
        if ($request->has('categories')) {
            $categories = json_decode($request->categories); // Decode JSON string into an array
            if (is_array($categories)) { // Check if it's an array
                DB::table('thesis_category')->where('thesis_id', $thesis->id)->delete();
                foreach ($categories as $categoryId) {
                    DB::table('thesis_category')->insert([
                        'thesis_id' => $thesis->id,
                        'category_id' => $categoryId
                    ]);
                }
            } else {
                // Handle case where categories is not an array
                // return response()->json(['error' => 'Categories data is not in the correct format'], 400);
                $rs = SharedFunctions::prompt_msg("Categories data is not in the correct format");
            }
        }
        if (!$thesis->save()) {
            return response()->json(SharedFunctions::prompt_msg("Error saving thesis"));
        }
        $message = $new_help ? "Thesis created successfully" : "Thesis updated successfully";
        return response()->json(SharedFunctions::success_msg($message));
    }

    public function update(Request $request)
    {
        $rs = SharedFunctions::default_msg();
       
        // dd($request->all());
        $this->validate($request, [
            'title' => 'required|max:120',
            'abstract' => 'required|max:500',
            'published_at' => 'required|date',
            
            
        ]);
        //'video' => 'required|file|mimes:mp4,mov,avi,wmv',
        //'pdf' => 'required|file|mimes:pdf'
       
        if(!$thesis = thesis::find($request->id)){
            return response()->json(SharedFunctions::prompt_msg("error occured. Can't find any thesis with ID:"  + $request->id));
        }


        $thesis->title = $request->title;
        $thesis->abstract = $request->abstract;
        
        if ($request->has('published_at')) {
            $publishedAt = $request->published_at;
            // Check if $publishedAt is not null and is a valid string
            if (is_string($publishedAt) && !empty($publishedAt)) {
                // Assuming $thesis is an instance of your Eloquent model
                $thesis->published_at = $publishedAt;
                // Save the changes to the database
                
                // Return success response or perform other actions
                
            } else {
                // Handle case where published_at is not in the correct format
                return response()->json(['error' => 'Published at date is not in the correct format'], 400);
            }
        }
        
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
    
        if (!$thesis->save()) {
            return response()->json(SharedFunctions::prompt_msg("Error saving thesis"));
        }

        // Attach authors to the thesis
        if ($request->has('authors')) {
            $authors = json_decode($request->authors); // Decode JSON string into an array
            if (is_array($authors)) { // Check if it's an array
                foreach ($authors as $authorId) {
                    DB::table('thesis_author')->insert([
                        'thesis_id' => $thesis->id,
                        'author_id' => $authorId
                    ]);
                }
            } else {
                // Handle case where authors is not in the correct format
                return response()->json(['error' => 'Authors data is not in the correct format'], 400);
            }
        }
        

        if ($request->has('keywords')) {
            $keywords = json_decode($request->keywords); // Decode JSON string into an array
            if (is_array($keywords)) { // Check if it's an array
                foreach ($keywords as $keywordId) {
                    DB::table('thesis_keyword')->insert([
                        'thesis_id' => $thesis->id,
                        'keyword_id' => $keywordId
                    ]);
                }
            } else {
                // Handle case where keywords is not an array
                return response()->json(['error' => 'Keywords data is not in the correct format'], 400);
            }
        }
        
        if ($request->has('categories')) {
            $categories = json_decode($request->categories); // Decode JSON string into an array
            if (is_array($categories)) { // Check if it's an array

                foreach ($categories as $categoryId) {
                    DB::table('thesis_category')->insert([
                        'thesis_id' => $thesis->id,
                        'category_id' => $categoryId
                    ]);
                }
            } else {
                // Handle case where categories is not an array
                return response()->json(['error' => 'Categories data is not in the correct format'], 400);
            }
        }
        
        $message = "Thesis updated successfully";
        return response()->json(SharedFunctions::success_msg($message));
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
            return response()->json(['text' => 'pdf not found'], 200);
        }
    }
}
