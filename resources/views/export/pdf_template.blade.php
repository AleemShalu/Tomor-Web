<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            font-family: "Tajawal", sans-serif;
            margin: 0;
            padding: 0;
            background-color: #F1F5F9;
            color: #ffffff; /* All text color set to white */
        }

        header {
            background-color: #F1F5F9;
            color: #000000;
            padding: 15px;
            text-align: center;
            border-bottom: 2px solid #000;
        }

        footer {
            background-color: #161a7c;
            color: #000000;
            text-align: center;
            padding: 10px;
            border-top: 2px solid #000;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .body-table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        .body-table th,
        .body-table td {
            padding: 12px;
            border: 1px solid #ddd;
        }

        .body-table th {
            background-color: #161a7c;
            color: #ffffff;
            text-align: center;
        }

        .body-table td {
            text-align: center;
        }

        .space-top {
            padding-top: 2%;
        }

        .hr-space {
            padding: 4% 0;
        }

        .table-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 80vh;
            text-align: center;
        }

        .content-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .content-table td {
            border: 1px solid #161A7D;
            padding: 12px;
            text-align: center;
            vertical-align: middle;
        }

        .first-column {
            width: 30%;
        }

        .second-column-top,
        .second-column-bottom {
            height: 150px;
        }

        .image-size {
            width: 750px;
            height: 250px;
            background-repeat: no-repeat;
            background-position: center center;
            background-size: cover;
            border: 1px solid #000;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>

<body>
{{--@include('export.components.header')--}}
<div style="margin: 0 20px;">
    <table class="body-table">
        <thead>
        <tr>
            @foreach ($headers as $column)
                <th>{{ __($column) }}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach ($data as $item)
            <tr>
                @foreach ($columns as $column)
                    <td style="color: black;">{{  data_get($item, $column, '-')}}</td>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

{{--@include('export.components.footer')--}}
</body>

</html>
