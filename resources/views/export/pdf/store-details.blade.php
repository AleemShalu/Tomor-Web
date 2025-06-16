<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$reportName ?? 'Store Report'}}</title>
    <style>
    
    </style>
</head>
<body>
<div class="container">
    <h1 class="title-report">{{$reportName ?? 'Store Report'}}</h1>

    <div class="section">
        <h2 class="section-title">Store Details</h2>
        <table class="report-table">
            <tr>
                <th>Commercial Name (EN)</th>
                <td>{{$data->commercial_name_en}}</td>
                <th>Commercial Name (AR)</th>
                <td>{{$data->commercial_name_ar}}</td>
            </tr>
            <tr>
                <th>Short Name (EN)</th>
                <td>{{$data->short_name_en}}</td>
                <th>Short Name (AR)</th>
                <td>{{$data->short_name_ar}}</td>
            </tr>
            <tr>
                <th>Description (EN)</th>
                <td>{{$data->description_en}}</td>
                <th>Description (AR)</th>
                <td>{{$data->description_ar}}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{$data->email}}</td>
                <th>Contact No</th>
                <td>{{$data->dial_code}} {{$data->contact_no}}</td>
            </tr>
            <tr>
                <th>Tax ID Number</th>
                <td>{{$data->tax_id_number}}</td>
                <th>Commercial Registration No</th>
                <td>{{$data->commercial_registration_no}}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2 class="section-title">Owner Details</h2>
        <table class="report-table">
            <tr>
                <th>Name</th>
                <td>{{$data->owner->name}}</td>
                <th>Email</th>
                <td>{{$data->owner->email}}</td>
            </tr>
            <tr>
                <th>Contact No</th>
                <td>{{$data->owner->dial_code}} {{$data->owner->contact_no}}</td>
                <th>Status</th>
                <td>{{$data->owner->status == 1 ? 'Active' : 'Inactive'}}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2 class="section-title">Branches</h2>
        <table class="report-table">
            <thead>
            <tr>
                <th>Name (EN)</th>
                <th>Name (AR)</th>
                <th>Email</th>
                <th>Contact No</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data->branches as $branch)
                <tr>
                    <td>{{$branch->name_en}}</td>
                    <td>{{$branch->name_ar}}</td>
                    <td>{{$branch->email}}</td>
                    <td>{{$branch->dial_code}} {{$branch->contact_no}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2 class="section-title">Bank Accounts</h2>
        <table class="report-table">
            <thead>
            <tr>
                <th>Account Holder Name</th>
                <th>IBAN Number</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data->bank_accounts as $account)
                <tr>
                    <td>{{$account->account_holder_name}}</td>
                    <td>{{$account->iban_number}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2 class="section-title">Employees</h2>
        <table class="report-table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Contact No</th>
                <th>Roles</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data->employees as $employee)
                <tr>
                    <td>{{$employee->name}}</td>
                    <td>{{$employee->email}}</td>
                    <td>{{$employee->dial_code}} {{$employee->contact_no}}</td>
                    <td>
                        @foreach($employee->employee_roles as $role)
                            {{$role->name_en}}<br>
                        @endforeach
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2 class="section-title">Products</h2>
        <table class="report-table">
            <thead>
            <tr>
                <th>Product Name (EN)</th>
                <th>Product Name (AR)</th>
                <th>Category (EN)</th>
                <th>Category (AR)</th>
                <th>Price</th>
                <th>Availability</th>
                <th>Images</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data->products as $product)
                <tr>
                    <td>
                        @foreach($product->translations as $translation)
                            @if($translation->locale == 'en')
                                {{$translation->name}}
                            @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach($product->translations as $translation)
                            @if($translation->locale == 'ar')
                                {{$translation->name}}
                            @endif
                        @endforeach
                    </td>
                    <td>{{$product->product_category->category_en}}</td>
                    <td>{{$product->product_category->category_ar}}</td>
                    <td>{{$product->unit_price}}</td>
                    <td>{{$product->availability}}</td>
                    <td class="product-images">
                        @foreach($product->images as $image)
                            <img src="{{ public_path('storage/'.$image->url) }}" width="50px" alt="Product Image">
                        @endforeach
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2 class="section-title">Business Type</h2>
        <table class="report-table">
            <tr>
                <th>Business Type (EN)</th>
                <td>{{$data->business_type->name_en}}</td>
                <th>Business Type (AR)</th>
                <td>{{$data->business_type->name_ar}}</td>
            </tr>
        </table>
    </div>

</div>
</body>
</html>
