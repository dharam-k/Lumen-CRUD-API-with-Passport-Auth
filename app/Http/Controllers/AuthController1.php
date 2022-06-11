<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $req){
        $email = $req->email;
        $password = $req->password;

        if(empty($email) OR empty($password)){
            return response()->json(['status'=> 'error', 'message'=>'All Fields are required!']);
        }

        $client = new Client();

        $response = $client->request('POST', 'http://localhost:8001/oauth/token', [
            'headers' => [
                'cache-control' => 'no-cache',
                'Content-Type' => 'application/x-www-form-urlencoded'
            ],
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => 2,
                'client_secret' => '1B7jfvx97pvL8Y67AxaU7077aWeN4ZA0ptV4Kx6p',
                'username' => $req->email,
                'password' => $req->password,
                'scope' => '*',
            ]
        ]);

        return json_decode((string) $response->getBody(), true);
    }
}
