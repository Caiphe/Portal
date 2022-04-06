@once
@push('styles')
<link rel="stylesheet" href="{{ mix('/css/components/pricing.css') }}">
@endpush
@endonce

<div id="pricing">
    <div class="rate-plan shadow">
        <div class="group">MTN</div>
        <div class="price"><sup>ZAR</sup> 2,250</div>
        <div class="period">PER MONTH</div>
        <a href="#" class="button dark">Sign up</a>
        <div class="transactions">25,000<sup>1</sup><span>transactions per month</span></div>
        <div class="cost-per-transaction">0.31c<span>per transaction credit</span></div>
        <div class="features">
            <h6>Features</h6>
            No monthly contracts<br>
            Business support<br>
            Online Technical Support<br>
            Other premium Services
        </div>
    </div>
    <div class="rate-plan shadow popular">
        <div class="group">MTN</div>
        <div class="price"><sup>ZAR</sup> 4,250</div>
        <div class="period">PER MONTH</div>
        <a href="#" class="button dark">Sign up</a>
        <div class="transactions">50,000<sup>1</sup><span>transactions per month</span></div>
        <div class="cost-per-transaction">0.31c<span>per transaction credit</span></div>
        <div class="features">
            <h6>Features</h6>
            No monthly contracts<br>
            Business support<br>
            Online Technical Support<br>
            Other premium Services
        </div>
    </div>
    <div class="rate-plan shadow">
        <div class="group">MTN</div>
        <div class="price"><sup>ZAR</sup> 8,250</div>
        <div class="period">PER MONTH</div>
        <a href="#" class="button dark">Sign up</a>
        <div class="transactions">100,000<sup>1</sup><span>transactions per month</span></div>
        <div class="cost-per-transaction">0.31c<span>per transaction credit</span></div>
        <div class="features">
            <h6>Features</h6>
            No monthly contracts<br>
            Business support<br>
            Online Technical Support<br>
            Other premium Services
        </div>
    </div>
</div>