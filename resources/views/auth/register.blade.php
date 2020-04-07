@extends('layouts.auth')

@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/components/step-wizzard.css') }}">
@endpush

@section('content')
<div class="row m-0">
    <div class="step__wizzard_container left">
        <div class="row m-0 step__wizzard_header">
            <img src="/images/mtn-logo.svg" alt="MTN logo">
            <h4>Developer Portal</h4>
        </div>

        <form class="step__wizzard_content" id="stepWizzardForm" method="POST" action="{{ route('register') }}">
            @csrf
            <div class="step__wizzard_item">
                <div class="intro">
                    <p class="return_user">
                        Already have an account ?
                        <a class="login_link" href="/login" >Login here &#8594;</a>
                    </p>
                    <h2 class="header">Y'ello there!</h2>
                    <p class="text">
                        Let’s get you registered and on your way to building some awesome new apps.
                    </p>
                </div>

                <div class="input_group">
                    <label for="first_name"><strong>What's your first name? *</strong></label>
                    <input class="@error('first_name') is-invalid @enderror" type="text" id="formFirstName" name="first_name" value="{{ old('first_name') }}" required autocomplete="first_name" placeholder="First Name" autofocus />
                    @error('first_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="input_group">
                    <label for="email"><strong>What is your email address? *</strong></label>
                    <input class="@error('email') is-invalid @enderror" type="email" id="formEmail" name="email" value="{{ old('email') }}" required autocomplete="new-email" placeholder="Email Address" />
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="step__wizzard_item">
                <div class="item_content">
                    <div class="input_group">
                        <label><strong>And your last name, <span class="first__name_slot"></span>? *</strong></label>
                        <input class="@error('last_name') is-invalid @enderror" type="text" id="formLastName" name="last_name" value="{{ old('last_name') }}" required autocomplete="last_name" placeholder="Last name" />
                        @error('last_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="step__wizzard_item">
                <div class="item_content">
                    <div class="input_group">
                        <label><strong>And your secret password? *</strong></label>
                        <input class="@error('password') is-invalid @enderror" type="password" id="formPassword" name="password" value="{{ old('password') }}" required autocomplete="new-password" placeholder="Password" onkeyup="checkPasswordStrength(event)"/>
                        <button type="button" class="fab show-password" onclick="togglePasswordVisibility(this)"></button>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="input_group">
                        <label><strong>Can you confirm that please, <span class="first__name_slot"></span>? *</strong></label>
                        <input type="password" id="formPasswordConf" name="password_confirmation" value="{{ old('password_confirmation') }}" required autocomplete="off" placeholder="Confirm Password" />
                        <button type="button" class="fab show-password" onclick="togglePasswordVisibility(this)"></button>
                    </div>

                    <button disabled id="passwordStrengthBtn" class="password_strength grey">Strong Password</button>
                </div>
            </div>

            <div class="step__wizzard_item">
                <label class="step-input-label"><strong>In which countries do you intend to release your applications? *</strong></label>
                <p>
                    Some of our APIs are country specific. By specifying specific countries we can help narrow your search for the right APIs.
                </p>

                <div class="input_group locations">
                    @foreach($locations as $location)
                        <label for="{{$location}}">
                            <input type="checkbox" name="locations[]" value="{{$location}}" id="{{$location}}" autocomplete="off">
                            <img src="/images/locations/{{$location}}.svg" alt="{{$location}}" title="{{$location}}">
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="step__wizzard_item">
                <h4>Please accept our terms and conditions*</h4>
                <div class="terms_and_conditions">
                    <h5>Terms of use for MTN Developer Portal (sandbox version)</h5>
                    <h5><strong>IMPORTANT NOTICE:</strong></h5>
                    <ul>
                        <li>A. This is a test site and MTN will not be responsible for any use thereof or liable to you for any loss or damage you may suffer as a result of using it.</li>
                        <li>B. In these Terms there are also various limitations and exclusions of MTN’s liability, indemnities which are given by you, and disclaimers by MTN of warranties. See clause 12 for more information.</li>
                        <li>C. To the extent that this Agreement or Services provided under this Agreement are governed by the Consumer Protection Act, 2008 (the "Consumer Protection Act"), no provision of this Agreement is intended to contravene the applicable provisions of the Consumer Protection Act, and therefore all provisions of this Agreement must be treated as being qualified, to the extent necessary, to ensure that the applicable provisions of the Consumer Protection Act are complied with</li>
                        <li>
                            1. Acceptance of Terms
                            <p>
                                <strong>By accessing or using the Services, the Developer Site, the Services Documentation or any of the APIs, you are agreeing to these MTN Developer Site Terms of Use (the “Terms”)</strong> and an agreement is created between you and MTN as set out in these Terms.  You also agree to comply with the Terms and that the Terms control your relationship with us regarding the use of the Services and the APIs. So please read all the Terms carefully.
                            </p>
                        </li>
                        <li>
                            2. Key Definitions
                            <p>
                                Headings will not be used in the interpretation of these Terms and, unless expressly provided otherwise, or otherwise required by the context, the following words and expressions shall have the corresponding meaning:
                                <ul>
                                    <li>1. <strong>Affiliate</strong> – in relation to a Party, means all other persons or entities which directly or indirectly (whether through one or more intermediaries or otherwise) Control, or are Controlled by, or are under common Control with, that Party or its successors-in-title from time to time.</li>
                                    <li>2. <strong>Agreement</strong> – the agreement that is created between you and MTN when you access or use the Services, the Developer Site, the Services Documentation or any of the APIs. Such agreement is on the terms and conditions set out in these Terms and the terms and conditions set out in the Data Privacy and Security Policy for MTN Developer Portal which is available as set out on clause 4.</li>
                                    <li>3. <strong>API</strong> – an application programming interface made available by MTN through, or for purposes of accessing, the Developer Site or using the Sandbox. A reference to “APIs” is a reference to the application programming interfaces made available by MTN through, or for purposes of accessing, the Developer Site or using the Sandbox.</li>
                                    <li>4. <strong>API Documentation</strong> –  the documentation, data and information that MTN provides regarding the use of the APIs.</li>
                                    <li>5. <strong>Applicable Law</strong> – all laws, rules, codes, regulations, and formal regulatory guidelines and standards, made by a Government Authority, legislature or other public authority with binding effect in force from time to time (construed having regard to related guidance and codes of practice issued or approved by a Government Authority or other public body) and applicable to a Party and relevant to these Terms.</li>
                                    <li>6. <strong>Application</strong> – any software application, website, or product or service you create or offer.</li>
                                    <li>7. <strong>Confidential Information</strong> – any information of a confidential and/or commercially sensitive nature, howsoever obtained or received and whether or not marked confidential, including (i) all Materials, records, files, analysis, documents, software, computer or electronic data disks or tapes, test data, printouts, processes, designs, file layout, technical bulletins, manuals, diagrams, formulas, research, inventions, patents and discoveries reasonably related to the Parties’ businesses or products and services of the Parties that have not been publicly released, (ii) technical, financial, business plan or customer information, including standard periodic financial statements and analyses, budgets, tax returns, benefit and compensation plans, customer list(s) and contact names, functional and technical specifications; and (iii) other valuable information disclosed by one of the Parties to the other, in whatever form, and shall include proprietary information. For clarity, the “Confidential Information” of MTN shall include the MTN Data and the Confidential Information of all MTN Affiliates.</li>
                                    <li>8. <strong>Claim</strong> –  a claim, action, demand, suit, investigation or proceeding.</li>
                                    <li>9. <strong>Control</strong> – (i) the holding or beneficial ownership of 30 per cent or more of the ordinary shares (or other like instruments) in that party's issued share capital (or like ownership structure), or the holding of a participation interest of 30 per cent or more in that party where it is unincorporated; or (ii) the right or ability to direct or otherwise control or exercise 30 per cent or more of the voting rights attaching to that party's issued ordinary shares (or other like instruments), or the right or ability to direct or otherwise control or exercise the voting rights attaching to a participation interest of 30 per cent or more in that party; or (iii) the right or ability to appoint or remove 30 per cent or more of the board of directors of that party (or such other body legally representing such party) or to appoint or remove individuals able to exercise 30 per cent or more of the votes exercisable at the meetings of board of directors or such party; or (iv) the right or ability to direct or generally manage, or to cause the direction or general management of, affairs of such party; and the terms "Controls" and "Controlled" shall have a corresponding meaning.</li>
                                    <li>10. <strong>Data</strong> – any Materials which are Processed, uploaded, posted, transmitted or otherwise made available by MTN or by users of the Services via the Services, including messages, files, comments, profile information and anything else entered or uploaded into the Service by a user of the Service.</li>
                                    <li>11. <strong>Data Protection Legislation</strong> –  all Applicable Laws regulating the Processing of Personal Information, including privacy laws, data sovereignty laws, anti-spam laws and other similar laws.</li>
                                    <li>12. <strong>Data Subject</strong> – a person, including a natural or artificial person whose Personal Information is Processed.</li>
                                    <li>
                                        13. <strong>Destructive Code</strong> – “viruses”, “trojan horses”, ransom ware, malware, spyware and computer code, instructions, devices or other Materials that:
                                        <ul>
                                            <li>1. e designed to disrupt, disable (in whole or in part), harm or otherwise impede in any manner, including aesthetic disruptions or distortions, the operation or use of the MTN Infrastructure, the Developer Site, the Services, the Applications, the MTN Materials, or the Materials of a Party;</li>
                                            <li>2. would permit an unauthorised party to access the MTN Infrastructure, Developer Site, Services, Application, the MTN Materials, or the Materials of a Party to cause such disablement or impairment;</li>
                                            <li>3. contain any other similar harmful, malicious or hidden procedures, routines or mechanisms; or</li>
                                            <li>4. can cause or allow unauthorised damage or access to, interference with, or loss, theft, destruction or corruption of, MTN Infrastructure, the Developer Site, the Services, Applications, the MTN Materials, or the Materials of a Party. </li>
                                        </ul>
                                    </li>
                                    <li>14. <strong>Develop</strong> – without limitation, to adapt, amend, compile, conceive, create, deliver, develop, acquire, enhance, modify, prepare, supply, and improve, and the terms “Developed” and “Development” shall bear corresponding meanings.</li>
                                    <li>15. <strong>Developer Site</strong> – MTN’s developer site found at [https://developers.mtn.com/  or https://developer.mtn.com/], through which the Sandbox, APIs, and API Documentation are made available by MTN.</li>
                                    <li>16. <strong>End User</strong> – your Personnel and each person that uses an API, or who accesses the Developer Site, or who uses the Services or the API Documentation on your behalf.</li>
                                </ul>
                            </p>
                        </li>
                    </ul>
                </div>
                <div class="switch_container">
                    <x-switch id="termsSwitch"></x-switch>
                    <span class="text">Accept</span>
                </div>
            </div>
        </form>

        <div class="step_wizzard__footer">
            <p id="FormStepErrorMsg" style="display: none; font-size: 16px; text-align: center; color: red; margin: 0px; margin-bottom: 20px;"><i>Please fill in all fields in Step!</i></p>
            <button id="stepWizzardPrevBtn" onclick="nextPrev(-1)" class="dark outline">Back</button>
            <button class="mr-1" id="stepWizzardNextBtn" onclick="nextPrev(1)" >Next</button>
            <button type="submit" onclick="readyToSubmit(event)" id="stepWizardSubmitBtn" disabled>Create New Account</button>
            <p>
                press Enter &crarr;
            </p>
        </div>

        <div class="step__wizzard_progress_bar step__wizzard_bar_container"></div>
        <div id="stepWizzardProgress" class="step__wizzard_progress_bar"></div>
    </div>

    <x-carousel class="step__wizzard_container right" wait="5000" duration="0.34">
        <x-carousel-item class="carousel_item_cnt" style="background-image: url('/images/mtn-carousel-img-01.png');">
            <div class="overlay">
                <h2>Create an account</h2>
                <p>
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr,
                    sed diam nonumy eirmod tempor invidunt ut labore et dolore magna.
                </p>
            </div>
        </x-carousel-item>

        <x-carousel-item class="carousel_item_cnt" style="background-image: url('/images/mtn-carousel-img-02.png');">
            <div>
                <h2>Register today!</h2>
                <p>
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr,
                    sed diam nonumy eirmod tempor invidunt ut labore et dolore magna.
                </p>
            </div>
        </x-carousel-item>

        <x-carousel-item class="carousel_item_cnt" style="background-image: url('/images/mtn-carousel-img-01.png');">
            <div>
                <h2>Create an account</h2>
                <p>
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr,
                    sed diam nonumy eirmod tempor invidunt ut labore et dolore magna.
                </p>
            </div>
        </x-carousel-item>

        <x-carousel-item class="carousel_item_cnt" style="background-image: url('/images/mtn-carousel-img-02.png');">
            <div>
                <h2>Join Us today</h2>
                <p>
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr,
                    sed diam nonumy eirmod tempor invidunt ut labore et dolore magna.
                </p>
            </div>
        </x-carousel-item>
    </x-carousel>
</div>
@endsection

@push('scripts')
    <script src="{{ mix('/js/register.js') }}"></script>
@endpush
