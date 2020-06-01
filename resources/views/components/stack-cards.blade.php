@props(['cards'])

<div {{ $attributes->merge(['class' => 'stack-cards']) }}>
    @foreach($cards as $card)
    <div class="stack-card">
        <div class="tags">
            <span class="tag">{{ $card['group'] }}</span>
        </div>
        <h3>{{ $card['name'] }}</h3>
        <p>{{ $card['description'] }}</p>
        <div class="flags">
            @foreach($card['countries'] as $code => $country)
            <span title="$country">@svg($code, null, 'images/locations')</span>
            @endforeach
        </div>
        <a href="{{ $card['href'] }}" class="button">View</a>
    </div>
    @endforeach
</div>