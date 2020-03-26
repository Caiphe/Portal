@push('styles')
    <link rel="stylesheet" href="/css/templates/apps/_create.css">
@endpush

@extends('layouts.sidebar')

@section('sidebar')
    <x-sidebar-accordion id="sidebar-accordion"
                         :list="
    [ 'Manage' =>
        [
            [ 'label' => 'Profile', 'link' => '#'],
            [ 'label' => 'Approved apps', 'link' => '#'],
            [ 'label' => 'Revoked apps','link' => '#'],
        ],
        'Discover' =>
        [
            [ 'label' => 'Browse all products', 'link' => '#'],
            [ 'label' => 'Working with our products','link' => '#'],
        ]
    ]
    " />
@endsection

@section('content')

    <x-heading heading="Apps" tags="CREATE NEW"></x-heading>

    <div class="container" id="app-create">
        <div class="row">
            <form action="">
                <div>
                    <label for="name">Name your app *</label>
                    <input type="text" name="name" id="name">
                </div>

                <label for="name">Callback url *</label>
                <input type="text" name="name" id="name">

                <label for="description">Description *</label>
                <textarea name="description" id="description"></textarea>

                <button class="dark">Select regions</button>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>

</script>
@endpush
