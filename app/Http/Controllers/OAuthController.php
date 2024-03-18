<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class OAuthController
{
    public function redirect()
    {
        $url = Setting::where('setting', 'oauth_client_url')->firstOrFail();
        $authorize_url = Setting::where('setting', 'oauth_authorize_path')->first();
        $client_id = Setting::where('setting', 'oauth_client_id')->firstOrFail();
        $oauth_scopes = Setting::where('setting', 'oauth_scopes')->first();

        $query = http_build_query([
            'client_id' => $client_id->value,
            'redirect_url' => route('oauth.callback'),
            'response_type' => 'code',
            'scope' => $oauth_scopes?->value ?? []
        ]);

        return redirect()->to('https://' .
            $url->value .
            ($authorize_url?->value ?? '/oauth/authorize') . '?client_id=' .
            $client_id->value . '&response_type=code&redirect_uri=' .
            urlencode(route('oauth.callback')) . '&scope=' . urlencode($oauth_scopes?->value ?? ''));
    }

    public function callback(Request $request)
    {
        if (! $request->has('code')) abort(404);

        $code = $request->get('code');
        $url = Setting::where('setting', 'oauth_client_url')->firstOrFail();
        $user_path = Setting::where('setting', 'oauth_user_path')->first();
        $token_url = Setting::where('setting', 'oauth_token_path')->first();
        $client_id = Setting::where('setting', 'oauth_client_id')->firstOrFail();
        $client_secret = Setting::where('setting', 'oauth_client_secret')->firstOrFail();

        $response = Http::withoutVerifying()->asForm()->post('https://' . $url->value . $token_url?->value ?? '/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => $client_id->value,
            'client_secret' => $client_secret->value,
            'redirect_uri' => route('oauth.callback'),
            'code' => $code
        ]);


        $access_token = $response->json()['access_token'];
        $response = Http::withoutVerifying()->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $access_token
        ])->get('https://' . $url->value . $user_path?->value ?? '/api/user');


        $responseJson = $response->json();

        return $this->loginUsingResponse($responseJson);
    }

    public function loginUsingResponse($response)
    {
        try {
            $user = User::where('email', $response['email'])->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $user = new User();

            $user->name = $response['name'];
            $user->email = $response['email'];
            $user->password = Str::random(128);
            $user->role = 1;

            $user->save();
        }

        Auth::loginUsingId($user->id);

        return redirect()->to('/');
    }
}
