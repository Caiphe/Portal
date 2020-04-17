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
                    <x-accordion :id="$category->slug" :title="$category->title" :link="$category->slug" icon="link">
                        <article>
                            <header>
                                <p>
                                    What credentials do I need once I have subscribed to a product?
                                </p>
                                <span class="tag outline yellow">MTN</span>
                                <span class="tag outline yellow">Advertising</span>
                                <button class="button fab plus"></button>
                            </header>
                            <ul class="content">
                                <li>Subscription Key – received upon subscription to a product. It is used for authenticating the number of API calls. For information about subscription keys, refer to point 2</li>
                                <li>API User and API Key for bearer Oauth 2.0 token. In the sandbox they are self-generated from the APIs. In production these are generated from the partner portal (part of onboarding)</li>
                            </ul>
                        </article>
                    </x-accordion>
                @endforeach
            </div>

            <div class="faq-categories">
                <span>Categories</span>
                <ul>
                    @foreach ($categories as $category)
                        <li>
                            <a href="#{{ $category->slug }}">{{ $category->title }}</a>
                        </li>
                    @endforeach
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
                    <option value="Advertising" selected>
                        Advertising
                    </option>
                    <option value="Customer">
                        Customer
                    </option>
                    <option value="Fintech">
                        Fintech
                    </option>
                    <option value="Messaging">
                        Messaging
                    </option>
                    <option value="SMS">
                        SMS
                    </option>
                    <option value="Tickets">
                        Tickets
                    </option>
                </select>
            </x-panel>

            <div class="contact-form">
                <p>
                    Connect with our developer support team, and other developers who are integrating with MTN Open API using Whatsapp or Skype.
                </p>

                <form action="" method="POST">
                    @csrf

                    <input type="text" name="first_name" placeholder="Enter first name" autocomplete="first_name">
                    <input type="text" name="last_name" placeholder="Enter last name" autocomplete="last_name">
                    <input type="email" name="email" placeholder="Enter email address" autocomplete="email">

                    <textarea name="message" placeholder="Enter message" rows="4"></textarea>

                    <button>Send message</button>
                </form>
            </div>

            <div id="fintech">
                <x-select></x-select>

                <div class="connect">
                    @svg('skype')

                    @svg('whatsapp', '#FFFFFF')
                </div>
            </div>
        </div>
	</section>

@endsection

@pushscript('faq')
<script src="{{ mix('/js/templates/faq/index.js') }}" defer></script>
@endpushscript
