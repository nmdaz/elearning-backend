<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Auth\Notifications\ResetPassword;


class SendPasswordResetEmailController extends Controller
{
    use SendsPasswordResetEmails;
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        ResetPassword::createUrlUsing(function ($notifiable, $token) use ($request) {
            return "$request->callbackUrl/password/reset/{$token}";
        });

        return $this->sendResetLinkEmail($request);
    }
}
