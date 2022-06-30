@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/templates/admin/tasks/index.css') }}">
@endpush

@section('title', 'Task Panel')

@section('content')
    <h1>Task Panel</h1>
    <div class="tasks-container">

        <div class="single-task">
            <div class="panel-headers">
                <div class="header">
                    <p>Opco Admin Request</p>
                    <div class="countrys-list">
                        @svg('country')
                        @svg('country')
                        @svg('country')
                    </div>
                </div>

                <div class="header user-name-block">
                    <a class="user-name">Name Surname if its really long can overflow </a>
                </div>

                <div class="header">
                    <p class="date-requested">24 Mar 2022</p>
                    <button class="view-motivation-button">
                        View motivation @svg('chevron-down', '#0c678f')
                    </button>
                </div>

                <div class="header button-container">
                    <button class="deny-btn">@svg('close', '#000') Deny</button>
                    <button class="approve-btn">
                        @svg('check') Approve
                    </button>
                </div>

            </div>

            <div class="panel">

                <div class="inner-panel-container">
                    <div class="motivation-container">
                        <h4>Motivation</h4>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut
                            labore
                            et
                            dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                            aliquip ex
                            ea commodo consequat.
                        </p>
                    </div>

                    <div class="countries-requested-block">
                        <h4>Countries Requested</h4>

                        <div class="each-country">
                            @svg('country')
                            <span class="country-name">Ghana</span>
                        </div>

                        <div class="each-country">
                            @svg('country')
                            <span class="country-name">Ghana</span>
                        </div>

                        <div class="each-country">
                            @svg('country')
                            <span class="country-name">Ghana</span>
                        </div>

                        <div class="each-country">
                            @svg('country')
                            <span class="country-name">Ghana</span>
                        </div>

                        <div class="each-country">
                            @svg('country')
                            <span class="country-name">Ghana</span>
                        </div>

                        <div class="each-country">
                            @svg('country')
                            <span class="country-name">Ghana</span>
                        </div>

                    </div>
                </div>

                <div class="deny-form-container">
                    <h4>Countries Requested</h4>
                    <p>Tell us why you're denying this Opco Admin access</p>

                    <form class="deny-form" method="POST" action="#">
                        @csrf
                        <textarea name="message" placeholder="Please list a reason here">
                        </textarea>

                        <button type="submit" class="submit-deny">Submit</button>
                    </form>

                </div>

            </div>
        </div>

        <div class="single-task">
            <div class="panel-headers">
                <div class="header">
                    <p>Opco Admin Request</p>
                    <div class="countrys-list">
                        @svg('country')
                        @svg('country')
                        @svg('country')
                    </div>
                </div>

                <div class="header user-name-block">
                    <a class="user-name">Name Surname if its really long can overflow </a>
                </div>

                <div class="header">
                    <p class="date-requested">24 Mar 2022</p>
                    <button class="view-motivation-button">
                        View motivation @svg('chevron-down', '#0c678f')
                    </button>
                </div>

                <div class="header button-container">
                    <button class="deny-btn">@svg('close', '#000') Deny</button>
                    <button class="approve-btn">
                        @svg('check') Approve
                    </button>
                </div>

            </div>

            <div class="panel">

                <div class="inner-panel-container">
                    <div class="motivation-container">
                        <h4>Motivation</h4>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut
                            labore
                            et
                            dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                            aliquip ex
                            ea commodo consequat.
                        </p>
                    </div>

                    <div class="countries-requested-block">
                        <h4>Countries Requested</h4>

                        <div class="each-country">
                            @svg('country')
                            <span class="country-name">Ghana</span>
                        </div>

                        <div class="each-country">
                            @svg('country')
                            <span class="country-name">Ghana</span>
                        </div>

                        <div class="each-country">
                            @svg('country')
                            <span class="country-name">Ghana</span>
                        </div>

                        <div class="each-country">
                            @svg('country')
                            <span class="country-name">Ghana</span>
                        </div>

                        <div class="each-country">
                            @svg('country')
                            <span class="country-name">Ghana</span>
                        </div>

                        <div class="each-country">
                            @svg('country')
                            <span class="country-name">Ghana</span>
                        </div>

                    </div>
                </div>

                <div class="deny-form-container">
                    <h4>Countries Requested</h4>
                    <p>Tell us why you're denying this Opco Admin access</p>

                    <form class="deny-form" method="POST" action="#">
                        @csrf
                        <textarea name="message" placeholder="Please list a reason here">
                        </textarea>

                        <button type="submit" class="submit-deny">Submit</button>
                    </form>

                </div>

            </div>
        </div>

        <div class="single-task">
            <div class="panel-headers">
                <div class="header">
                    <p>Opco Admin Request</p>
                    <div class="countrys-list">
                        @svg('country')
                        @svg('country')
                        @svg('country')
                    </div>
                </div>

                <div class="header user-name-block">
                    <a class="user-name">Name Surname if its really long can overflow </a>
                </div>

                <div class="header">
                    <p class="date-requested">24 Mar 2022</p>
                    <button class="view-motivation-button">
                        View motivation @svg('chevron-down', '#0c678f')
                    </button>
                </div>

                <div class="header button-container">
                    <button class="deny-btn">@svg('close', '#000') Deny</button>
                    <button class="approve-btn">
                        @svg('check') Approve
                    </button>
                </div>

            </div>

            <div class="panel">

                <div class="inner-panel-container">
                    <div class="motivation-container">
                        <h4>Motivation</h4>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut
                            labore
                            et
                            dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                            aliquip ex
                            ea commodo consequat.
                        </p>
                    </div>

                    <div class="countries-requested-block">
                        <h4>Countries Requested</h4>

                        <div class="each-country">
                            @svg('country')
                            <span class="country-name">Ghana</span>
                        </div>

                        <div class="each-country">
                            @svg('country')
                            <span class="country-name">Ghana</span>
                        </div>

                        <div class="each-country">
                            @svg('country')
                            <span class="country-name">Ghana</span>
                        </div>

                        <div class="each-country">
                            @svg('country')
                            <span class="country-name">Ghana</span>
                        </div>

                        <div class="each-country">
                            @svg('country')
                            <span class="country-name">Ghana</span>
                        </div>

                        <div class="each-country">
                            @svg('country')
                            <span class="country-name">Ghana</span>
                        </div>

                    </div>
                </div>

                <div class="deny-form-container">
                    <h4>Countries Requested</h4>
                    <p>Tell us why you're denying this Opco Admin access</p>

                    <form class="deny-form" method="POST" action="#">
                        @csrf
                        <textarea name="message" placeholder="Please list a reason here">
                        </textarea>

                        <button type="submit" class="submit-deny">Submit</button>
                    </form>

                </div>

            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        var viewMotivationBtns = document.getElementsByClassName("view-motivation-button");

        for (var i = 0; i < viewMotivationBtns.length; i++) {
            viewMotivationBtns[i].addEventListener("click", function() {
                var singleTask = this.closest('.single-task');
                singleTask.classList.toggle('active');
            });
        }
    </script>
@endpush
