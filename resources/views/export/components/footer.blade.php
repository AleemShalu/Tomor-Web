@php

    use App\Models\Business;$business = Business::first();

@endphp

<footer style="border-top: 1px solid #000000; margin-bottom: 3%; padding: 10px; position: relative;">
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <!-- Left Side -->
            <td style="width: 33%; vertical-align: middle; padding: 0;">
                <table style="width: auto; border-collapse: collapse; margin: 0; padding: 0; height: 100%; display: flex; align-items: center;">
                    <tr>
                        <!-- Centered Logo and Text -->
                        <td style="padding: 0; display: flex; align-items: center; height: 100%;">
                            {{--                            <a href="#" style="display: flex; align-items: center; margin: 0;">--}}
                            {{--                                <img src="{{public_path('images/tomor-logo-03.png')}}" alt="Logo"--}}
                            {{--                                     style="height: 30px; display: block; margin: 0;"/>--}}
                            {{--                            </a>--}}
                            <div style="margin-left: 5px; padding: 0; margin-top: 2px; line-height: 1;">
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
                                @endif                            </div>
                        </td>
                    </tr>
                </table>
            </td>

            <!-- Centered Page Number -->
            <td style="width: 34%; text-align: center; font-size: 10px;">
                {PAGENO}
            </td>

            <!-- Empty Right Side -->
            <td style="width: 33%;"></td>
        </tr>
    </table>
</footer>
