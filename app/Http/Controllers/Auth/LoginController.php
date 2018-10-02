<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Cookie;
use \Firebase\JWT\JWT;
use Carbon\Carbon;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
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
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();
        $jwt = $this->getToken($this->guard()->user());
        $this->clearLoginAttempts($request);
        Cookie::queue('jwt', $jwt);
        return $this->authenticated($request, $this->guard()->user())
            ?: redirect()->intended($this->redirectPath());
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();
        Cookie::queue('jwt', null);
        return $this->loggedOut($request) ?: redirect('/');
    }     
}
