<?php

namespace App\Http\Controllers\Web\Owner\Store\Bank;

use App\Enums\UserType;
use App\Http\Controllers\Owner\Store\Bank\BankAccountBranches;
use App\Http\Controllers\Web\Owner\Controller;
use App\Models\BankAccount;
use App\Models\Store;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;

class BankAccountController extends Controller
{

    /**
     * Show the form for creating a new resource.
     */
    public function create($storeId)
    {
        if (!auth()->user()->hasRole('owner')) {
            abort(403, 'Unauthorized');
        }

        $user_id = Auth::id();
        $store = Store::where('id', $storeId)->where('owner_id', $user_id)->first();

        if ($store) {
            return view('owner.store.manage.bank.create', compact('store'));
        } else {
            return view('owner.utility.404');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'store_id' => 'required',
            'iban_number' => [
                'required',
                'string',
                Rule::unique('bank_accounts')->where(function ($query) use ($request) {
                    return $query->where('store_id', $request->store_id);
                })
            ],
            'account_holder_name' => 'required',
            'iban_attachment' => 'required|file|mimes:jpeg,png,pdf|max:2048',
        ], [
            'iban_number.required' => 'The IBAN number field is required.',
            'iban_number.regex' => 'Please enter a valid Saudi bank IBAN number.',
            'iban_number.unique' => 'The IBAN number has already been taken for this store.',
            'account_holder_name.required' => 'The account holder name field is required.',
            'iban_attachment.mimes' => 'The IBAN attachment must be a file of type: jpeg, png, pdf.',
            'iban_attachment.max' => 'The IBAN attachment may not be greater than 2MB.',
        ]);

        $bankAccount = new BankAccount();
        $bankAccount->store_id = $request->store_id;
        $bankAccount->bank_name = $request->bank_name;
        $bankAccount->iban_number = $request->iban_number;
        $bankAccount->account_holder_name = $request->account_holder_name;
        $bankAccount->save();

        // Handle the IBAN attachment file upload
        if (isset($validatedData['iban_attachment'])) {
            $store_iban_attachments_folder = 'stores/' . $request->store_id . '/attachments/iban-attachments/' . $bankAccount->id;
            if (!File::exists(storage_path($store_iban_attachments_folder))) {
                Storage::disk(getSecondaryStorageDisk())->makeDirectory($store_iban_attachments_folder);
            }
            $iban_attachment_file = $validatedData['iban_attachment'];
            $iban_attachment_path = Storage::disk(getSecondaryStorageDisk())->putFileAs(
                $store_iban_attachments_folder,
                $iban_attachment_file,
                uniqid('iban-attachments-', true) . '.' . $iban_attachment_file->getClientOriginalExtension(),
            );
            $bankAccount->iban_attachment = $iban_attachment_path;
            $bankAccount->save();
        }

        return redirect()->route('settings.manage', ['id' => $request->store_id])->with('success', 'Bank account added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($store, $account)
    {
        if (!auth()->user()->hasRole('owner')) {
            abort(403, 'Unauthorized');
        }

        $bankAccount = BankAccount::where('id', $account)->get();

        $bankAccountBranches = BankAccount::with('branches', 'store')
            ->where('store_id', $store)
            ->where('id', $account)
            ->get();

        $store = Store::find($store); // أو Store::where('id', $store)->first();
//        return response()->json($bankAccountBranches);
        return view('owner.store.manage.bank.show', compact('bankAccountBranches', 'store'));

//        return response()->json($bankAccount);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BankAccount $backAccount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BankAccount $backAccount)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BankAccount $backAccount)
    {
        //
    }

    public function printPDF($store, $account)
    {
        // Retrieve the data needed to generate the PDF
        $bankAccountBranches = BankAccountBranches::with('store', 'branch', 'bank')
            ->where('store_id', $store)
            ->where('bank_account_id', $account)
            ->get();

        // Load the view file
        $html = View::make('owner.store.manage.bank.show', compact('bankAccountBranches', 'store', 'account'))->render();

        // Generate the PDF using Dompdf
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Output the generated PDF
        $dompdf->stream('bank_account_details.pdf', ['Attachment' => false]);
    }
}
