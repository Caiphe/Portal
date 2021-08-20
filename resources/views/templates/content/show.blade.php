@extends('layouts.master')

@section('title', $content['title'])

@section('content')
    <x-heading :heading="$content['title']" :edit="route('admin.page.edit', $content->slug)"/>
    <div class="constrained auto-x">{!!$content['body']!!}</div>
@endsection