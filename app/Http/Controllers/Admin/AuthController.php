<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\Session;

class AuthController extends Controller
{
    public function index()
    {
        return Inertia::render('Auth/Index');
    }

    public function redirect()
    {
        return Socialite::driver('keycloak')->redirect();
    }

    public function callback()
    {
        $provider = Socialite::driver('keycloak');
        $data = $provider->stateless()->stateless()->user();

        $user = User::where('email', '=', $data->email)->first();

        $this->authenticate($user, $data);

        return redirect()->route('Admin.HomeIndex');
    }

    public function silentCallback(Request $request)
    {
        $provider = Socialite::driver('keycloak');
        $data = $provider->userFromToken($request->token);

        $user = User::where('email', '=', $data->email)->first();

        $this->authenticate($user, $data);

        session()->save();

        if($session = Session::where('id', '=', session()->getId())->first()) {
            $session->update(['id_sso' => $request->sid]);
        }

        return back();
    }

    public function logout()
    {
        Auth::logout();

        return redirect(Socialite::driver('keycloak')->getLogoutUrl(route('HomeIndex'), config('services.keycloak.client_id')));
    }

    public function silentLogout(Request $request)
    {
        if($request->logout_token) {
            $data = explode('.', $request->logout_token);

            if(isset($data[1])) {
                $dataDecode = base64_decode(strtr($data[1], ['-' => '+', '_' => '/']));
                $decoded = json_decode($dataDecode);

                $session = Session::where('id_sso', '=', $decoded->sid)->first();

                if($session) {
                    $session->delete();
                }
            }
        }

        return response(200);
    }

    private function authenticate($user = null, $data = [])
    {
        if(!$user) {
            if(!empty($data) && isset($data->user['family_name'])) {
                $user = new User([
                    'surname' => $data->user['family_name'],
                    'name' => $data->user['given_name'],
                    'email' => $data->user['email'],
                    'email_verified_at' => Carbon::now(),
                    'password' => Hash::make(Str::random(32)),
                ]);

                $user->save();
            }
        }

        if($user) {
            Auth::login($user);

            session()->save();

            $session = Session::where('id', '=', session()->getId())->first();

            if($session && isset($data->accessTokenResponseBody['session_state'])) {
                $session->update(['id_sso' => $data->accessTokenResponseBody['session_state']]);
            }
        }
    }
}
