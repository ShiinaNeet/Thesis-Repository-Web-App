<?php

namespace App\Http\Controllers;

use App\Libraries\SharedFunctions;
use App\Models\AuditTrail;
use App\Models\thesis;
use App\Models\authors;
use App\Models\category;
use App\Models\keywords;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class ThesisController extends Controller
{
    public function search(Request $request){

        $keyword = $request->keyword;
        $author = $request->author;
        $category = $request->category;
        $title = $request->title;
        $sortFilter = $request->sort;
        
        $theses = Thesis::withTrashed();
        
       
           $theses = $theses->get()->map(function($q) {
                $arr = [];
               
                $authorIds = explode(',', trim($q->authors, '[]'));
                
                foreach ($authorIds as $authorId) {
                    $author = authors::find($authorId);
                    if ($author) {
                        $arr[] = $author->toArray();
                    }
                }
                $q->authors = $arr;

                $arrC = [];
               
                $categoryIds = explode(',', trim($q->categories, '[]'));
                
                foreach ($categoryIds as $categoryId) {
                    $category = category::find($categoryId);
                    if ($category) {
                        $arrC[] = $category->toArray();
                    }
                }
                $q->categories = $arrC;
                
                $arrK = [];
                $keywordIds = explode(',', trim($q->keywords, '[]'));
                
                foreach ($keywordIds as $keywordId) {
                    $keyword = keywords::find($keywordId);
                    if ($keyword) {
                        $arrK[] = $keyword->toArray();
                    }
                }
                $q->keywords = $arrK;

                return $q;
            });
        if ($sortFilter === "1") {
            $theses = $theses->sortBy('published_at');
        } elseif ($sortFilter === "2") {
        
            $theses = $theses->sortByDesc('published_at');
        }
        else{
            $theses = $theses->sortByDesc('created_at');
        }
        $theses = $theses->filter(function ($thesis) use ($author,$category,$keyword, $title) {
            
            $authorsCollection = collect($thesis->authors);
            $categoryCollection = collect($thesis->categories);
            $keywordsCollection = collect($thesis->keywords);
            $categoryMatch = true;
            $authorMatch = true;
            $keywordMatch = true;
            $titleMatch = true;

            if ($title !== null && $title !== '') {
                $titleMatch = stripos($thesis->title, $title) !== false;
            }
            if($author !== null || $author === []){
                foreach ($author as $auth) {
                    if ($authorsCollection->contains(function ($authorItem) use ($auth) {
                        return stripos($authorItem['name'], $auth) !== false;
                    })) {
                        $authorMatch = true;
                        break;
                    }
                }
            } 
            if($category !== null || $category === []){
                foreach ($category as $cat) {
                    if ($categoryCollection->contains(function ($categoryItem) use ($cat) {
                        return stripos($categoryItem['category'], $cat) !== false;
                    })) {
                        $categoryMatch = true;
                        break;
                    }
                }
            }
            if($keyword !== null || $keyword === ''){
                foreach ($keyword as $key) {
                    if ($keywordsCollection->contains(function ($keywordItem) use ($key) {
                        return stripos($keywordItem['keyword'], $key) !== false;
                        
                    })) {
                        $keywordMatch = true;
                        break;
                    }
                }
            }
                return $authorMatch & $categoryMatch & $keywordMatch & $titleMatch;
        });

        $theses = $theses->values()->all();
        //$theses = $theses->values()->all();
        $rs = SharedFunctions::success_msg('Success');
        $rs['result'] = $theses;
        return response()->json($rs);
    }

    public function getThesisById($id){

        $thesis = Thesis::withTrashed()->find($id);

        // Check if thesis with given ID exists
        if ($thesis) {
            // Perform your additional processing
            $arr = [];
            $arrK = [];
            $arrC = [];
        
            // Process authors
            $authorIds = explode(',', trim($thesis->authors, '[]'));
            foreach ($authorIds as $authorId) {
                $author = authors::find($authorId); // Assuming 'Author' is your author model
                if ($author) {
                    $arr[] = $author->toArray();
                }
            }
        
            // Process keywords
            $keywordIds = explode(',', trim($thesis->keywords, '[]'));
            foreach ($keywordIds as $keywordId) {
                $keyword = keywords::find($keywordId); // Assuming 'Keyword' is your keyword model
                if ($keyword) {
                    $arrK[] = $keyword->toArray();
                }
            }
        
            // Process categories
            $categoryIds = explode(',', trim($thesis->categories, '[]'));
            foreach ($categoryIds as $categoryId) {
                $category = Category::find($categoryId); // Assuming 'Category' is your category model
                if ($category) {
                    $arrC[] = $category->toArray();
                }
            }
        
            // Update thesis object with processed data
            $thesis->authors = $arr;
            $thesis->keywords = $arrK;
            $thesis->categories = $arrC;
        
            // Return the modified thesis object
            $rs = SharedFunctions::success_msg('Success');
            $rs['result'] = $thesis->toArray();
        } else {
            // Thesis with given ID not found
            $rs = SharedFunctions::prompt_msg('Thesis Not Found');
        }
        
        return response()->json($rs);
        
    }
    public function delete(Request $request)
    {
        $rs = SharedFunctions::default_msg();
        $query = thesis::find($request->id);
        if ($query->forceDelete()) {
            $rs = SharedFunctions::success_msg("Thesis Deleted");
            SharedFunctions::create_audit_log(
                AuditTrail::MODULE_THESIS, AuditTrail::ACTION_DELETE 
            );
        }
        return response()->json($rs);
    }

    public function disable(Request $request)
    {
        $rs = SharedFunctions::default_msg();
        
        $query = thesis::find($request->id);

        if ($query->delete()) {
            $rs = SharedFunctions::success_msg('Thesis Disabled');
            SharedFunctions::create_audit_log(
                AuditTrail::MODULE_THESIS, AuditTrail::ACTION_DISABLE 
            );
        }

       
        return response()->json($rs);
    }
    public function enable(Request $request)
    {
        $rs = SharedFunctions::default_msg();
        
        thesis::withTrashed()->find($request->id)->restore();

        $rs = SharedFunctions::success_msg('Thesis Enabled');
        SharedFunctions::create_audit_log(
            AuditTrail::MODULE_THESIS, AuditTrail::ACTION_ENABLE 
        );
        return response()->json($rs);
    }

    public function get()
    {
        
        $query = Thesis::withTrashed()
        ->orderBy('created_at', 'DESC')
        ->get()
        ->map(function($q) {
            $arr = [];
            $arrK = [];
            $arrC = [];
            
            // DD($q);
            $authorIds = explode(',', trim($q->authors, '[]'));
            
            foreach ($authorIds as $authorId) {
                $author = authors::find($authorId);
                if ($author) {
                    $arr[] = $author->toArray();
                }
            }

            $keywordIds = explode(',', trim($q->keywords, '[]'));
            
            foreach ($keywordIds as $keywordId) {
                $keyword = keywords::find($keywordId);
                if ($keyword) {
                    $arrK[] = $keyword->toArray();
                }
            }

            $categoryIds = explode(',', trim($q->categories, '[]'));
            
            foreach ($categoryIds as $categoryId) {
                $category = category::find($categoryId);
                if ($category) {
                    $arrC[] = $category->toArray();
                }
            }
    
            $q->authors = $arr;
            $q->keywords = $arrK;
            $q->categories = $arrC;
            return $q;
        })
        ->toArray();

        $rs = SharedFunctions::success_msg('Success');
        $rs['result'] = $query;
        return response()->json($rs);
    }

    public function get_sorted($sort_by)
    {
        $query = Thesis::withTrashed()
       
        ->orderBy('created_at', 'DESC')
        ->get()
        ->map(function($q) {
            $arr = [];
            $arrK = [];
            $arrC = [];
            $q = Thesis::where('id', $q->id)
                ->first();
            //  dd($q->authors);
            $authorIds = explode(',', trim($q->authors, '[]'));
            
            foreach ($authorIds as $authorId) {
                $author = authors::find($authorId);
                if ($author) {
                    $arr[] = $author->toArray();
                }
            }

            $keywordIds = explode(',', trim($q->keywords, '[]'));
            
            foreach ($keywordIds as $keywordId) {
                $keyword = keywords::find($keywordId);
                if ($keyword) {
                    $arrK[] = $keyword->toArray();
                }
            }

            $categoryIds = explode(',', trim($q->categories, '[]'));
            
            foreach ($categoryIds as $categoryId) {
                $category = category::find($categoryId);
                if ($category) {
                    $arrC[] = $category->toArray();
                }
            }
    
            $q->authors = $arr;
            $q->keywords = $arrK;
            $q->categories = $arrC;
            return $q;
        })
        ->toArray();
       
        $rs = SharedFunctions::success_msg('Success');
        $rs['result'] = $query;
        return response()->json($rs);
    }

   
    public function save(Request $request)
    {
        $rs = SharedFunctions::default_msg("Something went wrong!");
        $this->validate($request, [
            'title' => 'required|max:120',
            'abstract' => 'required|max:500',
            'published_at' => 'required|date',
        ]);
    
        $new_help = false;
        if (isset($request->id)){   
            $thesis = thesis::find($request->id);
        }
        else { 
            $thesis = new thesis(); 
            $new_help = true; 
        }
    
        $thesis->title = $request->title;
        $thesis->abstract = $request->abstract;
    
       
        if ($request->hasFile('video') && $request->file('video')->isValid()) {
            $path = $request->file('video')->store('videos');
            $thesis->video = $path;
        }
    
        if ($request->hasFile('pdf') && $request->file('pdf')->isValid()) {
            $path = $request->file('pdf')->store('pdf');
            $thesis->pdf = $path;
        }
    
        if ($request->has('published_at')) {
            $publishedAt = $request->published_at;
            if ($publishedAt !== false) {
                $thesis->published_at = $publishedAt;
            } else {
                return response()->json(SharedFunctions::prompt_msg("Published Date is not in the correct format"), 400);
            }
        }
        
        $keywordsArray = json_decode($request->keywords, true);
        $keywords = array_unique(array_column($keywordsArray, 'id'));
        $authorsarray = json_decode($request->authors, true);
        $authors = array_unique(array_column($authorsarray, 'id'));
        $categoriesarray = json_decode($request->categories, true);
        $categories = array_unique(array_column($categoriesarray, 'id'));
        
        $thesis->authors = '[' . implode(',', $authors) . ']';
        $thesis->categories = '[' . implode(',', $categories) . ']';
        $thesis->keywords = '[' . implode(',', $keywords) . ']';

        if($thesis->save()){
            $rs = SharedFunctions::success_msg("Thesis Saved!");
            SharedFunctions::create_audit_log(
                AuditTrail::MODULE_THESIS,  $new_help == true ? AuditTrail::ACTION_CREATE : AuditTrail::ACTION_UPDATE,
            );
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
            return response()->json(['text' => 'pdf not found'], 200);
        }
    }
}
