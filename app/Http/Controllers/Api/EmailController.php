<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Mail\WelcomeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendEmail(Request $request)
    {
        $email = $request->input('email');
        Mail::to($email)->send(new WelcomeMail());

        return response()->json(['success' => true]);
    }
}
