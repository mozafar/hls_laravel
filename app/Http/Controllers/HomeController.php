<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cookie;
use \Firebase\JWT\JWT;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Generates new jwt token
     * 
     * @param  mixed  $user
     * @return string 
     */
    protected function getToken($user) 
    {
        $key = 's3cr3t';
        $token = array(
            "iss" => "parspack",
            "sub" => "hls",
            "aud" => "users",
            "iat" => time(),
            "exp" => time() + (24 * 60 * 60),
            "uid" => $user->id
        );
        
        $jwt = JWT::encode($token, $key);
        return $jwt;
    }    

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->cookie('jwt')) {
            $jwt = $this->getToken(Auth::user());
            Cookie::queue('jwt', $jwt);
        }
        return view('home');
    }  
}
