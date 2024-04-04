<?php

namespace App\Http\Controllers;

use App\Libraries\SharedFunctions;
use App\Models\Users;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session as FacadesSession;

class UsersController extends Controller
{
    public function disable(Request $request)
    {
        $rs = SharedFunctions::default_msg();
        
        $query = Users::find($request->id);

        if ($query->delete()) {
            $rs = SharedFunctions::success_msg('Account disabled');
            
        }

       
        return response()->json($rs);
    }
    public function enable(Request $request)
    {
        $rs = SharedFunctions::default_msg();
        
        Users::withTrashed()->find($request->id)->restore();

        $rs = SharedFunctions::success_msg('Account enabled');
        return response()->json($rs);
    }

    public function GetUsers(Request $request)
    {
        $rs = SharedFunctions::default_msg();

        $users = Users::withTrashed()->get();
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
       
        $currentUser = Users::where('UserID','=', $request->userID)->first();
        if($currentUser){
            $rs = SharedFunctions::prompt_msg("User ID Taken! Please Try Again!");
            goto end;
        }
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
            'user_type' => 'required',
        ]);

        $authUser = Auth::user()->userID;
        $user = Users::where('userID','=', $request->userID)->first();

        if ($user && $user->id !== $request->id) {
            // If a user with the new userID already exists (excluding the current user), return appropriate error message
            $rs = SharedFunctions::prompt_msg("User ID already taken! Please choose a different one.");
            return response()->json($rs);
        }

        $userToUpdate = Users::find($request->id);

        $userToUpdate->userID = $request->userID;
        $userToUpdate->email = $request->email;
        $userToUpdate->user_type = $request->user_type;  

        if (!$userToUpdate->save()) {
            $rs = SharedFunctions::prompt_msg("Account Update Failed!");
            goto end;
        }
        $rs = SharedFunctions::success_msg("Account Updated");
        end: return response()->json($rs);
    }

    public function generate_password(Request $request)
    {
        $rs = SharedFunctions::prompt_msg("");
       
        $this->validate($request,[
            'userID' => ['required'],
        ]);

        $user = Users::where('userID', $request->userID)->first();
        // dd($user);
        if(!$user){
            $rs = SharedFunctions::prompt_msg("Something went wrong. Please refresh and try again! Error: ID missing");
            goto end;
        }
        // Generate password here
        $randomString = Str::random(15);
        // Assign
        $user->password = bcrypt($randomString);

        if($user->save()){
            $result = $randomString;
            
            $rs = SharedFunctions::success_msg("Password Successfully Generated");
            $rs['result'] = $result;
            goto end;
        }
        $rs = SharedFunctions::prompt_msg("Save Failed. Please refresh and try again!");
        
        end: return response()->json($rs);
    }

    public function logout(){
        Auth::logout();
        FacadesSession::flush();
        return redirect('/login');
    }
    
}
