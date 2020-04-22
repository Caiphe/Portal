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
                    <input id="filter-categories" type="text" name="search" placeholder="Search" autofocus>
                </x-panel>

                @foreach($categories as $category)
                    @if(!$category->faqs->isEmpty())
                        <x-accordion :id="$category->slug" :title="$category->title" :link="$category->slug" icon="link">
                            @foreach($category->faqs as $faq)
                                <article>
                                    <header>
                                        <p>
                                            {!! $faq->question !!}
                                        </p>
                                        <button class="button fab plus"></button>
                                    </header>
                                    <ul class="content">
                                        <li>{!! $faq->answer  !!}</li>
                                    </ul>
                                </article>
                            @endforeach
                        </x-accordion>
                    @endif
                @endforeach
            </div>

            <div class="faq-categories">
                <span>Categories</span>
                <ul>
                    @foreach ($categories as $category)
                        @if(!$category->faqs->isEmpty())
                            <li>
                                <a href="#{!! $category->slug  !!}">{!! $category->title !!}</a>
                            </li>
                        @endif
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

            <x-panel>
                <label for="categories"></label>
                <select name="categories" id="categories">
                    <option value="Advertising">
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

                <form action="{{ route('contact.send') }}" method="POST">
                    @csrf

                    <input type="text" name="first_name" placeholder="Enter first name" autocomplete="first_name">
                    <input type="text" name="last_name" placeholder="Enter last name" autocomplete="last_name">
                    <input type="email" name="email" placeholder="Enter email address" autocomplete="email">

                    <textarea name="message" placeholder="Enter message" rows="4"></textarea>

                    <button>Send message</button>
                </form>
            </div>

            <div id="fintech">
                <p>
                    Connect with our developer support team, and other developers who are integrating with MTN Open API using Whatsapp or Skype.
                </p>

                <x-select></x-select>

                <div class="connect">
                    <a class="skype" href="" target="_blank" rel="noopener noreferrer">
                        @svg('skype')
                    </a>

                    <a class="whatsapp" href="" target="_blank" rel="noopener noreferrer">
                        @svg('whatsapp')
                    </a>
                </div>
            </div>
        </div>
	</section>

@endsection

