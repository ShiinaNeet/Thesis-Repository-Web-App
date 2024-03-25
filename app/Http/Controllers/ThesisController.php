<?php

namespace App\Http\Controllers;

use App\Libraries\SharedFunctions;
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
        $theses = Thesis::withTrashed();

        if ($request->title  !== null || $request->title != '') {
            $theses = $theses->where('title', 'like', '%' . $request->title . '%');
        }
        
      
           $theses = thesis::get()->map(function($q) {
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
            $theses = $theses->filter(function ($thesis) use ($author,$category,$keyword) {
               
                $authorsCollection = collect($thesis->authors);
                $categoryCollection = collect($thesis->categories);
                $keywordsCollection = collect($thesis->keywords);

                $authorMatch = $authorsCollection->contains(function ($authorItem) use ($author) {
                    return strpos($authorItem['name'], $author) !== false;
                });
                $categoryMatch = $categoryCollection->contains(function ($categoryitem) use ($category) {
                    return strpos($categoryitem['category'], $category) !== false;
                });
                $keywordMatch = $keywordsCollection->contains(function ($keywordItem) use ($keyword) {
                    return strpos($keywordItem['keyword'], $keyword) !== false;
                });
                return $authorMatch && $categoryMatch && $keywordMatch;
            });
            
        // $theses = $theses->get()
        //     ->map(function($q) {
        //         $arr = [];
        //         $arrK = [];
        //         $arrC = [];
        //         $q = Thesis::where('id', $q->id)
        //             ->first();
        //         //  dd($q->authors);
        //         $authorIds = explode(',', trim($q->authors, '[]'));
                
        //         foreach ($authorIds as $authorId) {
        //             $author = authors::find($authorId);
        //             if ($author) {
        //                 $arr[] = $author->toArray();
        //             }
        //         }
    
        //         $keywordIds = explode(',', trim($q->keywords, '[]'));
                
        //         foreach ($keywordIds as $keywordId) {
        //             $keyword = keywords::find($keywordId);
        //             if ($keyword) {
        //                 $arrK[] = $keyword->toArray();
        //             }
        //         }
    
        //         $categoryIds = explode(',', trim($q->categories, '[]'));
                
        //         foreach ($categoryIds as $categoryId) {
        //             $category = category::find($categoryId);
        //             if ($category) {
        //                 $arrC[] = $category->toArray();
        //             }
        //         }
        
        //         $q->authors = $arr;
        //         $q->keywords = $arrK;
        //         $q->categories = $arrC;
        //         return $q;
        //     })
        //     ->toArray();
       

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
            $rs = SharedFunctions::prompt_msg('Thesis not found');
        }
        
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
                return response()->json(SharedFunctions::prompt_msg("Published at date is not in the correct format"), 400);
            }
        }
        
        // $thesis->authors =  $request->authors;
        // $thesis->categories = $request->categories;
        // $thesis->keywords = $request->keywords;
        
        if (is_string($request->authors)) {
            // If $request->authors is a string, convert it to an array
            $authors = [$request->authors];
        } else if (is_array($request->authors)) {
            // If $request->authors is already an array, use it as is
            $authors = $request->authors;
        } else {
            // Handle the case where $request->authors is neither a string nor an array
            // You might want to handle this differently based on your requirements
            // For example, you could set $authors to an empty array or throw an error
            $authors = [];
        }
        if (is_string($request->categories)) {
            // If $request->authors is a string, convert it to an array
            $categories = [$request->categories];
        } else if (is_array($request->categories)) {
            // If $request->authors is already an array, use it as is
            $categories = $request->categories;
        } else {
            // Handle the case where $request->authors is neither a string nor an array
            // You might want to handle this differently based on your requirements
            // For example, you could set $authors to an empty array or throw an error
            $categories = [];
        }
        if (is_string($request->keywords)) {
            // If $request->authors is a string, convert it to an array
            $keywords = [$request->keywords];
        } else if (is_array($request->keywords)) {
            // If $request->authors is already an array, use it as is
            $keywords = $request->keywords;
        } else {
            // Handle the case where $request->authors is neither a string nor an array
            // You might want to handle this differently based on your requirements
            // For example, you could set $authors to an empty array or throw an error
            $keywords = [];
        }
        
        $thesis->authors = implode('|', $authors);
        $thesis->categories = implode('|', $categories);
        $thesis->keywords = implode('|', $keywords);

        
        if($thesis->save()){
            $rs = SharedFunctions::success_msg("Thesis Saved!");

        }

        // try {
        //     DB::beginTransaction();
    
        //     // Delete existing related records only if updating an existing thesis
        //     if ($new_help === false) {
        //         DB::table('thesis_author')->where('thesis_id', $thesis->id)->delete();
        //         DB::table('thesis_keyword')->where('thesis_id', $thesis->id)->delete();
        //         DB::table('thesis_category')->where('thesis_id', $thesis->id)->delete();
        //     }
    
        //     if ($thesis->save()) {
        //         // Insert authors
        //         if ($request->has('authors')) {
        //             $authors = json_decode($request->authors, true);
        //             if (is_array($authors)) {
        //                 $authorData = [];
        //                 foreach ($authors as $authorId) {
        //                     $authorData[] = [
        //                         'thesis_id' => $thesis->id,
        //                         'author_id' => $authorId
        //                     ];
        //                 }
        //                 DB::table('thesis_author')->insert($authorData);
        //             } else {
        //                 return response()->json(SharedFunctions::prompt_msg("Authors data is not in the correct format"), 400);
        //             }
        //         }
    
        //         // Insert keywords
        //         if ($request->has('keywords')) {
        //             $keywords = json_decode($request->keywords , true);
        //             if (is_array($keywords)) {
        //                 $keywordData = [];
        //                 foreach ($keywords as $keywordId) {
        //                     $keywordData[] = [
        //                         'thesis_id' => $thesis->id,
        //                         'keyword_id' => $keywordId
        //                     ];
        //                 }
        //                 if (!empty($keywordData)) {
        //                     DB::table('thesis_keyword')->insert($keywordData);
        //                 }
        //             } else {
        //                 return response()->json(SharedFunctions::prompt_msg("Keywords data is not in the correct format"), 400);
        //             }
        //         }
    
        //         // Insert categories
        //         if ($request->has('categories')) {
        //             $categories = json_decode($request->categories, true);
        //             if (is_array($categories)) {
        //                 $categoryData = [];
        //                 foreach ($categories as $categoryId) {
        //                     $categoryData[] = [
        //                         'thesis_id' => $thesis->id,
        //                         'category_id' => $categoryId
        //                     ];
        //                 }
        //                 DB::table('thesis_category')->insert($categoryData);
        //             } else {
        //                 return response()->json(SharedFunctions::prompt_msg("Categories data is not in the correct format"), 400);
        //             }
        //         }
    
        //         DB::commit();
        //         $message = $new_help ? "Thesis updated successfully" : "Thesis created successfully";
        //         $rs = SharedFunctions::success_msg($message);
                
        //         return response()->json($rs);
        //     } else {
        //         throw new \Exception("Error saving thesis");
        //     }
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return response()->json(SharedFunctions::prompt_msg($e->getMessage()), 500);
        // }
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
