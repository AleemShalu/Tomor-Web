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

<p dir="ltr"
   style="border-top:1px dashed  rgb(130, 124, 133);padding-top:24px;margin:0;color:rgb(124, 46, 170);font-size:16px;font-weight:bold;font-family:Droid Arabic Kufi, Open Sans, Century Gothic, sans-serif">
    {{$business->name_ar}} | {{$business->name_en}}
</p>
<p dir="ltr"
   style="margin-bottom:0 0 12px 0;font-size:14px;font-family:Droid Arabic Kufi, Open Sans, Century Gothic, sans-serif">
    <sapn style="color:rgb(124, 46, 170);">address:</sapn>{{$businessAddress}}
    <br>
    <sapn style="color:rgb(124, 46, 170);">webiste:</sapn>
    <a href="{{ env('APP_URL') }}" target="_blank">{{ env('APP_URL') }}</a>
</p>
