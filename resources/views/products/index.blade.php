<!DOCTYPE html>
<html>
<head>
    <title>Product List</title>
</head>
<body>
    <form method="GET" action="{{ route('products.index') }}">
        <label for="name">Product Name:</label>
        <input type="text" name="name" id="name">
        <br>
        <label for="manufacturer">Manufacturer:</label>
        <select name="manufacturer" id="manufacturer">
            <option value="">--Select Manufacturer--</option>
            @foreach($companies as $company)
                <option value="{{ $company->id }}">{{ $company->name }}</option>
            @endforeach
        </select>
        <br>
        <button type="submit">Search</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Manufacturer</th>
            <th>Actions</th>
        </tr>
        @foreach($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td><img src="{{ $product->image_url }}" alt="{{ $product->name }}"></td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->price }}</td>
                <td>{{ $product->stock }}</td>
                <td>{{ $product->company->name }}</td>
                <td>
                    <a href="{{ route('products.show', $product->id) }}">Details</a>
                    <form method="POST" action="{{ route('products.destroy', $product->id) }}" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
    <a href="{{ route('products.create') }}">Add New Product</a>
</body>
</html>
