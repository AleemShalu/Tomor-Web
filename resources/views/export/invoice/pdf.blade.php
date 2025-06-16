<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simplified Tax Invoice</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            /*background-color: #F6F6F6;*/
            margin: 0;
            padding: 0;
            text-align: center;
            align-content: center;
            align-items: center;
        }

        /*.invoice-container {*/
        /*    !*max-width: 600px;*!*/
        /*    !*margin: 10px auto;*!*/
        /*    background-color: #fff;*/
        /*    !*padding: 10px;*!*/
        /*    !*border-radius: 10px;*!*/
        /*}*/

        .header, .business {
            text-align: center;
            /*margin-bottom: 10px;*/
        }

        /*.details {*/
        /*    margin-top: 5px;*/
        /*}*/

        .details p, .invoice-details h3, .qr-title {
            margin: 10px 0;
        }

        /*.qr-code-container {*/
        /*    text-align: center;*/
        /*    margin-top: 5px;*/
        /*}*/

        /*.qr-code-container img {*/
        /*    max-width: 100px;*/
        /*}*/

        /*table {*/
        /*    border-collapse: collapse;*/
        /*    width: 100%;*/
        /*}*/

        /*td {*/
        /*    padding: 5px 3px;*/
        /*    font-size: 14px;*/
        /*}*/

        hr {
            border: 1px solid #E0E0E0;
        }

    </style>
</head>
<body>
<div class="invoice-container">
    <img style="width: 60px; margin-bottom: 10px" src="{{public_path('images/tomor-logo-04.png')}}">
    <div class="header">
        <strong>فاتورة ضريبية مبسطة
            <br>
            Simplified Tax Invoice
        </strong>
    </div>
    <br>
    <div class="business">
        <strong>{{$business->name_en}}
            <br>
            {{$business->name_ar}}
        </strong>
        <p>Head office:
            @if($business->building_no)
                {{$business->building_no}},
            @endif
            @if($business->street)
                {{$business->street}},
            @endif
            @if($business->district)
                {{$business->district}},
            @endif
            @if($business->city)
                {{$business->city}},
            @endif
            @if($business->state)
                {{$business->state}},
            @endif
            @if($business->country)
                {{$business->country}}.
            @endif
        </p>
    </div>
    <hr>
    <div class="details">
        <table style="width:100%;">
            <tr>
                <td style="width:60%;">
                    <span>رقم التعريف الضريبي</span><br>
                    <span>Tax Identification Number:</span>
                </td>
                <td style="text-align: right;">
                    {{ $business->vat_number ?? 'N/A' }}
                </td>
            </tr>
            <tr>
                <td>
                    <span>رقم الطلب</span><br>
                    <span>Order Number:</span>
                </td>
                <td style="text-align: right;">
                    {{ $order->store_order_number }}
                </td>
            </tr>
            <tr>
                <td>
                    <span>رقم الفاتورة التسلسلي:</span><br>
                    <span>Invoice Serial Number:</span>
                </td>
                <td style="text-align: right;">
                    {{ $invoice->invoice_number }}
                </td>
            </tr>
            <tr>
                <td>
                    <span>تاريخ الفاتورة</span><br>
                    <span>Invoice Date:</span>
                </td>
                <td style="text-align: right;">
                    {{ convertDateToTimezone($invoice->invoice_date, null, request('timezone')) }}
                </td>
            </tr>
        </table>
    </div>
    <hr>
    <div class="invoice-details">
        <strong>تفاصيل الفاتورة <br> Details</strong>
        <table style="width:100%;">
            <tr>
                <td style="width:60%;">
                    <span>رسوم الخدمة</span><br>
                    <span>Service Fees:</span>
                </td>
                <td style="text-align: right;">
                    {{ formatPrice($invoice->subtotal, config('app.currency')) }}
                </td>
            </tr>
            <tr>
                <td>
                    <span>إجمالي الخصم</span><br>
                    <span>Discount:</span>
                </td>
                <td style="text-align: right;">
                    {{ formatPrice($invoice->total_discount, config('app.currency')) }}
                </td>
            </tr>
        </table>
        <hr>
        <table style="width:100%;">
            <tr>
                <td>
                    <span>الإجمالي شامل الضريبة</span><br>
                    <span>Sub Total VAT Inclusive:</span>
                </td>
                <td style="text-align: right;">
                    {{ formatPrice($invoice->gross_total_including_vat, config('app.currency')) }}
                </td>
            </tr>
            <tr>
                <td>
                    <span>معدل ضريبة القيمة المضافة</span><br>
                    <span>VAT:</span>
                </td>
                <td style="text-align: right;">
                    {{ $taxRate }}%
                </td>
            </tr>
            <tr>
                <td>
                    <span>إجمالي ضريبة القيمة المضافة التي تم تحصيلها</span><br>
                    <span>Total VAT Collected:</span>
                </td>
                <td style="text-align: right;">
                    {{ formatPrice($invoice->total_vat_in_sar, config('app.currency')) }}
                </td>
            </tr>

        </table>
    </div>
    <hr>
    <div class="qr-code-container">
        <p class="qr-title">
            <span>رمز الاستجابة السريع</span><br>
            <span>QR Code:</span>
        </p>
        <img src="{{ $qrCode }}" alt="QR Code for Invoice">
    </div>

</div>
</body>
</html>
