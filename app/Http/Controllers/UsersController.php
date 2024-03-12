<?php

namespace App\Http\Controllers;

use App\Libraries\SharedFunctions;
use App\Models\Users;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session as FacadesSession;

class UsersController extends Controller
{
    public function GetUsers(Request $request)
    {
        $rs = SharedFunctions::default_msg();

        $users = Users::get();
        $rs = SharedFunctions::success_msg("");
        $rs["result"] = $users;
        return $rs;
    }

    public function register(Request $request)
    {
        $rs = SharedFunctions::default_msg();
        
        $this->validate($request, [
            'userID' => 'required',
            'password' => 'required',
        ]);

        $user = Users::where('userID',$request->userID)->count();
        if($user > 0)  
        {
            $rs = SharedFunctions::prompt_msg("There is an account registered with this User ID!");
            goto end;
        }
       
        $newUser = new Users();
        $newUser->userID = $request->userID;
        $newUser->password = bcrypt($request->password);
        $rs = SharedFunctions::success_msg('Account successfully created.');
        if(!$newUser->save()) $rs = SharedFunctions::prompt_msg('Invalid Account Credentials');

        end: return response()->json($rs);
    }

    public function login(Request $request)
    {
        $rs = SharedFunctions::prompt_msg('Login failed');
        $this->validate($request, [
            'userID' => 'required',
            'password' => 'required'
        ]);
        Auth::attempt([
            'userID'     => $request->userID,
            'password'  => $request->password
        ]);
    
        if (Auth::check()) {
            $rs = SharedFunctions::success_msg('Login success');
            $rs['redirect'] = '/dashboard';
        }
        return response()->json($rs);
    }

    public function save(Request $request)
    {
        $rs = SharedFunctions::default_msg();
        $this->validate($request, [
            'userID' => 'required|numeric',
            'user_type' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'repassword' => 'required',
        ]);
       

        $user = new Users(); $new_account = true; 
        $password = $request->password;
        $repassword = $request->repassword;
        if($password == $repassword){
            $user->userID = $request->userID;
            $user->email = $request->email;
            $user->user_type = $request->user_type;
            $user->password = bcrypt($password);
        }
        else {
            $rs = SharedFunctions::prompt_msg("Invalid Account Credentials");
            goto end;
        }

 
        if ($user->save()) {
            $rs = SharedFunctions::success_msg("Account created");
           
            
        }
        end: return response()->json($rs);
    }

    public function update(Request $request)
    {
        $rs = SharedFunctions::default_msg();
        $this->validate($request, [
            'userID' => 'required|numeric',
            'email' => 'required',
            'user_type' => 'required',
        ]);
       
        $user = Users::find($request->id);
        if(!$user){
            $rs = SharedFunctions::prompt_msg("Cannot found the account with User ID: "+$request->id);
        }
        
        $user->userID = $request->userID;
        $user->email = $request->email;
        $user->user_type = $request->user_type;
       

        if (!$user->save()) {
            $rs = SharedFunctions::prompt_msg("Account Update Failed!");
           
        }
        $rs = SharedFunctions::success_msg("Account saved");
        return response()->json($rs);
    }

    public function logout(){
        Auth::logout();
        FacadesSession::flush();
        return redirect('/login');
    }
    
}
