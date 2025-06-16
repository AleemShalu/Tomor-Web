@component('mail::message')

    @php $verification_code_time_limit = env('FRONT_OTP_CODE_EXPIRATION_MINUTES', 5); @endphp
    @php
        $business = \App\Models\Business::find(1);

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

    @endphp


    <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
        <tr>
            <td style="padding:0 0 36px 0;color: rgb(130, 124, 133);font-family:Droid Arabic Kufi, Open Sans, Century Gothic, sans-serif">
                <p dir="rtl"
                   style="font-weight:bold; margin:0;font-family:Droid Arabic Kufi, Open Sans, Century Gothic, sans-serif">
                    مرحبًا يا {{ $user_data->name }},
                </p>
                <p dir="ltr"
                   style="font-weight:bold; margin:0 0 20px 0;font-family:Droid Arabic Kufi, Open Sans, Century Gothic, sans-serif">
                    Hello {{ $user_data->name }},
                </p>
                <p dir="rtl"
                   style="margin:0;font-size:14px;line-height:24px;font-family:Droid Arabic Kufi, Open Sans, Century Gothic, sans-serif">
                    من فضلك استعمل الرمز المؤقت أدناه كي نفعل بريدك الإلكتروني.
                </p>
                <p dir="ltr"
                   style="margin:0 0 12px 0;font-size:14px;line-height:24px;font-family:Droid Arabic Kufi, Open Sans, Century Gothic, sans-serif">
                    Use the following OTP code to complete the registration process.
                </p>
                <p style="text-align: center;">
                    <span style="font-size:1.6rem;font-weight:500;display:inline-block;overflow:hidden;line-height:initial;margin:15px 0;text-decoration:none;color: rgb(124, 46, 170);font-family:Droid Arabic Kufi, Open Sans, Century Gothic, sans-serif">
                        {{ $user_data->code }}
                    </span>
                </p>
                <p dir="rtl"
                   style="margin:30px 0 0 0;font-size:14px;font-family:Droid Arabic Kufi, Open Sans, Century Gothic, sans-serif">
                    مع فائق الاحترام،
                </p>
                <p dir="ltr"
                   style="margin-bottom:0 0 5px 0;font-size:14px;font-family:Droid Arabic Kufi, Open Sans, Century Gothic, sans-serif">
                    Sincerely,
                </p>
                <p dir="ltr"
                   style="border-top:1px dashed  rgb(130, 124, 133);padding-top:12px;margin-top:24px 0 0 0;color:rgb(124, 46, 170);font-size:16px;font-weight:bold;font-family:Droid Arabic Kufi, Open Sans, Century Gothic, sans-serif">
                    {{$business->name_ar}} | {{$business->name_en}}
                </p>
                <p dir="ltr"
                   style="margin-bottom:0 0 12px 0;font-size:14px;font-family:Droid Arabic Kufi, Open Sans, Century Gothic, sans-serif">
                    <sapn style="color:rgb(124, 46, 170);">address:</sapn>{{$businessAddress}}
                    <br>
                    <a href="{{ env('APP_URL') }}" target="_blank">{{ env('APP_URL') }}</a>
                </p>
            </td>
        </tr>
    </table>
@endcomponent

{{-- <p dir="rtl" style="margin:0;font-size:14px;line-height:24px;font-family:Droid Arabic Kufi, Open Sans, Century Gothic, sans-serif">
    الرمز صالحٌ لمدة {{ $verification_code_time_limit }} دقائق فحسب، لذا نرجوا أن تنشّط حسابك قبل ذلك.
</p>
<p dir="ltr" style="margin:0 0 12px 0;font-size:14px;line-height:24px;font-family:Droid Arabic Kufi, Open Sans, Century Gothic, sans-serif">
    The code will expire in {{ $verification_code_time_limit }} minutes, so please verify soon!
</p> --}}
