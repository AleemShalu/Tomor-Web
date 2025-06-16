<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function setLanguage(Request $request)
    {
        $request->validate([
            'locale' => 'required|in:en,ar', // add more languages if needed
        ]);

        session(['locale' => $request->input('locale')]);

        return redirect()->back();
    }
}
