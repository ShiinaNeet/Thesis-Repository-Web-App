<?php

namespace App\Http\Controllers;

use App\Models\authors;
use App\Models\thesis;
use App\Models\Users;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class PageController extends Controller
{
    public function access_denied(){
        $data['css'] = ['global'];
        return view('student.accessdenied',$data);
    }
    public function thesisSearch(){
        $data['css'] = ['global'];
        return view('student.dashboard', $data);
    }

    function index(){
        $data['css'] = ['global'];
        $user = Auth::user();
        // if (Auth::check() && $user->user_type == Users::TYPE_STUDENT){
          
        //     return view('student.dashboard', $data);
        // }

        if($user){
            return redirect('/dashboard');
        }else{
            return view('/login', $data);
        }


    }
    function login(){
        $data['css'] = ['global'];
        $user = Auth::user();
       
      
        if($user !== null){
            if ($user->user_type === Users::TYPE_STUDENT){
                return view('student.dashboard', $data);
            }
            return redirect('/dashboard');
        }else{
            return view('/login', $data);
        }
        return view('/login', $data);

    }
    
    function dashboard(){
        $data['css'] = ['global'];
        return view('dashboard', $data);

    }

    function studentDashboard(){
        $data['css'] = ['global'];
        return view('student.dashboard', $data);

    }

    function dashboardData(){
        
        $PdfStorageSize = $this->getFolderSize(storage_path('app/pdf'));
        $VideoStorageSize = $this->getFolderSize(storage_path('app/videos'));

    
        $Users = Users::get()->count();
        $authors = authors::get()->count();
        $thesis = thesis::get()->count();
        $thesisoutdated = thesis::get()->where('published_at','<', Carbon::now()->subYears(5))->count();
        $rs['result'] = [
            'users' => $Users,
            'authors' => $authors,
            'thesis' => $thesis,
            'thesisoutdated' => $thesisoutdated,
            'pdf' => $PdfStorageSize,
            'video' => $VideoStorageSize
        ];
        
        return $rs;
    }
    function getFolderSize($path)
    {
        $totalSize = 0;

        // Get all files and directories within the specified path
        $items = File::allFiles($path);
    
        foreach ($items as $item) {
            $totalSize += $item->getSize(); // Add the size of each file
        }
    
        return number_format($totalSize / 1048576, 2); 
    }
}
