@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/templates/apps/create.css') }}">
@endpush

@extends('layouts.sidebar')

@section('sidebar')
    <x-sidebar-accordion id="sidebar-accordion" active="/apps" :list="
    [ 'Manage' =>
        [
            [ 'label' => 'My profile', 'link' => '/profile'],
            [ 'label' => 'My apps', 'link' => '/apps'],
            [ 'label' => 'My teams', 'link' => '/teams'],
        ],
        'Discover' =>
        [
            [ 'label' => 'Browse all products', 'link' => '/products'],
            [ 'label' => 'Working with our products','link' => '/getting-started'],
        ]
    ]
    "/>
@endsection

@section('title')
    Create app
@endsection

@section('content')
    <x-twofa-warning class="tall"></x-twofa-warning>
    <div class="content">
        <div class="ly-40">
            <h1 class="main-heading">Create a new application</h1>
            <form id="create-app-form"
                  class="create-app-form app-form"
                  action="{{ route('app.store') }}"
                  data-redirect="{{ route('app.index') }}"
                  method="POST"
                  data-formtype="create"
            >
                @csrf

                @include('templates.apps.partials.create-form')

            </form>
        </div>
        <div class="create-form-actions">
            <div class="first">
                <p><strong>Create a new application</strong></p>
                <p>Complete all required details before completion.</p>
            </div>
            <div class="second">
                <div class="form-actions">
                    <button type="button"
                            class="button dark outline back"
                            id="cancel"
                            data-back-url="{{ route('app.index') }}"
                    >Cancel</button>
                    <button
                        type="submit"
                        class="button primary"
                        id="complete"
                    >Complete</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ mix('/js/components/app-validate-fields.js') }}" defer></script>
    <script src="{{ mix('/js/templates/apps/create.js') }}" defer></script>
@endpush
