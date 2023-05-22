<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Services\Login\RememberMeExpiration;
use Illuminate\Support\Facades\Hash;


class AuthenticationController extends Controller {
    use RememberMeExpiration;

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
     * Display a login form.
     *
     */
    public function loginView()
    {
        return view('auth.login');
    }

    /**
     * Authenticate the user.
     *
     * @param LoginRequest $request
     * @return RedirectResponse|Response
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->getCredentials();
//
//        if(!Auth::validate($credentials)):
//            return redirect()->back()->withErrors(['login' => "Invalid login details"]);
//        endif;

        $remember = $request->get('remember');
        $user = Auth::getProvider()->retrieveByCredentials($credentials);
        Auth::login($user, $remember);

        if($remember):
            $this->setRememberMeExpiration($user);
        endif;

        return $this->authenticated($request, $user);
    }

    /**
     * Handle response after user authenticated
     *
     * @param Request $request
     * @param User $user
     *
     * @return Response
     */
    protected function authenticated(Request $request, User $user)
    {
        return redirect()->intended('/');
    }

    /*public function logincreatetest(Request $request)
    {
        $user = new User();

        $password = Hash::make($request->get("password"));

        $user->fill([
            'username' => $request->get('username'),
            'password' => $password,
        ]);

        $user->save();
    }*/

    /**
     * Log out the user from application.
     *
     * @param Request $request
     * @return Response
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')
            ->withSuccess('You have logged out successfully!');;
    }
}
