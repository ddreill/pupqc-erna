<?php

namespace App\Http\Controllers\Auth;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Rules\ValidDomain;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RedirectsUsers;

class SSOController extends Controller
{
    use RedirectsUsers;

    /**
     * Where to redirect users after authenticated.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    protected $providers = array(
        "google",
        "azure"
    );

    protected $providersColumn = [
        "google" => [
            "id" => "google_auth_id",
            "email" => "google_auth_email",
        ],
        "azure" => [
            "id" => "azure_auth_id",
            "email" => "azure_auth_email",
        ],
    ];

    public function login($provider)
    {
        abort_if(!in_array($provider, $this->providers), Response::HTTP_NOT_FOUND, '404 Not Found');

        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function redirect($provider)
    {
        abort_if(!in_array($provider, $this->providers), Response::HTTP_NOT_FOUND, '404 Not Found');

        try {

            $user = Socialite::driver($provider)->stateless()->user();

            $validator = Validator::make(["email" => $user->email]);

            if ($validator->fails()) {
                return redirect()
                    ->route('login')
                    ->with('error', __('auth.sso_not_permitted'));
            }

            $findUser = User::where($this->providersColumn[$provider]["id"], $user->getId())->first();

            if (!$findUser) {

                if (User::where('email', $user->getEmail())->exists()) {
                    return redirect()
                        ->route('login')
                        ->with('error', __('Unable to proceed, these account do not match our records.'));
                }

                return $this->redirectRegistration($user, $provider);
            } else {
                $user = $findUser;
            }

            Auth::login($user);

            return redirect($this->redirectPath());
        } catch (Exception $e) {
            dd($e);
            return redirect()->route('login')->with('error', __('auth.sso_failed'));
        }
    }

    public function redirectRegistration($user, $provider)
    {
        $token = Str::random(64);

        DB::table('sso_registration')->where('auth_id', $user->getId())->delete();

        DB::table('sso_registration')->insert(
            [
                'auth_id' => $user->getId(),
                'email' => $user->getEmail(),
                'name' => $user->getName(),
                'avatar' => $user->getAvatar(),
                'token' => Hash::make($token),
                "created_at" => now()
            ]
        );

        dd($token);
        return redirect()->route('sso.register', ["provider" => $provider, "token" => $token]);
    }

    public function register(Request $request, $provider, $token)
    {
        abort_if(!in_array($provider, $this->providers), Response::HTTP_NOT_FOUND, '404 Not Found');

        $sso_registration = DB::table('sso_registration')
            ->where('auth_id', $request->auth_id)
            ->first();

        if ($sso_registration && Hash::check($token, $sso_registration->token)) {
            $createdAt = Carbon::parse($sso_registration->created_at);

            if (Carbon::now()->greaterThan($createdAt->addMinutes(config('auth.passwords.users.expire')))) {
                // return view('auth.register-sso')->with(
                //     ['message' => __('Registration token is already expired. Please try again.'), 'validToken' => false]
                // );

                return "YES";
            }
        } else {
            // return view('auth.register-sso')->with(
            //     ['message' => __('Registration token is already expired. Please try again.'), 'validToken' => false]
            // );
            return "NO";

        }

        // return view('auth.register-sso')->with(["id" => $request->id, "email" => $request->email, "avatar" => $request->avatar]);
    }
}
