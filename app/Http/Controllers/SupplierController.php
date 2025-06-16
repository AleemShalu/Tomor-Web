<?php

// app/Http/Controllers/SupplierController.php

namespace App\Http\Controllers;

use App\Services\DhamenApiService;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    protected $dhamenApiService;

    public function __construct(DhamenApiService $dhamenApiService)
    {
        $this->dhamenApiService = $dhamenApiService;
    }

    /**
     * @throws \Exception
     */
    public function create(Request $request)
    {
//        $request->validate([
//            'name' => 'required|string|max:100',
//            'iban' => 'required|string|size:22|starts_with:SA',
//            'identityNumber' => 'required|string|size:10',
//            'email' => 'nullable|email',
//            'mobile' => 'nullable|string|regex:/^9665[0-9]{8}$/',
//        ]);

        $response = $this->dhamenApiService->createSupplier($request->all());

        return response()->json($response->json(), $response->status());
    }

//    public function update(Request $request)
//    {
////        $request->validate([
////            'supplierId' => 'required|uuid',
////            'name' => 'required|string|max:100',
////            'iban' => 'required|string|size:22|starts_with:SA',
////            'identityNumber' => 'required|string|size:10',
////            'email' => 'nullable|email',
////            'mobile' => 'nullable|string|regex:/^9665[0-9]{8}$/',
////        ]);
//
//        $response = $this->dhamenApiService->updateSupplier($request->all());
//
//        return response()->json($response->json(), $response->status());
//    }

    public function payment(Request $request)
    {
//        $request->validate([
//            'paymentReferenceID' => 'required|string|max:50|unique:payments,payment_reference_id',
//            'supplierPayments' => 'required|array',
//            'supplierPayments.*.supplierId' => 'required|uuid',
//            'supplierPayments.*.amount' => 'required|numeric|min:1',
//        ]);

        $response = $this->dhamenApiService->supplierPayment($request->all());

        return response()->json($response->json(), $response->status());
    }
}
