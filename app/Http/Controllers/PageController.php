<?php

namespace App\Http\Controllers;

use App\Models\authors;
use App\Models\Users;
use Illuminate\Http\Request;

class PageController extends Controller
{
    function index(){
        if (true) return redirect(url('/login'));
        $data['css'] = ['global'];
        return view('login', $data);

    }
    function login(){
        $data['css'] = ['global'];
        return view('login', $data);

    }
    
    function dashboard(){
        $data['css'] = ['global'];
        return view('dashboard', $data);

    }

    function dashboardData(){
        
        $Users = Users::get()->count();
        $authors = authors::get()->count();
        $rs['result'] = [
            'users' => $Users,
            'authors' => $authors
        ];
        
        return $rs;
    }
}
