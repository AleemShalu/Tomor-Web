<header style="border-bottom: 1px solid #000000; padding: 10px; position: relative;">
    <table style="width: 100%; color: black; border-collapse: collapse;">
        <tr>
            <!-- Left Side -->
            <td style="width: 33%; vertical-align: middle; padding: 0;">
                <table style="width: auto; border-collapse: collapse; margin: 0; padding: 0; height: 100%; display: flex; align-items: center;">
                    <tr>
                        <!-- Centered Logo -->
                        <td style="padding: 0; display: flex; justify-content: center; align-items: center; height: 100%;">
                            <a href="#" style="display: flex; align-items: center; margin: 0;">
                                <img src="{{public_path('images/tomor-logo-03.png')}}" alt="Logo"
                                     style="height: 30px; display: block; margin: 0;"/>
                            </a>
                        </td>
                        <!-- Text beside the logo -->
                        <td style="padding: 0; vertical-align: middle;">
                            <div style="margin: 0; padding: 0; line-height: 1;">
                                The report was printed by <span
                                        style="font-weight: bold">{{auth()->user()->name ?? '-'}}</span><br>
                                tomor-sa.com
                            </div>
                        </td>
                    </tr>
                </table>
            </td>


            <!-- Right Side (Empty) -->
            <td style="width: 35%;"></td>


            <td style="width: 25%; vertical-align: middle;">
                <table style="width: 100%; border-collapse: collapse; text-align: center;">
                    <tr>
                        <td style="text-align: left;">
                            <strong>{{ $reportName }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">
                            Date of
                            report: {{ convertDateToRiyadhTimezone( \Carbon\Carbon::now())}}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</header>
