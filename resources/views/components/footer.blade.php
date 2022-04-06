@push('styles')
<link rel="stylesheet" href="{{ mix('/css/components/footer.css') }}">
@endpush

<footer id="footer">
    @svg('curve', '#1a1a1a')
    <div class="footer-inner container">
        <p>
            &copy; {{date("Y")}} MTN. All rights reserved.<br>
            MTN (PTY) LTD is an authorised Financial Service Provider underwritten by Guardrisk Insurance Company. FSP license number: 44774
        </p>
        <a href="{{route('page.show', ['privacy-policy'])}}" class="link-to">Privacy policy</a>
        <a href="{{route('page.show', ['terms-and-conditions'])}}" class="link-to">Terms and conditions</a>

        <a class="logo" href="https://www.mtn.com">@svg('logo', '', '/images')</a>
        <div class="social">
            <a href="https://twitter.com/MTNGroup" target="_blank" rel="noopener">@svg('twitter', '#FFFFFF')</a>
            <a href="https://www.linkedin.com/company/mtn/" target="_blank" rel="noopener">@svg('linkedin', '#FFFFFF')</a>
            <a href="https://www.youtube.com/user/TheMTNGroup" target="_blank" rel="noopener">@svg('youtube', '#FFFFFF')</a>
        </div>
    </div>
</footer>
