<?php

namespace App\Http\Controllers\Web\Owner\Auth;

use App\Http\Controllers\Web\Owner\Controller;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;

class RedirectController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $hasStore = Store::where('user_id', $userId)->exists();

        if ($hasStore) {

            return redirect()->route('dashboard');
        } else {
            // User does not have data in the "store" table
            // Add your logic here
            // For example, return a different view or perform alternative actions
            return redirect('/stores/create');
        }
    }
}
