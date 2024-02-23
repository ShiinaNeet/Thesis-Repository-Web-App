<?php

namespace App\Http\Controllers;

use App\Libraries\SharedFunctions;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class UsersController extends Controller
{
    //
    public function GetUsers(Request $request)
    {
        return 'wewok';
    }

    public function register(Request $request)
    {
        $rs = SharedFunctions::default_msg();
        
        $this->validate($request, [
            'userID' => 'required|max:20',
            'password' => 'required',
            'password_match' => 'required',
        ]);

        $user = Users::find($request->userID)->count();
        if($user > 0)  
        {
            $rs = SharedFunctions::prompt_msg("There is an account registered with this User ID!");
            goto end;
        }
        if(!$user && $request->password !== $request->password_match)
        {
          $rs = sharedfunction::prompt_msg('Invalid Account Credentials');
          goto end;
        }
        
        $newUser = new Users();
        $newUser->userID = $request->userID;
        $newUser->password = bcrypt($request->password);
        if($newUser->save()) $rs = sharedfunction::prompt_msg('Invalid Account Credentials');

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
            'email'     => $request->email,
            'password'  => $request->password
        ]);
    
        if (Auth::check()) {
            $rs = SharedFunctions::success_msg('Login success');
            $rs['redirect'] = '/dashboard';
        }
        return response()->json($rs);
    }
}
