@extends('layouts.sidebar')

@section('sidebar')
    This is the sidebar
@endsection

@section('content')
    <x-heading :heading="$product->display_name" tags="hello, wolrd"/>

    <div>this is some content</div>
@endsection