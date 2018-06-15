<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Socialite;

use App\User;
use App\SocialProvider;

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
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        try
        {

            $socialUser = Socialite::driver($provider)->user();

            \Debugbar::debug($socialUser);

            $socialProvider = SocialProvider::where('provider_id', $socialUser->getId())->first();

            \Debugbar::debug($socialProvider);

            if(!$socialProvider)
            {
                $user = User::firstOrCreate(
                    ['email' => $socialUser->getEmail() ],
                    ['name' => $socialUser->getName() ]
                );

                \Debugbar::debug($user);

                $user->socialProvider()->create(
                    ['provider_id' =>$socialUser->getId(), 'provider' => $provider]
                );

                \Debugbar::debug($user);
            }
            else
            {
                $user = $socialProvider->user;

                \Debugbar::debug($user);
            }

            \Debugbar::debug($user);

            auth()->login($user);

        } catch (\Exception $e) {

            //return redirect('/');

            throw $e;
        }

        return redirect('/home');
    }
}
