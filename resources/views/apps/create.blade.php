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

        <nav>
            <a href="#" class="active">
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

            <form action="">
{{--                @svg('app-avatar', '#ffffff')--}}
{{--                <div>--}}
{{--                    <div class="group">--}}
{{--                        <label for="name">Name your app *</label>--}}
{{--                        <input type="text" name="name" id="name" placeholder="Enter name">--}}
{{--                    </div>--}}

{{--                    <div class="group">--}}
{{--                        <label for="name">Callback url *</label>--}}
{{--                        <input type="text" name="name" id="name" placeholder="Enter callback url">--}}
{{--                    </div>--}}

{{--                    <div class="group">--}}
{{--                        <label for="description">Description *</label>--}}
{{--                        <textarea name="description" id="description" rows="5" placeholder="Enter description"></textarea>--}}
{{--                    </div>--}}

{{--                    <button class="dark" type="submit">--}}
{{--                        Select regions--}}
{{--                        @svg('arrow-forward', '#ffffff')--}}
{{--                    </button>--}}
{{--                </div>--}}

                <div>
                    <p style="font-size: 16px;">Select the regions you would like to associate with your app *</p>

                    <div class="buttons">
                        <button class="dark outline">Back</button>
                        <button class="dark">
                            Add products
                            @svg('arrow-forward', '#ffffff')
                        </button>
                    </div>
                </div>

            </form>
        </div>

        <button type="reset">Cancel</button>
    </div>

@endsection

@push('scripts')
<script>
    var state = document.querySelector('form .active');
</script>
@endpush
