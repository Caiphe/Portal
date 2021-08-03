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
                    <input id="filter-categories" type="text" name="search" placeholder="Search" autofocus autocomplete="off">
                </x-panel>

                @foreach($categories as $category)
                    <x-accordion :id="$category->slug" :title="$category->title" :link="$category->slug" icon="link">
                        @foreach($category->faqs as $catFaq)
                            @php
                                $expand = isset($faq) && $catFaq->id === $faq->id;
                            @endphp
                            <article id="faq-{{$catFaq->id}}" class="faq @if($expand) active expand  @endif">
                                <header>
                                    <p>
                                        {!! $catFaq->question !!}
                                        @if(\Auth::check() && \Auth::user()->can('view-admin'))
                                        <a href="{{ route('admin.faq.edit', $catFaq->slug) }}" class="edit button small dark outline ml-1">EDIT</a>
                                        @endif
                                    </p>
                                    <button class="button fab plus"></button>
                                </header>
                                <ul class="content @if($expand) active expand  @endif">
                                    <li>{!! $catFaq->answer  !!}</li>
                                </ul>
                            </article>
                        @endforeach
                    </x-accordion>
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

            <form action="{{ route('contact.send') }}" method="POST" id="faq-contact-form">
                @csrf

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

                <div style="text-align: left">
                    <input type="text" name="username" placeholder="Enter a username" autocomplete="off">
                    <small style="color: red"></small>
                </div>
                <div style="text-align: left">
                    <input type="text" name="first_name" placeholder="Enter first name" autocomplete="first_name">
                    <small style="color: red"></small>
                </div>
                <div style="text-align: left">
                    <input type="text" name="last_name" placeholder="Enter last name" autocomplete="last_name">
                    <small style="color: red"></small>
                </div>
                <div style="text-align: left">
                    <input type="email" name="email" placeholder="Enter email address" autocomplete="email">
                    <small style="color: red"></small>
                </div>
                <div style="text-align: left">
                    <textarea name="message" placeholder="Enter message" rows="4"></textarea>
                    <small style="color: red"></small>
                </div>

                <button>Send message</button>
            </form>
        </div>
	</section>

@endsection

@pushscript('faq')
<script>
(function(){
    var search = document.getElementById('filter-categories');
    var accordionTitles = document.querySelectorAll('.accordion .title');
    var faqDict = @json($faqs);
    var accordionContents = document.querySelectorAll('article header');
    var timeOut = null;

    search.addEventListener('keyup', searchFaq);

    for (var i = 0; i < accordionTitles.length; i++) {
        accordionTitles[i].addEventListener('click', toggleAccordion)
    }

    function toggleAccordion(event) {
        var accordion = event.currentTarget;
        var children = accordion.parentNode.children;

        for (var i = 1; i < children.length; i++) {
            children[i].classList.toggle('active');
            children[i].classList.toggle('expand');
        }
    }

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

    function searchFaq() {
        if(timeOut) window.clearTimeout(timeOut);
        timeOut = window.setTimeout(filterCategories.bind(this), 720);
    }

    function filterCategories() {
        var filter = this.value;
        var accordions = document.querySelectorAll('.accordion');
        var faqs = document.querySelectorAll('.faq');
        var found = [];
        var categories = [];
        var category = "";
        var reg = new RegExp(filter, 'i');
        var i = 0;

        for (var i = faqDict.length - 1; i >= 0; i--) {
            for(var column in faqDict[i]){
                if(!reg.test(faqDict[i][column])) continue;

                found.push('faq-' + faqDict[i].id);

                if(categories.indexOf(faqDict[i]['category_cid']) === -1){
                    categories.push(faqDict[i]['category_cid']);
                }

                break;
            }
        }

        for (i = accordions.length - 1; i >= 0; i--) {
            if(categories.indexOf(accordions[i].id) === -1) {
                accordions[i].classList.add('hide-accordion');
            } else {
                accordions[i].classList.remove('hide-accordion');
            };
        }

        for (i = faqs.length - 1; i >= 0; i--) {
            if(filter === ""){
                faqs[i].classList.remove('hide-accordion');
                faqs[i].classList.remove('active');
                faqs[i].classList.remove('expand');
            } else if(found.indexOf(faqs[i].id) === -1) {
                faqs[i].classList.add('hide-accordion');
                faqs[i].classList.remove('active');
                faqs[i].classList.remove('expand');
            } else {
                faqs[i].classList.remove('hide-accordion');
                faqs[i].classList.add('active');
                faqs[i].classList.add('expand');
            };
        }
    }

    function showMessage(input, message, type) {
        const msg = input.parentNode.querySelector("small");
        msg.innerText = message;

        input.className = type ? "success" : "error";
        return type;
    }

    function showError(input, message) {
        return showMessage(input, message, false);
    }

    function showSuccess(input) {
        return showMessage(input, "", true);
    }

    function hasValue(input, message) {
        if (input.value.trim() === "") {
            return showError(input, message);
        }
        return showSuccess(input);
    }

    function validateEmail(input, requiredMsg, invalidMsg) {
        if (!hasValue(input, requiredMsg)) {
            return false;
        }

        const emailRegex =
            /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

        const email = input.value.trim();
        if (!emailRegex.test(email)) {
            return showError(input, invalidMsg);
        }
        return true;
    }

    const form = document.querySelector("#faq-contact-form");

    const FIRSTNAME_REQUIRED = "Please enter your first name";
    const LASTNAME_REQUIRED = "Please enter your last name";
    const EMAIL_REQUIRED = "Please enter your email";
    const EMAIL_INVALID = "Please enter a correct email address format";
    const MESSAGE_REQUIRED = "Please enter a message";

    addEvent(form, "submit", function () {
        let formErrorsPresent = false;

        if (hasValue(form.elements["first_name"], FIRSTNAME_REQUIRED)) {
            formErrorsPresent = true;
        }

        if (hasValue(form.elements["last_name"], LASTNAME_REQUIRED)) {
            formErrorsPresent = true;
        }

        if (validateEmail(form.elements["email"], EMAIL_REQUIRED, EMAIL_INVALID)) {
            formErrorsPresent = true;
        }

        if (hasValue(form.elements["message"], MESSAGE_REQUIRED)) {
            formErrorsPresent = true;
        }

        return formErrorsPresent;
    });

    function addEvent(element, event, callback) {
        let previousEventCallBack = element["on"+event];
        element["on"+event] = function (e) {
            let output = callback(e);

            if (output === false) return false;

            if (typeof previousEventCallBack === 'function') {
                output = previousEventCallBack(e);
                if(output === false) return false;
            }
        }
    };
}());
</script>
@endpushscript
