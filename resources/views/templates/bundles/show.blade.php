@extends('layouts.sidebar')

@push('styles')
<link rel="stylesheet" href="{{mix('/css/templates/bundles/show.css')}}">
@endpush

@section('title', $bundle['display_name'])

@section('banner')
<div id="banner" style="background-image: url({{$bundle->banner}});"></div>
@endsection

@section('sidebar')
<x-sidebar-accordion id="sidebar-accordion" :active="'/' . request()->path()" :list="$sidebar" />
@endsection

@section('content')
    <div class="heading-box">
        <h1>{{$bundle->display_name}}</h1>
        <p class="t-pxl">{{$bundle->description}}</p>
        <a href="/" class="button yellow outline">Subscribe</a>
    </div>
    <div class="price-box">
        <h3>$6,250</h3>
        <small>PER MONTH</small>
        <p>25,000<sup>1</sup></p>
        <small>transactions per month</small>
        <p>0.31¢</p>
        <small>per transaction credit</small>
        <p>Features</p>
        <p>
            No monthly contracts<br>
            Business support<br>
            Online Technical Support<br>
            Other premium Servicess<br>
        </p>
    </div>
@endsection