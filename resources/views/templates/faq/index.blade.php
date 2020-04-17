@extends('layouts.master')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/faq/index.css') }}">
@endpush

@section('title','FAQ')

@section('content')
	<x-heading heading="FAQ"></x-heading>

    <section>
        <div class="container">
            <x-action-tab
                link="https://spectrum.chat/mtn-developer-hub"
                text="For more help, connect with us on Spectrum"
                logo="spectrum">
            </x-action-tab>

            <x-action-tab
                link="https://madapi.statuspage.io/"
                title="Network status."
                text="See more on our status page"
                status="green">
            </x-action-tab>
        </div>
    </section>

    <section class="search">
        <div class="container">
            <div class="content">
                <x-panel>
                    <h2>Looking for something specific?</h2>
                    <form action="">
                        <input type="text" name="search" placeholder="Search" autofocus>
                    </form>
                </x-panel>

                @foreach ($categories as $category)
                    <x-accordion :id="$category->id" :title="$category->title" icon="link">
{{--                        {!! $faq->answer !!}--}}
                    </x-accordion>
                @endforeach
            </div>

            <div class="faq-categories">
                <span>Categories</span>
                <ul>
                    <li>
                        <a href="">Authentication</a>
                    </li>
                    <li>
                        <a href="">Callback</a>
                    </li>
                    <li>
                        <a href="">Error code</a>
                    </li>
                    <li>
                        <a href="">Support</a>
                    </li>
                    <li>
                        <a href="">Onboarding</a>
                    </li>
                </ul>
            </div>
        </div>
    </section>

	<section class="contact">
        <div class="container">
            <h1>
                Need more help? Get in touch
            </h1>
            <p>
                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam.
            </p>

            <x-panel>
                <label for="categories"></label>
                <select name="categories" id="categories">
                    <option value="Advertising">
                        Advertising
                    </option>
                </select>
            </x-panel>

            <p>
                Connect with our developer support team, and other developers who are integrating with MTN Open API using Whatsapp or Skype.
            </p>

            <select name="countries" id="countries">
                <option value="Cameroon">Cameroon</option>
            </select>

            <div class="connect">
                @svg('skype', '#FFFFFFF')

                @svg('whatsapp', '#FFFFFF')
            </div>


{{--            <form action="{{url('contact/sendMail')}}" id="contact-form" method="POST">--}}
{{--                @csrf--}}
{{--                @isset($slot)--}}
{{--                    <p class="mb-2">{{$slot}}</p>--}}
{{--                @endisset--}}
{{--                <input type="text" name="first_name" placeholder="Enter first name" autocomplete="first_name">--}}
{{--                <input type="text" name="last_name" placeholder="Enter last name" autocomplete="last_name">--}}
{{--                <input type="email" name="email" placeholder="Enter email address" autocomplete="email">--}}
{{--                <textarea name="message" placeholder="Enter message" rows="4"></textarea>--}}
{{--                <button>Send message</button>--}}
{{--            </form>--}}
        </div>
	</section>

@endsection

@pushscript('faq')
<script src="{{ mix('/js/templates/faq/index.js') }}" defer></script>
@endpushscript
