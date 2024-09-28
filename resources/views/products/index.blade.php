<!DOCTYPE html>
<html>
<head>
    <title>Product List</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <form method="GET" action="{{ route('products.index') }}" id="search-form">
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
        <label for="price_min">Price Min:</label>
        <input type="number" name="price_min" id="price_min">
        <br>
        <label for="price_max">Price Max:</label>
        <input type="number" name="price_max" id="price_max">
        <br>
        <label for="stock_min">Stock Min:</label>
        <input type="number" name="stock_min" id="stock_min">
        <br>
        <label for="stock_max">Stock Max:</label>
        <input type="number" name="stock_max" id="stock_max">
        <br>
        <button type="submit">Search</button>
    </form>

    <table>
        <thead>
            <tr>
                <th><a href="#" class="sort" data-column="id">ID</a></th>
                <th>Image</th>
                <th><a href="#" class="sort" data-column="name">Name</a></th>
                <th><a href="#" class="sort" data-column="price">Price</a></th>
                <th><a href="#" class="sort" data-column="stock">Stock</a></th>
                <th>Manufacturer</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="product-list">
            <!-- Data will be dynamically loaded here -->
        </tbody>
    </table>

    <a href="{{ route('products.create') }}">Add New Product</a>

    <script>
    $(document).ready(function() {
        let sortColumn = 'id';
        let sortDirection = 'desc';

        function loadProducts() {
            $.ajax({
                url: "{{ route('products.search') }}",
                method: 'GET',
                data: {
                    sort_by: sortColumn,
                    sort_direction: sortDirection,
                    // other search parameters
                    ...$('#search-form').serializeArray().reduce((acc, {name, value}) => ({ ...acc, [name]: value }), {})
                },
                success: function(data) {
                    $('#product-list').empty();
                    data.forEach(function(product) {
                        $('#product-list').append(`
                            <tr>
                                <td>${product.id}</td>
                                <td><img src="${product.image}" alt="${product.name}"></td>
                                <td>${product.name}</td>
                                <td>${product.price}</td>
                                <td>${product.stock}</td>
                                <td>${product.company.name}</td>
                                <td>
                                    <a href="/products/${product.id}">Details</a>
                                    <button class="delete-button" data-id="${product.id}">Delete</button>
                                </td>
                            </tr>
                        `);
                    });
                }
            });
        }

        $('.sort').on('click', function(e) {
            e.preventDefault();
            sortColumn = $(this).data('column');
            sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
            loadProducts();
        });

        $('#search-form').on('submit', function(e) {
            e.preventDefault();
            loadProducts();
        });

        $('#product-list').on('click', '.delete-button', function() {
            let productId = $(this).data('id');
            $.ajax({
                url: `/products/${productId}`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        $(`button[data-id="${productId}"]`).closest('tr').remove();
                    } else {
                        alert('{{ config('message.errors.delete_failed') }}');
                    }
                }
            });
        });

        // Initial load of products
        loadProducts();
    });
    </script>
</body>
</html>
