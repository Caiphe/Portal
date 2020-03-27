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

    <x-heading heading="Apps" tags="SHOW"></x-heading>

    <div class="container" id="app-create">

        <nav>
            <a href="#">
                <span>1</span> App details
            </a>
            <a href="#">
                <span>2</span> Select regions
            </a>
            <a href="#">
                <span>3</span> Add products
            </a>
        </nav>

        <div class="row">
            @svg('app-avatar', '#ffffff')
            <form id="create" action="">

                <div class="group">
                    <label for="name">Name your app *</label>
                    <input type="text" name="name" id="name" placeholder="Enter name">
                </div>

                <div class="group">
                    <label for="name">Callback url *</label>
                    <input type="text" name="name" id="name" placeholder="Enter callback url">
                </div>

                <div class="group">
                    <label for="description">Description *</label>
                    <textarea name="description" id="description" rows="5" placeholder="Enter description"></textarea>
                </div>

                <button class="dark" type="submit">
                    Select regions
                    @svg('arrow-forward', '#ffffff')
                </button>
            </form>
        </div>

        <button type="reset">Cancel</button>
    </div>

@endsection

@push('scripts')
    <script>

    </script>
@endpush
