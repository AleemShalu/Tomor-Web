<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceResource;
use App\Models\Business;
use App\Models\Coupon;
use App\Models\CustomerWithSpecialNeeds;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\ServiceDefinition;
use App\Models\TaxCode;
use App\Services\InvoiceService;
use App\Traits\Api\UserIsAuthorizedTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Salla\ZATCA\GenerateQrCode;
use Salla\ZATCA\Tags\InvoiceDate;
use Salla\ZATCA\Tags\InvoiceTaxAmount;
use Salla\ZATCA\Tags\InvoiceTotalAmount;
use Salla\ZATCA\Tags\Seller;
use Salla\ZATCA\Tags\TaxNumber;

class InvoiceController extends Controller
{
    use UserIsAuthorizedTrait;

    public function list(Request $request)
    {
        // validate user credentials
        $error = $this->checkIfRequestHasAuthUser($request);
        if ($error) return $error;

        // check if the user is authorized to use the resource
        $error = $this->checkIfUserHasRightRoles($request, ['customer']);
        if ($error) return $error;

        // check if user is authorized to use the resource
        if ($request->user()->id != request('customer_id')) {
            return response()->json([
                "message" => __('locale.api.errors.user_is_forbidden_from_resource'),
                "code" => "FORBIDDEN_ERROR"
            ], Response::HTTP_FORBIDDEN);
        }

        $invoices = Invoice::where('customer_id', request('customer_id'))
            ->orderBy('created_at', 'desc')
            ->select([
                'id',
                'invoice_number',
                'status',
                'order_id',
                'store_invoice_number',
                'path',
                'invoice_date',
                'store_name_ar',
                'store_name_en',
                'gross_total_including_vat',
                'updated_at',
                'created_at',
            ])
            ->get();

        $transformedInvoices = InvoiceResource::collection($invoices);

        return response()->json([
            'invoices' => $transformedInvoices,
        ], Response::HTTP_OK);
    }

    public function show(Request $request)
    {
        $invoice = Invoice::find(request('invoice_id'));

        if (!$invoice) {
            return response()->json(['message' => 'Invoice not found'], Response::HTTP_NOT_FOUND);
        }

        $transformedInvoice = new InvoiceResource($invoice);

        return response()->json(['invoice' => $transformedInvoice], Response::HTTP_OK);
    }