@pushscript('faq')
<script>
    var accordions = document.querySelectorAll('.accordion .title');

    for (var i = 0; i < accordions.length; i++) {
        accordions[i].addEventListener('click', toggleAccordion)
    }

    function toggleAccordion(event) {
        var accordion = event.currentTarget;
        var children = accordion.parentNode.children;

        for (var i = 1; i < children.length; i++) {
            children[i].classList.toggle('active');
            children[i].classList.toggle('expand');
        }
    }

    var accordionContents = document.querySelectorAll('article header');

    for (var j = 0; j < accordionContents.length; j++) {
        accordionContents[j].addEventListener('click', toggleAccordionContent)
    }

    function toggleAccordionContent(event) {
        var article = event.currentTarget;

        article.nextElementSibling.classList.toggle('expand');

        if(article.nextElementSibling.classList.contains('expand')) {
            article.classList.add('bottom');
        } else {
            article.classList.remove('bottom');
        }

        article.querySelector('button').classList.toggle('plus');
        article.querySelector('button').classList.toggle('minus');
    }

    var select = document.getElementById('categories');
    var value = document.getElementById('categories').value;

    select.value = 'Advertising';
    select.addEventListener('change', handleSelectCategory);

    function handleSelectCategory(event) {
        var form = document.querySelector('.contact-form');
        var fintech = document.getElementById('fintech');

        for(var i = 0, j = select.options.length; i < j; ++i) {
            if(select.options[i].innerHTML === value) {
                select.selectedIndex = i;
                break;
            }
        }

        if (event.target.value === 'Fintech') {
            form.classList.add('hide');
            fintech.classList.add('show');
        } else {
            form.classList.remove('hide');
            fintech.classList.remove('show');
        }
    }

    var country = document.getElementById('countries');
    country.options.selectedIndex = 0;

    country.addEventListener('change', handleSelectCountry);

    function handleSelectCountry(event) {
        var selected = event.currentTarget;
        var selectedCountry = selected.options[selected.selectedIndex];

        document.querySelector('.connect').style.display = 'block';
        document.querySelector('.skype').href = selectedCountry.dataset.skype;
        document.querySelector('.whatsapp').href = selectedCountry.dataset.whatsapp;
    }

    document.getElementById("filter-categories").addEventListener("keyup", filterCategories);

    var faqDict = {
        'faq-1': [
            'Authentication',
            'Is the API down?',
            'If you need verify if the MTN API Platform is up and responsive, or perhaps down due to maintenance, then check out the status page'
        ],
        'faq-2': [
            'Onboarding',
            'Do you have sample or reference applications that could demonstrate some API calls for me?',
            'Stay posted at our GitHub to see various reference helper applications and SDKs.'
        ],
        'faq-3': [
            'Support',
            'I\'m looking for a specific API functionality – how do I know if you offer it?',
            'Take a look at our products page – it will let you know what APIs are available on a market-by-market basis. If you have a really strong business case for a new API we\'d love to hear about it! Send us a message through our contact us page.'
        ],
        'faq-4': [
            'Authentication',
            'What system of authorization do you use for your APIs, and how do I get authorized to make calls?',
            'We currently have two authorisation mechanisms: API Key, and OAuth. Most of the MTN APIs use API Key today to support legacy apps, but is swtiching over to OAuth. Each API products page will specify what authorisation mechanisms each API uses. API Key uses the x-api-key header, which you can get from the apps section on your profile, under Consumer Key. We use a standard OAuth 2.0 scheme for authorization. To make calls, check out our OAuth page to get information on implementing a 2-legged and 3-legged OAuth flow.'
        ],
        'faq-5': [
            'Onboarding',
            'How do I move my application to production?',
            'We\'re excited to see your creation! If you\'ve done some testing and have a valid prototype or idea worked out, check out our contact us page and fill out the form. We\'ll engage your team and start vetting you for production access.'
        ],
        'faq-6': [
            'Support',
            'I\'ve forgotten my User ID and my Password. How do I recover them?',
            'Head to the login page and hit the forgot User ID/Password.'
        ],
        'faq-7': [
            'Support',
            'I have an idea for an API that would really enable my product. Can MTN help me?',
            'Please let us know at our Contact Us page! We are always looking for new ways to expose APIs that enable the financial technology space and create new opportunities.'
        ],
        'faq-8': [
            'Onboarding',
            'What does it cost me?',
            'There are no fees currently to access our sandbox.  If we allow you to move beyond the sandbox, at that time we can discuss next steps and pricing.'
        ],
        'faq-9': [
            'Onboarding',
            'What functionality is available in the sandbox?',
            'Our functionality varies from region to region – though we provide simulated access to our points platform, customer profiles, accounts, and transactions across all regions. To see if your desired functionality is available in your product\'s region, be sure to check out our API catalog and documentation. Please be reminded that this a sandbox, which means a test environment, that only uses dummy data.'
        ],
        'faq-10': [
            'Onboarding',
            'What kind of data and access do I get in the portal?',
            'The MTN Developer portal consists of: API Products Catalogue - listing the different APIs that can be used, the related documentation, and a way to "Try it Out", test out the APIs directly on the portal. User Profiles - allowing developers to Register, and create apps that use APIs, and the the related credentials/keys for those APIs. ‘sandbox\', which allows you to make API calls that are the same in form and function to our production environments. It contains mock test data so that you can prototype your application as if it were the real thing. We keep our public APIs sandboxed to protect our clients\' data and validate products before moving them to production.'
        ],
        'faq-11': [
            'Onboarding',
            'Get started',
            'Read the Welcome page, then head over to Things every developer should know.'
        ]
    }

    function filterCategories() {
        var categories = document.querySelectorAll(".accordion");
        var filter = document.getElementById("filter-categories").value;
        var match = new RegExp(filter, "gi");

        var found = [];

        for (var key in faqDict) {
            var value = faqDict[key];

            found.push(value)
        }

        console.log(found)

        for (var j = 0; j < categories.length; j++) {
            categories[j].style.display = "none";

            textValid = filter === "" || categories[j].dataset.category.match(match) || inArray(found, filter);

            if (textValid) categories[j].style.display = "flex";

            categories[j].querySelector('svg').classList.add('active');
            categories[j].querySelector('article').classList.add('expand');

            if (filter === "") {
                categories[j].querySelector('svg').classList.remove('active');
                categories[j].querySelector('article').classList.remove('expand');
            }
        }
    }

    function sentenceCase(str) {
        return str.replace(/[a-z]/i, function (letter) {
            return letter.toUpperCase();
        }).trim();
    }

    function inArray(haystack, needle) {
        var found = false;
        for (var i = 0; i < haystack.length; i++) {
            if(haystack[i].indexOf(needle) !== -1) {
                found = true;
                break;
            }
        }
        return found;
    }

</script>
@endpushscript
