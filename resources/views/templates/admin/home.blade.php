@extends('layouts.admin')

@section('title', 'Home')

@section('content')
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Access</th>
                <th>Environments</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->display_name  }}</td>
                <td>{{ $product->access  }}</td>
                <td>{{ $product->environments }}</td>
                <td>{{ $product->category->title }}</td>
                <td align="center">
                    <a href="{{ route('product.edit', $product->slug) }}">@svg('edit')</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $products->links() }}
@endsection