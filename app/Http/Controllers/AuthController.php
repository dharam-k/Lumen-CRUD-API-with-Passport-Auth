<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request){
        $email = $request->email;
        $password = $request->password;
        
        //Check if field is not empty
        if (empty($email) or empty($password)) {
            return response()->json(['status' => 'error', 'message' => 'You must fill all fields']);
        }       
        $user = User::where('email', '=', $email)->exists();

        if ($user === false) {
            return response()->json(['status' => 'error', 'message' => 'User doesnt exist']);
        }                               
        
        try{
            $tokenRequest = $request->create(           
                env('PASSPORT_LOGIN_ENDPOINT'),
                'POST'
            );
    
            $tokenRequest->request->add([
                "grant_type" => "password",
                "username" => $request->email,              
                "password" => $request->password,
                "client_id" =>env('PASSPORT_CLIENT_ID'),
                "client_secret" => env('PASSPORT_CLIENT_SECRET'),
                "scope"=> "*"
            ]);
    
            $response = app()->handle($tokenRequest);

            return $response;
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
