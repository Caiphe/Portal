@push('styles')
    <link rel="stylesheet" href="{{mix('/css/templates/apps/create.css')}}">
@endpush

@extends('layouts.sidebar')

@section('sidebar')
    <x-sidebar-accordion id="sidebar-accordion" active="/apps" :list="
    [ 'Manage' =>
        [
            [ 'label' => 'My profile', 'link' => '/profile'],
            [ 'label' => 'My apps', 'link' => '/apps'],
            [ 'label' => 'My teams', 'link' => '/teams/create'],
        ],
        'Discover' =>
        [
            [ 'label' => 'Browse all products', 'link' => '/products'],
            [ 'label' => 'Working with our products','link' => '/getting-started'],
        ]
    ]
    " />
@endsection

@section('title')
    Edit {{$data['display_name']}}
@endsection

@section('content')
    <div class="content apps-edit">

        <div class="ly-40">
            <h1 class="main-heading">Edit application</h1>

            <form
                id="form-edit-app"
                class="create-app-form app-form"
                method="PUT"
                action="{{ route('app.update', $data) }}"
                data-redirect="{{ route('app.index') }}"
                data-formtype="edit"
            >
                @csrf

                @include('templates.apps.partials.edit-form')

            </form>

        </div>

        <div class="create-form-actions">
            <div class="first">
                <p><strong>Update the application</strong></p>
                <p>Complete all required details before completion.</p>
            </div>
            <div class="second">
                <div class="form-actions">
                    <a type="button"
                        class="button dark outline back"
                        id="cancel"
                        data-back-url="{{ route('app.index') }}"
                    >Cancel</a>
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
    <script src="{{ mix('/js/templates/apps/edit.js') }}" defer></script>
@endpush
