<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\StorePromoter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StorePromoterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $promoters = StorePromoter::all();
        return view('admin.store.promoter.index', compact('promoters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $stores = Store::all();
        return view('admin.store.promoter.create', compact('stores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'required|unique:store_promoters',
            'store_id' => 'required|exists:stores,id',
            'name_en' => 'required',
            'name_ar' => 'required',
            'description_en' => 'nullable',
            'description_ar' => 'nullable',
            'croppedImageData' => 'required', // up to 1 MB
            'status' => 'nullable|boolean',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $storePromoter = new StorePromoter();

        if ($request->has('croppedImageData')) {
            // Get the base64-encoded image data
            $croppedImageData = $request->input('croppedImageData');

            // Convert the base64 data to binary
            $binaryImageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $croppedImageData));

            // Determine the file path
            $storeHeaderFolder = 'stores/' . $validatedData['store_id'] . '/header-promoter';
            if (!Storage::disk('public')->exists($storeHeaderFolder)) {
                Storage::disk('public')->makeDirectory($storeHeaderFolder);
            }

            $headerPath = $storeHeaderFolder . '/' . uniqid('header-', true) . '.png'; // You can use a specific extension

            // Store the binary image data
            Storage::disk('public')->put($headerPath, $binaryImageData);

            // Update the store model with the image path
            $storePromoter->promoter_header_path = $headerPath;
        }


// Parse and convert the start and end dates
        $start_date = Carbon::parse($validatedData['start_date'], 'Asia/Riyadh')->tz('UTC');
        $end_date = Carbon::parse($validatedData['end_date'], 'Asia/Riyadh')->tz('UTC');

// Update the $validatedData array with the converted dates
        $validatedData['start_date'] = $start_date;
        $validatedData['end_date'] = $end_date;

// Fill the model with validated data
        $storePromoter->fill($validatedData);

        // Save the store promoter
        $storePromoter->save();

        // Return a single resource instance
        return redirect()->route('admin.promoters.index');
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $storePromoter = StorePromoter::find($id);
        return view('admin.store.promoter.show', compact('storePromoter'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $storePromoter = StorePromoter::find($id);
        $stores = Store::all();
        return view('admin.store.promoter.edit', compact('storePromoter', 'stores'));
    }

    /**
     * Activate the specified resource.
     */
    public function activate($id)
    {
        $promoter = StorePromoter::findOrFail($id);
        $promoter->status = 0; // Assuming 0 is for active status
        $promoter->save();

        // Redirect or return a response as needed
        return redirect()->route('admin.promoters.index')->with('success', 'Promoter activated successfully');
    }

    /**
     * Deactivate the specified resource.
     */
    public function deactivate($id)
    {
        $promoter = StorePromoter::findOrFail($id);
        $promoter->status = 1; // Assuming 1 is for inactive status
        $promoter->save();

        // Redirect or return a response as needed
        return redirect()->route('admin.promoters.index')->with('success', 'Promoter deactivated successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {

        $promoter_id = $request->promoter_id;
        $storePromoter = StorePromoter::findOrFail($promoter_id);
        $validatedData = $request->validate([
            'code' => 'required|unique:store_promoters,code,' . $promoter_id,
            'store_id' => 'required|exists:stores,id',
            'name_en' => 'required',
            'name_ar' => 'required',
            'description_en' => 'nullable',
            'description_ar' => 'nullable',
            'croppedImageData' => 'nullable', // Update only if provided, up to 1 MB
            'status' => 'nullable|boolean',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        if ($request->has('croppedImageData')) {
            // Get the base64-encoded image data
            $croppedImageData = $request->input('croppedImageData');

            // Convert the base64 data to binary
            $binaryImageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $croppedImageData));

            // Determine the file path
            $storeHeaderFolder = 'stores/' . $validatedData['store_id'] . '/header-promoter';
            if (!Storage::disk('public')->exists($storeHeaderFolder)) {
                Storage::disk('public')->makeDirectory($storeHeaderFolder);
            }

            $headerPath = $storeHeaderFolder . '/' . uniqid('header-', true) . '.png'; // You can use a specific extension

            // Store the binary image data
            Storage::disk('public')->put($headerPath, $binaryImageData);

            // Update the store model with the new image path
            $storePromoter->promoter_header_path = $headerPath;
        }

        // Parse and convert the start and end dates
        $start_date = Carbon::parse($validatedData['start_date'], 'Asia/Riyadh')->tz('UTC');
        $end_date = Carbon::parse($validatedData['end_date'], 'Asia/Riyadh')->tz('UTC');

        // Update the $validatedData array with the converted dates
        $validatedData['start_date'] = $start_date;
        $validatedData['end_date'] = $end_date;

        // Fill the model with validated data
        $storePromoter->fill($validatedData);

        // Update the model with validated data
        $storePromoter->update($validatedData);

        // Return a single resource instance
        return redirect()->route('admin.promoters.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StorePromoter $storePromoter)
    {
        //
    }
}
