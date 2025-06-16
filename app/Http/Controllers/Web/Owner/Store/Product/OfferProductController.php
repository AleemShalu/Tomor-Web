<?php

namespace App\Http\Controllers\Web\Owner\Store\Product;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class OfferProductController extends Controller
{
    public function index($storeId, Request $request)
    {
        $store = Store::findOrFail($storeId);
        $offers = Offer::where('store_id', $storeId)->with('products')->get();
        $products = $store->products;

        $currentOfferId = $request->input('offer_id', $offers->first()->id ?? null); // Get the offer_id from request or default to first offer
        $currentOffer = Offer::where('id', $currentOfferId)->with('products')->first();

        $currentOfferProductIds = [];
        if ($currentOffer) {
            $currentOfferProductIds = $currentOffer->products->pluck('id')->toArray();
        }

        return view('owner.store.manage.product.offer.index', compact('store', 'offers', 'products', 'currentOfferProductIds', 'currentOffer'));
    }

    public function getProductsForOffer($offerId)
    {
        $offer = Offer::with('products')->findOrFail($offerId);
        return response()->json($offer->products);
    }

    public function store(Request $request, $storeId)
    {
        $request->validate([
            'offer_id' => 'required|exists:offers,id',
            'product_ids.*' => 'exists:products,id'
        ]);

        $offerId = $request->input('offer_id');
        $productIds = $request->input('product_ids', []);

        $offer = Offer::findOrFail($offerId);
        $offer->products()->sync($productIds);

        return redirect()->route('offer.product', $storeId)->with('status', 'Offer applied to products successfully!');
    }

    public function createOffer($storeId)
    {
        $store = Store::findOrFail($storeId);
        return view('owner.store.manage.product.offer.create', compact('store'));
    }

    public function storeOffer(Request $request, $storeId)
    {
        $request->validate([
            'offer_name' => 'required|string|max:255',
            'offer_description' => 'nullable|string',
            'discount_percentage' => 'required|numeric|between:0,100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|boolean'
        ]);

        // Begin a transaction to ensure atomicity
        DB::beginTransaction();

        try {
            $offer = Offer::create([
                'store_id' => $storeId, // Note: We use the $storeId from the parameter
                'offer_name' => $request->input('offer_name'),
                'offer_description' => $request->input('offer_description'),
                'discount_percentage' => $request->input('discount_percentage'),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'status' => $request->input('status'),
            ]);

            // If the offer is active, set all other offers for the store to inactive
            if ($request->input('status') == "1") {
                Offer::where('store_id', $storeId)
                    ->where('id', '!=', $offer->id) // Exclude the newly created offer
                    ->update(['status' => false]);
            }

            // Commit the transaction if everything is successful
            DB::commit();
        } catch (\Exception $e) {
            // Rollback the transaction if there's any error
            DB::rollBack();

            // Handle the exception (maybe return a response or rethrow the exception)
            return redirect()->route('offer.create', $storeId)->with('error', 'Offer could not be created!');
        }

        return redirect()->route('offer.product', [$storeId, 'offer_id' => $offer->id])->with('status', 'Offer created successfully!');
    }

    public function editOffer($storeId, $offerId)
    {
        $store = Store::findOrFail($storeId);
        $offer = Offer::findOrFail($offerId);

        return view('owner.store.manage.product.offer.edit', compact('store', 'offer'));
    }

    public function updateOffer(Request $request, $storeId, $offerId)
    {
        $request->validate([
            'offer_name' => 'required|string|max:255',
            'offer_description' => 'nullable|string',
            'discount_percentage' => 'required|numeric|between:0,100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|boolean'
        ]);

        $offer = Offer::findOrFail($offerId);

        $offer->update([
            'offer_name' => $request->input('offer_name'),
            'offer_description' => $request->input('offer_description'),
            'discount_percentage' => $request->input('discount_percentage'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'status' => $request->input('status') == "1" ? true : false,
        ]);

        // If the offer is being set to active, set all other offers for the store to inactive.
        if ($request->input('status') == "1") {
            Offer::where('store_id', $storeId)
                ->where('id', '!=', $offerId)
                ->update(['status' => false]);
        }


        return redirect()->route('offer.product', [$storeId, 'offer_id' => $offer->id])->with('status', 'Offer updated successfully!');
    }

    public function destroy($storeId, $offerId)
    {
        $store = Store::findOrFail($storeId);
        $offer = Offer::findOrFail($offerId);

        // Implement any logic needed before deleting the offer, e.g., logs, etc.

        $offer->delete();

        return redirect()->route('offer.product', $storeId)->with('status', 'Offer deleted successfully!');
    }

}