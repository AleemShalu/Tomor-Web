<?php

namespace App\Http\Controllers\Web\Owner\Rating;

use App\Http\Controllers\Controller;
use App\Models\OrderRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexRating()
    {
        $ownerId = Auth::id();

        $ratings = OrderRating::with('store', 'order_rating_type')
            ->whereHas('store', function ($query) use ($ownerId) {
                $query->where('owner_id', $ownerId);
            })
            ->get();

        $ratingsTrendsChart = OrderRating::select(DB::raw('DATE_FORMAT(order_ratings.created_at, "%Y-%m") as month'), DB::raw('AVG(order_ratings.rating) as average_rating'))
            ->join('stores', 'order_ratings.store_id', '=', 'stores.id')
            ->where('stores.owner_id', $ownerId)
            ->groupBy('month')
            ->orderBy('month', 'asc') // Add this line to sort by 'month' in ascending order
            ->get();

        return view('owner.rating.rating.index', compact('ratings', 'ratingsTrendsChart'));
    }

    public function indexFeedback()
    {
        $ratings = OrderRating::with('customer', 'store', 'order.store_branch')->get();

        return view('owner.rating.feedback.index', compact('ratings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
