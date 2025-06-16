@component('mail::message')

    @php $verification_code_time_limit = env('FRONT_OTP_CODE_EXPIRATION_MINUTES', 5); @endphp

    <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
        <tr>
            <td style="text-align:center;padding:0 0 24px 0;color: rgb(130, 124, 133);font-family:Droid Arabic Kufi, Open Sans, Century Gothic, sans-serif">
                <p dir="rtl" style="text-align:center;font-weight:bold; margin:0;font-family:Droid Arabic Kufi, Open Sans, Century Gothic, sans-serif">
                    مرحبًا يا {{ $user_data->name }},
                </p>
                <p  dir="ltr" style="text-align:center;font-weight:bold; margin:0 0 20px 0;font-family:Droid Arabic Kufi, Open Sans, Century Gothic, sans-serif">
                    Hello {{ $user_data->name }},
                </p>
                <p dir="rtl" style="text-align:center;margin:0;font-size:14px;line-height:24px;font-family:Droid Arabic Kufi, Open Sans, Century Gothic, sans-serif">
                    من فضلك استعمل الرمز المؤقت أدناه كي نتوثق من طلبك لاستعادة كلمة السر.
                </p>
                <p dir="ltr" style="text-align:center;margin:0 0 12px 0;font-size:14px;line-height:24px;font-family:Droid Arabic Kufi, Open Sans, Century Gothic, sans-serif">
                    Use the following OTP code to verify your forgot password request.
                </p>
                <p style="text-align:center;">
                    <span style="font-size:1.6rem;font-weight:500;display:inline-block;overflow:hidden;line-height:initial;margin:0;text-decoration:none;color: rgb(124, 46, 170);font-family:Droid Arabic Kufi, Open Sans, Century Gothic, sans-serif">
                        {{ $user_data->code }}
                    </span>
                </p>
                <p dir="rtl" style="text-align:center;margin:0;font-size:14px;font-family:Droid Arabic Kufi, Open Sans, Century Gothic, sans-serif">
                    مع فائق الاحترام،
                </p>
                <p dir="ltr" style="text-align:center;margin:0;font-size:14px;font-family:Droid Arabic Kufi, Open Sans, Century Gothic, sans-serif">
                    Sincerely,
                </p>
            </td>
        </tr>
        <tr>
            <td>
                @include('emails.email_footer')
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
