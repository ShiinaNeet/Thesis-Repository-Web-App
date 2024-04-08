<?php

namespace App\Http\Controllers;

use App\Models\authors;
use App\Models\thesis;
use App\Models\Users;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    public function thesisSearch(){
        $data['css'] = ['global'];
        return view('student.dashboard', $data);
    }

    function index(){
        $data['css'] = ['global'];
        $user = Auth::user();
        if (Auth::check() && $user->user_type == Users::TYPE_STUDENT){
          
            return view('student.dashboard', $data);
        }

        if($user){
            return redirect('/dashboard');
        }else{
            return redirect('/login');
        }


    }
    function login(){
        $data['css'] = ['global'];
        $user = Auth::user();
        if (Auth::check() && $user->user_type == Users::TYPE_STUDENT){
          
            return view('student.dashboard', $data);
        }

        if($user){
            return redirect('/dashboard');
        }else{
            return redirect('/login');
        }


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
        
        $Users = Users::get()->count();
        $authors = authors::get()->count();
        $thesis = thesis::get()->count();
        $thesisoutdated = thesis::get()->where('published_at','<', Carbon::now()->subYears(5))->count();
        $rs['result'] = [
            'users' => $Users,
            'authors' => $authors,
            'thesis' => $thesis,
            'thesisoutdated' => $thesisoutdated,
        ];
        
        return $rs;
    }
}
