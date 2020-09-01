@props(['cards'])

<div {{ $attributes->merge(['class' => 'stack-general']) }}>
    @foreach($cards as $card)
    <div class="stack-card">
        <h3>{{ $card['type'] }}</h3>
        <h4>{{ $card['title'] }}</h4>
        {!! $card['body'] !!}
        <a href="/{{ $card['slug'] }}">Read more @svg('arrow-forward')</a>
    </div>
    @endforeach
</div>