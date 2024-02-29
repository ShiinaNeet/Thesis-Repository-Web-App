<?php

namespace App\Http\Controllers;

use App\Models\authors;
use App\Models\thesis;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    function index(){
        $data['css'] = ['global'];
        $user = Auth::user();
        if (Auth::check() && $user->user_type == Users::TYPE_STUDENT){
          
            return view('student.dashboard', $data);
        }
        elseif(Auth::check() && $user->user_type == Users::TYPE_ADMIN){
          
            return redirect(url('/dashboard'));
        }
        
       end: return view('login', $data);

    }
    function login(){
        $data['css'] = ['global'];
        return view('login', $data);

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
        $rs['result'] = [
            'users' => $Users,
            'authors' => $authors,
            'thesis' => $thesis,
        ];
        
        return $rs;
    }
}