    public function createInvoice($orderId, $request = null)
    {
        // Find the order and related data
        $order = Order::with('store', 'store_branch', 'customer')->findOrFail($orderId);
        $business = Business::first();
        $serviceDefinition = ServiceDefinition::first();
        $tax = TaxCode::first();

        $serviceCost = $serviceDefinition->price;
        $discount = 0;
        $discountAmount = 0;
        $taxRate = $tax->tax_rate / 100;

        $coupon = Coupon::where('code', $order->coupon_code)->first();
        if (isset($coupon->discount_percentage)) {
            $discount = $coupon->discount_percentage;
            $discountAmount = $serviceCost * ($discount / 100);
        } else if (isset($coupon->discount_amount)) {
            $discount = $coupon->discount_amount;
            if ($discount <= $serviceCost) { // check if discount is less or equal service price
                $discountAmount = $discount;
            }
        }

        if (isset($order->customer_id)) {
            $customer_with_special_needs = CustomerWithSpecialNeeds::where('customer_id', $order->customer_id)->first();
            if ($customer_with_special_needs && $customer_with_special_needs->special_needs_qualified == 1) {
                $discountAmount = $serviceCost; // make it free for special needs customers
            }
        }

        $serviceCostAfterDiscount = $serviceCost - $discountAmount;
        $taxAmount = $serviceCostAfterDiscount * $taxRate;
        $serviceCostAfterTaxAndDiscount = $serviceCostAfterDiscount + $taxAmount;

        $invoiceService = new InvoiceService();
        $invoiceNumber = $invoiceService->generateInvoiceNumber();

        $businessAddress = '';
        if ($business->building_no) {
            $businessAddress .= $business->building_no;
        }
        if ($business->street) {
            $businessAddress .= ($businessAddress ? ', ' : '') . $business->street;
        }
        if ($business->district) {
            $businessAddress .= ($businessAddress ? ', ' : '') . $business->district;
        }
        if ($business->city) {
            $businessAddress .= ($businessAddress ? ', ' : '') . $business->city;
        }
        if ($business->state) {
            $businessAddress .= ($businessAddress ? ', ' : '') . $business->state;
        }
        if ($business->country) {
            $businessAddress .= ($businessAddress ? ', ' : '') . $business->country . '.';
        }

        // Create the new invoice
        $invoice = new Invoice([
            'uuid' => Str::uuid(),
            'invoice_number' => $invoiceNumber,
            'status' => 'paid',
            'invoice_locale' => 'ar',
            'invoice_date' => now(),
            'business_id' => $business->id,
            'business_name_ar' => $business->name_ar,
            'business_name_en' => $business->name_en,
            'business_address' => $businessAddress,
            'business_vat_number' => $business->vat_number,
            'business_group_vat_number' => $business->group_vat_number,
            'business_cr_number' => $business->cr_number,

            'order_id' => $order->id,
            'supply_date' => now(),

            'store_id' => $order->store->id,
            'store_invoice_number' => $order->store_order_number,
            'store_name_ar' => $order->store->commercial_name_ar,
            'store_name_en' => $order->store->commercial_name_en,

            'store_branch_id' => $order->store_branch->id,
            'store_branch_invoice_number' => $order->branch_order_number,
            'store_branch_name_ar' => $order->store_branch->name_ar,
            'store_branch_name_en' => $order->store_branch->name_en,
            'store_branch_address' => $order->store_branch->location->district,

            'customer_id' => $order->customer->id,
            'customer_name' => $order->customer->name,
            'customer_email' => $order->customer->email,
            'customer_dial_code' => $order->customer->dial_code,
            'customer_contact_no' => $order->customer->contact_no,

            'invoice_discount_percentage' => optional($coupon)->discount_percentage ?? 0,
            'invoice_discount_amount' => optional($coupon)->discount_amount ?? 0,
            'subtotal' => $serviceCost,
            'total_discount' => $discountAmount,
            'total_taxtable_amount' => $serviceCostAfterDiscount,
            'total_vat' => $taxAmount,
            'exchange_rate' => 1.0,
            'conversion_time' => now(),
            'total_vat_in_sar' => $taxAmount,
            'gross_total_including_vat' => $serviceCostAfterTaxAndDiscount,
            'issued_by' => null,
            'path' => null, // Set path to null initially
            'qrcode_path' => null, // Set qrcode_path to null initially
            'additional' => null,
        ]);
        // Set the invoice_hash
        $invoice->invoice_hash = md5($invoice->invoice_number);

        // Save the invoice to the database
        $invoice->save();

        // Generate qr code in base64
        $qrCodeBase64 = $this->generateQrCode(
            $business,
            $invoice->invoice_date,
            $invoice->gross_total_including_vat,
            $invoice->total_vat_in_sar,
        );

        // Save the QR code image as a file
        $qrCodeImagePath = 'invoices/qrcodes/qr_code_' . $business->id . '_' . $invoice->invoice_date->format('Y_m_d_H_i_s') . '.png';
        $decodedQrCode = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $qrCodeBase64));
        if ($decodedQrCode !== false) {
            Storage::disk(getSecondaryStorageDisk())->put($qrCodeImagePath, $decodedQrCode);
        }

        // Generate and save PDF
        $pdfPath = $this->generatePdfSimplifiedInvoice(
            $business,
            $order,
            $invoice,
            $tax->tax_rate,
            $qrCodeBase64,
        );

        // Update the invoice with the file paths
        $invoice->update([
            'qrcode_path' => $qrCodeImagePath,
            'path' => $pdfPath,
        ]);

        return $invoice;
    }

    public function generateQrCode($business, $invoiceDate, $invoiceGrandTotal, $taxTotal)
    {
        return GenerateQrCode::fromArray([
            new Seller($business->name_ar),
            new TaxNumber($business->vat_number),
            new InvoiceDate($invoiceDate),
            new InvoiceTotalAmount(number_format($invoiceGrandTotal, 2)),
            new InvoiceTaxAmount(number_format($taxTotal, 2))
        ])->render();
    }

    public function generatePdfSimplifiedInvoice($business, $order, $invoice, $taxRate, $qrCodeBase64)
    {
        try {

            $data = [
                'business' => $business,
                'order' => $order,
                'invoice' => $invoice,
                'taxRate' => $taxRate,
                'qrCode' => $qrCodeBase64,
            ];

            $html = view('export.invoice.pdf', $data)->render();
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [120, 310]]);
            $mpdf->WriteHTML($html);
            $pdfOutput = $mpdf->Output('', 'S');

            // Define the storage path for the invoice PDF
            $storagePath = 'invoices/pdfs/invoice_' . $business->id . '_' . $invoice->invoice_date->format('Y_m_d_H_i_s') . '.pdf';

            // Store the PDF in the secondary storage disk
            Storage::disk(getSecondaryStorageDisk())->put($storagePath, $pdfOutput);

            // Return the file path for the stored invoice
            return $storagePath;
        } catch (ModelNotFoundException $e) {
            Log::error($e->getMessage());
            return null;
        }
    }
}
