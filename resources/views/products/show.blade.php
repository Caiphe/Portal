@extends('layouts.sidebar')

@section('sidebar')
    This is the sidebar
@endsection

@section('content')
    <x-heading :heading="$product->display_name" fab="dark plus"/>

    <div>this is some content</div>
@endsection