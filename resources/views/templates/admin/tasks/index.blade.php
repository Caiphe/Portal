@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/templates/admin/tasks/index.css') }}">
@endpush

@section('title', 'Task Panel')

@section('content')
    <h1>Task panel</h1>
    <div class="tasks-container">

        @foreach ($opco_role_requests as $request)
        <div class="single-task">
            <div class="panel-headers">
                <div class="header">
                    <p>Opco admin request</p>
                    <div class="countries-list">
                        @foreach (explode(',', $request->countries) as $country)
                            <img class="country-flag" src="/images/locations/{{$country}}.svg" alt="{{$country}}" title="{{$country}}">
                        @endforeach

                    </div>
                </div>

                <div class="header user-name-block">
                    <a class="user-name" href="{{ route('admin.user.edit', $request->user->id) }}">
                        {{ $request->user->first_name }}  {{ $request->user->last_name }}
                    </a>
                </div>

                <div class="header">
                    <p class="date-requested">{{ $request->created_at->format('d M Y') }}</p>
                    <button class="view-motivation-button">
                        <span class="view-hide">View </span> &nbsp; motivation @svg('chevron-down', '#0c678f')
                    </button>
                </div>

                <div class="header button-container">
                    <button class="deny-btn">@svg('close', '#000') Deny</button>

                    <x-dialog-box dialogTitle="Reason for denial" class="deny-role-modal">

                        <form class="dialog-custom-form deny-form" method="post" action="{{ route('admin.opco.deny', $request->id) }}">
                            @csrf
                            <input type="hidden" name="request_id" value="{{ $request->id }}" />
                            <p class="dialog-text-padding">Tell us why you're denying this Opco admin access</p>
                            <textarea name="message" rows="4" cols="10" placeholder="Please list a reason here"></textarea>
                            <div class="form-team-leave bottom-shadow-container button-container">
                                <button type="submit" class="submit-deny">Submit</button>
                            </div>
                        </form>
                    </x-dialog-box>

                    <form class="approval-form" name="approve-form-{{ $request->id }}" method="POST" action="{{ route('admin.opco.approve', $request->id) }}">
                        @csrf
                        <input type="hidden" name="request_id" value="{{ $request->id }}" />
                        <button type="sbmit" class="approve-btn" data-request-id="{{ $request->id }}" for="approve-form-{{ $request->id }}">
                            <img src="/images/check.png"> Approve
                        </button>

                        <div class="approval-form-container"></div>
                    </form>
                   
                </div>

            </div>

            <div class="panel">
                <div class="inner-panel-container">
                    <div class="motivation-container">
                        <h4>Motivation</h4>
                        <p>{{ $request->message }}
                        </p>
                    </div>

                    <div class="countries-requested-block">
                        <h4>County requested</h4>

                        @foreach (explode(',', $request->countries) as $country)
                            <div class="each-country">
                                <img class="country-flag" src="/images/locations/{{ $country }}.svg" alt="{{ $country }}" title="{{ $country }}">
                                @foreach ($countries as $location)
                                    @if($location->code === $country )
                                        <span class="country-name">{{ $location->name }}</span>
                                    @endif
                                @endforeach
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>   
        @endforeach

        @if(count($opco_role_requests) < 1)
            <p class="no-tasks">You have no tasks</p>
        @endif

    </div>
@endsection

@push('scripts')
    <script src="{{ mix('/js/templates/admin/task/index.js') }}" defer></script>
@endpush
