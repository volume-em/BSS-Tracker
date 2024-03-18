<?php

namespace App\Http\Controllers;

class LogoutResponse implements \Filament\Http\Responses\Auth\Contracts\LogoutResponse
{
    public function toResponse($request)
    {
        return redirect()->to('/');
    }
}
