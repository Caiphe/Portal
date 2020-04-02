@extends('layouts.master')

@section('title', $content['title'])

@section('content')
    <x-heading :heading="$content['title']"/>
    {!!$content['body']!!}
@endsection