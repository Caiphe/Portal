@extends('layouts.admin')

@section('title', 'Searching for "' . Request::get('q', '') . '"')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/search.css') }}">
@endpush

@section('content')
    <form action="{{route('admin.search')}}">
        <input class="search" name="q" placeholder="Search term" autofocus>
    </form>
    @if(!empty($results))
    <p class="tally"><strong>{{$total}}</strong> results for <strong>{{$searchTerm}}</strong></p>
    @endif
    <div class="search-results cols">
        @foreach($results as $result)
        <x-card-search :title="$result['title']" :link="$result['link']">{!!$result['description']!!}</x-card-search>
        @endforeach
    </div>
    @if($pages > 1)
    <ul class="pagination">
        @if($page !== 0)
        <li><a href="/search?q={{$searchTerm}}&page={{$page}}">@svg('chevron-left')</a></li>
        @endif
        @for ($i = 0; $i < $pages; $i++)
        <li><a @if($page === $i) class="current" @endif href="/search?q={{$searchTerm}}&page={{$i+1}}">{{$i+1}}</a></li>
        @endfor
        @if($page + 1 !== $pages)
        <li><a href="/search?q={{$searchTerm}}&page={{$page+2}}">@svg('chevron-right')</a></li>
        @endif
    </ul>
    @endif
@endsection