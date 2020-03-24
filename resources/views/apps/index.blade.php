@push('styles')
    <link rel="stylesheet" href="/css/templates/apps/_index.css">
@endpush

@extends('layouts.sidebar')

@section('sidebar')
    Sidebar content
@endsection

@section('content')

    <x-heading heading="Apps" tags="DASHBOARD">
        <button class="outline dark">Create new</button>
    </x-heading>

    <div class="container">


        <div class="row">
            @forelse($apps['app'] ?? '' as $app)
                <div class="app">
                    {{ $app['name'] }}
                </div>
            @empty
                <div class="col-12">
                    @svg('app', '#ffffff')
                    <h1>Looks like you don’t have any apps yet.</h1>
                    <p>Fortunately, it’s very easy to create one.</p>

                    <button class="outline dark">Create app</button>
                </div>
            @endforelse
        </div>

    </div>

@endsection
