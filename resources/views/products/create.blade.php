<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
</head>
<body>
    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
        @csrf
        <label for="name">Product Name:</label>
        <input type="text" name="name" id="name" required>
        <br>
        <label for="company_id">Manufacturer:</label>
        <select name="company_id" id="company_id" required>
            @foreach($companies as $company)
                <option value="{{ $company->id }}">{{ $company->name }}</option>
            @endforeach
        </select>
        <br>
        <label for="price">Price:</label>
        <input type="text" name="price
