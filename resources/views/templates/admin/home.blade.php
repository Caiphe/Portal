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
                <td>
                    <a href="{{ route('admin.product.edit', $product->slug) }}">{{ $product->display_name  }}</a>
                </td>
                <td>{{ $product->access  }}</td>
                <td>{{ $product->environments }}</td>
                <td>{{ $product->category->title }}</td>
                <td align="center">
                    <a href="{{ route('admin.product.edit', $product->slug) }}">@svg('edit')</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $products->links() }}
@endsection